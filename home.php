<?php 
require('connexion.php');

// Recover all valid images ,
$imgb = $db->prepare("SELECT * FROM products_matadi WHERE image IS NOT NULL AND image != ''");
$imgb->execute();
$slides = $imgb->fetchAll(PDO::FETCH_ASSOC);
$slideCount = count($slides);
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="global_style.css">
  <link rel="stylesheet" href="home_style.css">
  <title>Accueil</title>
  <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400;700&display=swap" rel="stylesheet">
 <?php include('header.php'); ?>
</head>
<body class="page-home">  
  <?php if ($slideCount > 0): ?>
    <div class="slider-container">
      <div class="slider">
        <?php foreach($slides as $slide): ?>
          <div class="slide">
            <img src="<?= htmlspecialchars($slide['image']) ?>" 
                 onerror="this.src='fallback-image.jpg'" 
                 alt="<?= htmlspecialchars($slide['name']) ?>">
            <div class="slide-caption"><?= htmlspecialchars($slide['name']) ?></div>
          </div>                                                               
        <?php endforeach; ?>  
      </div>
    </div>
  <?php else: ?>
    <div class="no-slides">Aucune image disponible pour le diaporama</div>
  <?php endif; ?>
    
  <h3 class="banner_terms">Rendez vos cheveux fiers de vous en les traitant comme des rois</h3>
  <div class="banner_text2"><p><a href="products.php">Voir produits</a></p></div>
  
  
  <div class="about">
      <h3 class= "tabout">En ce qui concerne cette entreprise</h3>
      <div class="descr_about">
        <img class="img_about" src="images/logo_merlitahair.JPG" alt="logo_merlita">
        <p>Merlita existe pour un réel but : Ne plus détruire ses cheveux, 
          <br/>notre rêve a toujours été test test test test test test testtest test vvtest test vtest test test testtest test
          <br/>notre rêve a toujours été test test test test test test testtest test vvtest test vtest test test testtest test
          <br/>notre rêve a toujours été test test test test test test testtest test vvtest test vtest test test testtest testnotre rêve a toujours été test test test test test test testtest test vvtest test vtest test test testtest testnotre rêve a toujours été test test test test test test testtest 
          test vvtest test vtest test test testtest test
        </p>
      </div>  

  </div>

  <footer>
    <?php include('footer.php') ?>
 </footer>

</body>  
</html>
