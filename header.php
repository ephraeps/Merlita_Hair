<?php
require_once('check_auth.php');
require('connexion.php');
$current_page = basename($_SERVER['PHP_SELF']);

$user_id = $_SESSION['user_id'] ?? null;
  
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel = "stylesheet" href = "header_style.css">    
</head>
<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-W30GEKWKHN"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-W30GEKWKHN');
</script>
<body>
    
<header class="header">
    <a href="index.php"><img class="logo" src="logo_merlitahair.png" alt="logo_merlitahair"></a>
    <h2 class="title">Merlita_Hair</h2>
    <input type="checkbox" id="menu-toggle">
    <label for="menu-toggle" class="menu-icon">
        <!-- Code SVG hamburger -->
        <svg width="32" height="32" viewBox="0 0 32 32">
            <rect y="7" width="32" height="4" rx="2" fill="#dfd085"/>
            <rect y="15" width="32" height="4" rx="2" fill="#dfd085"/>
            <rect y="23" width="32" height="4" rx="2" fill="#dfd085"/>
        </svg>
    </label>
    <nav>
        <ul class="navbar">
            <li class="nav-item"><a href="index.php" class="nav-link<?= $current_page === 'index.php' ? ' active' : '' ?>">Accueil</a></li>
            <li class="nav-item"><a href="products.php" class="nav-link<?= $current_page === 'products.php' ? ' active' : '' ?>">Produits</a></li>
            <li class="nav-item"><a href="recipes.php" class="nav-link<?= $current_page === 'recipes.php' ? ' active' : '' ?>">Recettes</a></li>
            <li class="nav-item"><a href="cart.php" class="nav-link<?= $current_page === 'cart.php' ? ' active' : '' ?>">Panier</a></li>
            <li class="nav-item"><a href="profile.php" class="nav-link<?= $current_page === 'profile.php' ? ' active' : '' ?>">Mon profil</a></li>
            <?php if (!empty($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1): ?>
                <li class="nav-item"><a href="admin.php" class="nav-link<?= $current_page === 'admin.php' ? ' active' : '' ?>">Admin</a></li>
            <?php endif; ?>    
            <?php if(!isset($_SESSION['user_id'])): ?>
                <li class="nav-item"><a href="login.php" class="nav-link<?= $current_page === 'login.php' ? ' active' : '' ?>">Se connecter</a></li>
                <li class="nav-item"><a href="create_user.php" class="nav-link<?= $current_page === 'create_user.php' ? ' active' : '' ?>">S'inscrire</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>

</body>
</html>
