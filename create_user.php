<?php


$username = $email = $city = $address = $phone = $password = $confirm_password = '';
$errors = [];

if($_SERVER['REQUEST_METHOD'] === 'POST'){ 
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $city = trim($_POST['city']);
    $address = trim($_POST['address']);
    $phone = trim($_POST['phone']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    
    if (empty($username)) $errors[] = "Le nom d'utilisateur est requis";
    if (empty($email)) $errors[] = "L'email est requis";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Email invalide";
    if (empty($city)) $errors[] = "Le nom de votre ville est requis";
    if (empty($address)) $errors[] = "Veuillez renseigner votre adresse";
    if (!preg_match('/^[0-9]{10}$/', $phone)) $errors[] = "Format de téléphone invalide";
    if (empty($password)) $errors[] = "Le mot de passe est requis";
    if ($password !== $confirm_password) $errors[] = "Les mots de passe ne correspondent pas";
    if (strlen($password) < 8) $errors[] = "Le mot de passe doit avoir au moins 8 caractères";

    // Validation des villes autorisées
    $allowed_cities = ['matadi', 'kinshasa', 'mbanza-ngungu','kisantu'];
    if (!in_array(strtolower($city), $allowed_cities)) {
        $errors[] = "Ville non reconnue. Choisissez entre matadi, kinshasa ou mbanza-ngungu";
    }

    if (empty($errors)) {
        // Vérifier si l'utilisateur existe déjà
        $check = $db->prepare("SELECT id FROM users WHERE username = ? OR email = ? OR phone = ?");
        $check->execute([$username, $email, $phone]);
        
        if ($check->fetch()) {
            $errors[] = "Nom d'utilisateur, email ou téléphone déjà utilisé";
        } else {
            // Hachage du mot de passe
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            
            // Insertion dans la base avec TOUS les champs
            $insert = $db->prepare("INSERT INTO users (username, email, password_hash, city, address, phone) VALUES (:user, :mail, :pass, :city, :address, :phone)");
            if ($insert->execute([':user' => $username, ':mail' => $email, ':pass' => $password_hash, ':city' => $city, ':address' => $address, ':phone' => $phone])) {
                header('Location: login.php?registration=success');
                exit;
            } else {
                $errors[] = "Erreur lors de l'inscription";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription - Merlita_Hair</title>
    <link rel="stylesheet" href="global_style.css">
</head>
<body>
    <div class="auth-container">
        <img src="logo_merlitahair.png" alt="Logo Merlita Hair" class="create_user-logo">
        <h2 class="auth-title">Créer un compte</h2>
        
        <?php if (!empty($errors)): ?>
            <div class="errors">
                <?php foreach ($errors as $error): ?>
                    <p><?= htmlspecialchars($error) ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <label for="username">Nom d'utilisateur</label>
                <input type="text" id="username" name="username" placeholder="Entrez votre nom d'utilisateur" required autofocus>
            </div>
            
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="Entrez votre adresse email" required>
            </div>
            
            <div class="form-group">
                <label for="city">Ville</label>
                <select id="city" name="city" required>
                    <option value="">-- Sélectionnez --</option>
                    <option value="matadi">Matadi</option>
                    <option value="kinshasa">Kinshasa</option>
                    <option value="mbanza-ngungu">Mbanza-Ngungu</option>
                    <option value="kisantu">Kisantu</option>
                </select>
            </div>

            <div class="form-group">
                <label for="address">Votre adresse</label>
                <input type="text" id="address" name="address" required>
            </div>

            <div class="form-group">
                <label for="password">Mot de passe (8 caractères minimum)</label>
                <input type="password" id="password" name="password" required minlength="8">
            </div>
            
            <div class="form-group">
                <label for="confirm_password">Confirmer le mot de passe</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>

            <div class="form-group">
                <label for="phone">Numéro de téléphone</label>
                <input type="tel" id="phone" name="phone" placeholder="Entrez votre numéro de téléphone" pattern="[0-9]{10}" required>
            </div>
            
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
            <button type="submit" class="auth-btn">S'inscrire</button>
        </form>
        
        <p class="auth-link">Déjà un compte ? <a href="login.php">Se connecter</a></p>
    </div>
</body>
</html>
