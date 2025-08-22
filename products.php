<?php 
require('check_auth.php');
require('connexion.php'); 

// Checking the user
$userId = $_SESSION['user_id'] ?? null; 

if ($userId) {
    $userQuery = $db->prepare("SELECT city FROM users WHERE id = ?");
    $userQuery->execute([$userId]);
    $user = $userQuery->fetch(PDO::FETCH_ASSOC); // Array
} else {
    die("Utilisateur non connecté");
}

// Requeries for products
$mprnt = $db->prepare("SELECT * FROM products_matadi ORDER BY add_at DESC");
$mprnt->execute();

$mbprnt = $db->prepare("SELECT * FROM products_mbanzangungu ORDER BY add_at DESC");
$mbprnt->execute();

$kprnt = $db->prepare("SELECT * FROM products_kinshasa ORDER BY add_at DESC");
$kprnt->execute();

$muprnt = $db->prepare("SELECT * FROM products_moanda ORDER BY add_at DESC");

// Look through images
function resolveProductImage($productId) {
    $basePath = "uploads/img_$productId";
    foreach (['jpg', 'jpeg', 'png', 'gif'] as $ext) {
        if (file_exists("$basePath.$ext")) {
            return "$basePath.$ext";
        }
    }
    return "uploads/products";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="global_style.css">
  <link rel="stylesheet" href="products_style.css">
  <title>Produits - Merlita_Hair</title>
  <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400;700&display=swap" rel="stylesheet">
</head>
<body class="page-products">
  <?php include('header.php');
  // PRODUCTS PRINTING
  ?>

  <?php if ($user && isset($user['city'])): ?>
    <!-- Matadi -->
    <?php if ($user['city'] == 'matadi'): ?>
      <div class="mprnt_products">
        <?php foreach($mprnt->fetchAll(PDO::FETCH_ASSOC) as $mproduct): ?>
          <a href="product_detail.php?id=<?= $mproduct['id'] ?>&city=matadi" class="product_link">  
            <div class="product_card">
              <img class="img_product" src="<?= $mproduct['image'] ?>" alt="image_product">
              <div class="product_details">
                <h3 class="product_title"><?= htmlspecialchars($mproduct['name']) ?></h3>
                <span class="product_price"><?= htmlspecialchars($mproduct['price']) ?> FC</span>
              </div>
            </div>  
          </a>
        <?php endforeach; ?>
      </div>

    <!-- Mbanza-Ngungu -->
    <?php elseif ($user['city'] == 'mbanza-ngungu'): ?>
      <div class="mprnt_products">
        <?php foreach($mbprnt->fetchAll(PDO::FETCH_ASSOC) as $mbproduct): ?>
          <a href="product_detail.php?id=<?= $mbproduct['id'] ?>&city=mbanzangungu" class="product_link">  
            <div class="product_card">
              <img class="img_product" src="<?= resolveProductImage($mbproduct['id']) ?>" alt="image_product">
              <div class="product_details">
                <h3 class="product_title"><?= htmlspecialchars($mbproduct['name']) ?></h3>
                <span class="product_price"><?= htmlspecialchars($mbproduct['price']) ?> FC</span>
              </div>
            </div>  
          </a>
        <?php endforeach; ?>
      </div>

    <!-- Kinshasa -->
    <?php elseif ($user['city'] == 'kinshasa'): ?>
      <div class="mprnt_products">
        <?php foreach($kprnt->fetchAll(PDO::FETCH_ASSOC) as $kproduct): ?>
          <a href="product_detail.php?id=<?= $kproduct['id'] ?>&city=kinshasa" class="product_link">  
            <div class="product_card">
              <img class="img_product" src="<?= resolveProductImage($kproduct['id']) ?>" alt="image_product">
              <div class="product_details">
                <h3 class="product_title"><?= htmlspecialchars($kproduct['name']) ?></h3>
                <span class="product_price"><?= htmlspecialchars($kproduct['price']) ?> FC</span>
              </div>
            </div>  
          </a>
        <?php endforeach; ?>
      </div>
    <!-- moanda -->
     <?php elseif ($user['city'] == 'moanda'): ?>
        <div class="muprnt_products">
          <?php foreach($kprnt->fetchAll(PDO::FETCH_ASSOC) as $kproduct): ?>
            <a href="product_detail.php?id=<?= $kproduct['id'] ?>&city=kinshasa" class="product_link">  
              <div class="product_card">
                <img class="img_product" src="<?= resolveProductImage($kproduct['id']) ?>" alt="image_product">
                <div class="product_details">
                  <h3 class="product_title"><?= htmlspecialchars($kproduct['name']) ?></h3>
                  <span class="product_price"><?= htmlspecialchars($kproduct['price']) ?> FC</span>
                </div>
              </div>  
            </a>
          <?php endforeach; ?>
       </div>



    <?php else: ?>
      <p>Aucun produit disponible pour votre ville (<?= htmlspecialchars($user['city']) ?>).</p>
    <?php endif; ?>

  <?php else: ?>
    <p>Ville inconnue ou utilisateur non connecté.</p>
  <?php endif; ?>

  <?php include('footer.php'); ?>
</body>
</html>
