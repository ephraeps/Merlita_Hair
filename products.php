<?php 
require('check_auth.php');
require('db.php'); 

// Retrieve logged-in user record (to get `city` etc.)
$user = null;
$user_id = $_SESSION['user_id'] ?? null;
if ($user_id) {
  $stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
  $stmt->execute([$user_id]);
  $user = $stmt->fetch(PDO::FETCH_ASSOC);
}


// Requeries for products
$mprnt = $db->prepare("SELECT * FROM products_matadi ORDER BY add_at DESC");
$mprnt->execute();

$mbprnt = $db->prepare("SELECT * FROM products_mbanzangungu ORDER BY add_at DESC");
$mbprnt->execute();

$kprnt = $db->prepare("SELECT * FROM products_kinshasa ORDER BY add_at DESC");
$kprnt->execute();

$kiprnt = $db->prepare("SELECT * FROM products_kisantu ORDER BY add_at DESC");
$kiprnt->execute();

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
  <link rel="stylesheet" href="products_style.css">
  <title>Produits - Merlita_Hair</title>
  <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400;700&display=swap" rel="stylesheet">
  <?php include('header.php');?>
</head>
<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-W30GEKWKHN"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-W30GEKWKHN');
</script>
<body class="page-products">

  <?php if ($user && isset($user['city'])): 
    // PRODUCTS PRINTING?>
 
    <!-- Matadi -->
    <?php if ($user['city'] == 'matadi'): ?>
      <div class="mprnt_products">
        <?php foreach($mprnt->fetchAll(PDO::FETCH_ASSOC) as $mproduct): ?>
          <a href="product_detail.php?id=<?= $mproduct['id'] ?>&city=matadi" class="product_link">  
            <div class="product_card">
              <img class="img_product" src="<?= $mproduct['image'] ?>" alt="Image_non_disponible">
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
              <img class="img_product" src="<?= $mbproduct['image'] ?>" alt="image_product">
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
              <img class="img_product" src="<?= $kproduct['image'] ?>" alt="image_product">
              <div class="product_details">
                <h3 class="product_title"><?= htmlspecialchars($kproduct['name']) ?></h3>
                <span class="product_price"><?= htmlspecialchars($kproduct['price']) ?> FC</span>
              </div>
            </div>  
          </a>
        <?php endforeach; ?>
      </div>
    <!-- kisantu -->
     <?php elseif ($user['city'] == 'kisantu'): ?>
        <div class="kiprnt_products">
          <?php foreach($kiprnt->fetchAll(PDO::FETCH_ASSOC) as $moproduct): ?>
            <a href="product_detail.php?id=<?= $moproduct['id'] ?>&city=kisantu" class="product_link">  
              <div class="product_card">
                <img class="img_product" src="<?= $moproduct['image'] ?>" alt="image_product">
                <div class="product_details">
                  <h3 class="product_title"><?= htmlspecialchars($moproduct['name']) ?></h3>
                  <span class="product_price"><?= htmlspecialchars($moproduct['price']) ?> FC</span>
                </div>
              </div>  
            </a>
          <?php endforeach; ?>
       </div>

       <!-- If user is not connected -->
        <!-- Print products from matadi and notice the user that those prices are for matadi customers-->
      <?php if ($user['city'] == 'null'): ?>
        <div class="mprnt_products">
          <p>Les prix affichés sont pour les clients de la ville de Matadi.Veuillez-vous connecter pour avoir des prix précis. </p>
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
      <?php endif; ?>




    <?php else: ?>
      <p>Ville non reconnue. Veuillez mettre à jour votre profil.</p>
  <?php endif; ?>

  

  <?php endif; ?>

  <?php if (!$user): ?>
            <div class="mprnt_products">
              <p class="not_user_head">Les prix affichés sont pour les clients de Matadi. Veuillez-vous connecter pour avoir des prix précis.</p>
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
  <?php endif; ?>

  <?php include('footer.php'); ?>
</body>
</html>  
