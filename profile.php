<?php
session_start();
error_log("Profile.php - Current session data: " . print_r($_SESSION, true));
require('check_auth.php');
require('connexion.php');

// Ajout temporaire pour récupérer is_admin

// Récupérer is_admin depuis la base
$is_admin = 0;
try {
    $stmt = $db->prepare("SELECT is_admin FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row) {
        $is_admin = (int)$row['is_admin'];
    }
} catch (PDOException $e) {
    // Optionnel : gestion d'erreur
}

// Stocke la valeur dans la session pour le header
$_SESSION['is_admin'] = $is_admin;

// Management updates
$editing_field = $_GET['edit'] ?? null;
$allowed_fields = ['username', 'email', 'city', 'address', 'phone', 'is_admin'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['field'], $_POST['value'])) {
        $field = $_POST['field'];
        $value = trim($_POST['value']);
        
        // Validation
        if (in_array($field, $allowed_fields)) {
            try {
                $stmt = $db->prepare("UPDATE users SET $field = ? WHERE id = ?");
                $stmt->execute([$value, $_SESSION['user_id']]);
                $_SESSION['success'] = "Modification enregistrée !";
                header("Location: profile.php");
                exit;
            } catch (PDOException $e) {
                $_SESSION['error'] = "Erreur : " . $e->getMessage();
            }
        }
    }
}

// data recuperation
try {
    $stmt = $db->prepare("SELECT username, email, city, address, phone FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        session_destroy();
        header('Location: login.php');
        exit;
    }
} catch (PDOException $e) {
    die("Erreur de base de données : " . $e->getMessage());
}

// Define variables for printing
$username = $user['username'] ?? 'Non renseigné';
$email = $user['email'] ?? 'Non renseigné';
$city = $user['city'] ?? 'Non renseigné';
$address = $user['address'] ?? 'Non renseigné';
$phone = $user['phone'] ?? 'Non renseigné';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mon profil - Merlita_Hair</title>
    <link rel="stylesheet" href="global_style.css">
    <link rel="stylesheet" href="profile_style.css">
</head>
<body>
    <?php include('header.php'); ?>
    
    <div class="profile-container">
        <h1>Mon profil</h1>
        
        <!-- Alert message -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert success"><?= $_SESSION['success'] ?></div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert error"><?= $_SESSION['error'] ?></div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>
        
        <div class="profile-info">
            <!--Username -->
            <div class="info-group">
                <label>Nom d'utilisateur</label>
                <?php if ($editing_field === 'username'): ?>
                    <form method="POST" class="edit-form">
                        <input type="hidden" name="field" value="username">
                        <input type="text" name="value" value="<?= htmlspecialchars($username) ?>" required>
                        <button type="submit" class="edit-btn">Valider</button>
                        <a href="profile.php" class="edit-btn">Annuler</a>
                    </form>
                <?php else: ?>
                    <span><?= htmlspecialchars($username) ?></span>
                    <a href="profile.php?edit=username#username" class="edit-btn">Modifier</a>
                <?php endif; ?>
            </div>
            
            <!-- Email -->
            <div class="info-group">
                <label>Email</label>
                <?php if ($editing_field === 'email'): ?>
                    <form method="POST" class="edit-form">
                        <input type="hidden" name="field" value="email">
                        <input type="email" name="value" value="<?= htmlspecialchars($email) ?>" required>
                        <button type="submit" class="edit-btn">Valider</button>
                        <a href="profile.php" class="edit-btn">Annuler</a>
                    </form>
                <?php else: ?>
                    <span><?= htmlspecialchars($email) ?></span>
                    <a href="profile.php?edit=email#email" class="edit-btn">Modifier</a>
                <?php endif; ?>
            </div>
            
            <!-- city -->
            <div class="info-group">
                <label>Ville</label>
                <?php if ($editing_field === 'city'): ?>
                    <form method="POST" class="edit-form">
                        <input type="hidden" name="field" value="city">
                        <select name="value" class="edit-btn" required>
                            <option value="matadi" <?= $city === 'matadi' ? 'selected' : '' ?>>Matadi</option>
                            <option value="kinshasa" <?= $city === 'kinshasa' ? 'selected' : '' ?>>Kinshasa</option>
                            <option value="mbanza-ngungu" <?= $city === 'mbanza-ngungu' ? 'selected' : '' ?>>Mbanza-Ngungu</option>
                            <option value="kisantu" <?= $city === 'kisantu' ? 'selected' : '' ?>>kisantu</option>
                        </select>
                         <button type="submit" class="edit-btn">Valider</button>
                        <a href="profile.php" class="edit-btn">Annuler</a>
                    </form>
                <?php else: ?>
                    <span><?= htmlspecialchars($city) ?></span>
                    <a href="profile.php?edit=city#city" class="edit-btn">Modifier</a>
                <?php endif; ?>
            </div>
            
            <!-- Adress -->
            <div class="info-group">
                <label>Adresse</label>
                <?php if ($editing_field === 'address'): ?>
                    <form method="POST" class="edit-form">
                        <input type="hidden" name="field" value="address">
                        <input type="text" name="value" value="<?= htmlspecialchars($address) ?>" required>
                        <button type="submit" class ="edit-btn">Valider</button>
                        <a href="profile.php" class="edit-btn">Annuler</a>
                    </form>
                <?php else: ?>
                    <span><?= htmlspecialchars($address) ?></span>
                    <a href="profile.php?edit=address#address" class="edit-btn">Modifier</a>
                <?php endif; ?>
            </div>
            
            <!-- phone -->
            <div class="info-group">
                <label>Téléphone</label>
                <?php if ($editing_field === 'phone'): ?>
                    <form method="POST" class="edit-form">
                        <input type="hidden" name="field" value="phone">
                        <input type="tel" name="value" value="<?= htmlspecialchars($phone) ?>" 
                               pattern="[0-9]{10}" required>
                        <button type="submit" class="edit-btn">Valider</button>
                        <a href="profile.php" class="edit-btn">Annuler</a>
                    </form>
                <?php else: ?>
                    <span><?= htmlspecialchars($phone) ?></span>
                    <a href="profile.php?edit=phone#phone" class="edit-btn">Modifier</a>
                <?php endif; ?>
            </div>
        </div>

        <!-- Déconnexion du compte -->
        <div class="logout-section">
            <a href="logout.php" class="logout-btn">Déconnexion</a>
        </div>
        
        <!-- Suppression de compte -->
        <div class="delete-account-section">
            <form method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer votre compte ? Cette action est irréversible.');">
                <input type="hidden" name="delete_account" value="1">
                <button type="submit" class="delete-account-btn">Supprimer mon compte</button>
            </form>
        </div>
    </div>

<?php include('footer.php'); ?>
</body>
</html>