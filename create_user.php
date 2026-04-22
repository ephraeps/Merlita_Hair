<?php
require('db.php');

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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - Merlita_Hair</title>
    <link rel="stylesheet" href="global_style.css">
    <style>
        .create_user-page {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .auth-container {
            width: 90%;
            max-width: 500px;
            margin: 40px auto;
            padding: 30px;
            background-color: #1a1f35;
            border: 2px solid #e8747e;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(201, 161, 122, 0.2);
        }

        .auth-container .form-group {
            margin-bottom: 18px;
        }

        .auth-container .form-group select {
            background-color: white;
            color: #1a1f35;
            cursor: pointer;
            appearance: none;
            padding-right: 35px;
            background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 10px center;
            background-size: 20px;
            padding-right: 40px;
        }

        .create_user-logo {
            display: block;
            margin: 0 auto 20px;
            width: 140px;
            height: auto;
        }

        .auth-title {
            margin-bottom: 20px;
            font-size: 28px;
        }

        .errors {
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
        }

        .errors p {
            margin: 8px 0;
            font-size: 14px;
            line-height: 1.5;
        }

        .errors p:first-child {
            margin-top: 0;
        }

        .errors p:last-child {
            margin-bottom: 0;
        }

        .auth-btn {
            width: 100%;
            padding: 14px;
            font-size: 16px;
            margin-top: 10px;
        }

        .auth-link {
            margin-top: 15px;
            font-size: 14px;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .auth-container {
                width: 95%;
                margin: 30px auto;
                padding: 25px 20px;
                border-radius: 12px;
            }

            .create_user-logo {
                width: 120px;
                margin-bottom: 15px;
            }

            .auth-title {
                font-size: 24px;
                margin-bottom: 18px;
            }

            .auth-container .form-group {
                margin-bottom: 16px;
            }

            .auth-container .form-group label {
                font-size: 13px;
                margin-bottom: 6px;
            }

            .auth-container .form-group input,
            .auth-container .form-group select {
                font-size: 16px;
                padding: 12px;
                border-radius: 6px;
            }

            .errors {
                padding: 12px;
                margin-bottom: 18px;
            }

            .errors p {
                font-size: 13px;
                margin: 6px 0;
            }
        }

        @media (max-width: 480px) {
            .create_user-page {
                padding: 20px 0;
            }

            .auth-container {
                width: 100%;
                margin: 20px 0;
                padding: 20px 15px;
                border-radius: 10px;
                gap: 15px;
            }

            .create_user-logo {
                width: 100px;
                margin-bottom: 12px;
            }

            .auth-title {
                font-size: 20px;
                margin-bottom: 15px;
            }

            .auth-container .form-group {
                margin-bottom: 14px;
            }

            .auth-container .form-group label {
                font-size: 12px;
                font-weight: 600;
                margin-bottom: 5px;
            }

            .auth-container .form-group input,
            .auth-container .form-group select {
                font-size: 16px;
                padding: 12px;
                border-radius: 5px;
                min-height: 44px;
            }

            .auth-btn {
                padding: 12px;
                font-size: 15px;
                margin-top: 8px;
                min-height: 44px;
            }

            .errors {
                padding: 10px;
                margin-bottom: 15px;
                font-size: 12px;
            }

            .errors p {
                font-size: 12px;
                margin: 5px 0;
            }

            .auth-link {
                font-size: 12px;
                margin-top: 12px;
            }

            input[type="text"],
            input[type="email"],
            input[type="password"],
            input[type="tel"],
            select {
                -webkit-appearance: none;
                appearance: none;
            }
        }

        /* Tablet specific improvements */
        @media (max-width: 768px) and (min-width: 481px) {
            .auth-container {
                width: 90%;
                max-width: 450px;
            }
        }

        /* Better focus states for accessibility */
        .auth-container .form-group input:focus,
        .auth-container .form-group select:focus {
            outline: none;
            border-color: #e8747e;
            box-shadow: 0 0 8px rgba(201, 161, 122, 0.5);
        }

        /* Improved button interaction */
        .auth-btn {
            transition: all 0.3s ease;
        }

        .auth-btn:active {
            transform: translateY(1px);
        }
    </style>
</head>
<body class="create_user-page">
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
        
        <form method="POST" class="create-form">
            <div class="form-group">
                <label for="username">Nom d'utilisateur *</label>
                <input type="text" id="username" name="username" placeholder="ex: marie_beauty" required autofocus>
            </div>
            
            <div class="form-group">
                <label for="email">Email *</label>
                <input type="email" id="email" name="email" placeholder="exemple@email.com" required>
            </div>
            
            <div class="form-group">
                <label for="city">Ville *</label>
                <select id="city" name="city" required>
                    <option value="">-- Sélectionnez votre ville --</option>
                    <option value="matadi">Matadi</option>
                    <option value="kinshasa">Kinshasa</option>
                    <option value="mbanza-ngungu">Mbanza-Ngungu</option>
                    <option value="kisantu">Kisantu</option>
                </select>
            </div>

            <div class="form-group">
                <label for="address">Votre adresse *</label>
                <input type="text" id="address" name="address" placeholder="ex: Avenue de la Paix, n°45" required>
            </div>

            <div class="form-group">
                <label for="phone">Numéro de téléphone *</label>
                <input type="tel" id="phone" name="phone" placeholder="0123456789" pattern="[0-9]{10}" required>
            </div>

            <div class="form-group">
                <label for="password">Mot de passe (8 caractères min) *</label>
                <input type="password" id="password" name="password" placeholder="••••••••" required minlength="8">
            </div>
            
            <div class="form-group">
                <label for="confirm_password">Confirmer le mot de passe *</label>
                <input type="password" id="confirm_password" name="confirm_password" placeholder="••••••••" required>
            </div>
            
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
            <button type="submit" class="auth-btn">S'inscrire</button>
        </form>
        
        <p class="auth-link">Déjà un compte ? <a href="login.php">Se connecter</a></p>
    </div>
</body>
</html>
