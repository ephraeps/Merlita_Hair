<?php 
require('check_auth.php');
require('connexion.php');

// Recover all valid images ,
$imgb = $db->prepare("SELECT image,name FROM slideshow WHERE image IS NOT NULL AND image != '' ORDER BY add_at DESC");
$imgb->execute();
$slides = $imgb->fetchAll(PDO::FETCH_ASSOC);
$slideCount = count($slides);
?>


<!DOCTYPE html>
<html lang="fr">
<head> 
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="global_style.css">
  <link rel="stylesheet" href="index_style.css">
  <title>Accueil</title>
  <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400;700&display=swap" rel="stylesheet">
 <?php include('header.php'); ?>
</head>
<body class="page-home">  
  <div class="loader">
    <img src="logo_merlitahair.JPG" alt="logo_merlita_hair">
  </div>


  <?php if ($slideCount > 0): ?>
  <div class="slider-container">
    <div class="slider-css">
      <?php foreach($slides as $i => $slide): ?>
        <div class="slide-css">
          <img src="<?= htmlspecialchars($slide['image']) ?>"
               onerror="this.src='fallback-image.jpg'"
               alt="<?= htmlspecialchars($slide['name'] ?? 'Slide') ?>">
          <div class="slide-caption"><?= htmlspecialchars($slide['name'] ?? '') ?></div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
  <?php else: ?>
    <div class="no-slides">Aucune image disponible pour le diaporama</div>
  <?php endif; ?>
    
  <h3 class="banner_terms">Offrez à vos cheveux le traitement royal qu'ils méritent.</h3>
  <div class="banner_text2"><p><a href="products.php">Voir produits</a></p></div>
  <div class="banner_text2"><p><a href="recipes.php">Voir recettes</a></p></div>
  
  <div class="about">
      <div class="descr_about">
        <img class="img_about" src="logo_merlitahair.JPG" alt="logo_merlita">
          <h3>Merlita_Hair : Votre allié pour des cheveux sains et éclatants</h3>
      </div>  
      <h2>A propos</h2>
      <p>Bienvenue chez Merlita_Hair, votre destination de confiance pour des produits capillaires naturels et des recettes maison. Nous croyons que des cheveux sains commencent par des soins authentiques et respectueux de la nature. C'est pourquoi nous proposons une gamme soigneusement sélectionnée de produits capillaires naturels, exempts de produits chimiques agressifs, pour nourrir et revitaliser vos cheveux en profondeur. En plus de nos produits, nous partageons des recettes maison simples et efficaces, utilisant des ingrédients naturels que vous pouvez trouver dans votre cuisine. Que vous cherchiez à hydrater, renforcer ou embellir vos cheveux, Merlita_Hair est là pour vous accompagner dans votre parcours capillaire. Découvrez la beauté naturelle de vos cheveux avec nous !</p>

  </div>
  <style>
        .slider-container {
        width: 100%;
        max-width: 600px;
        margin: 30px auto 0 auto;
        overflow: hidden;
        position: relative;
        border-radius: 12px;
        background: #222;
    }

    .slider-css {
        display: flex;
        width: 100%;
        animation: slideAuto linear infinite;
    }

    .slide-css {
        min-width: 100%;
        box-sizing: border-box;
        position: relative;
        text-align: center;
    }

    .slide-css img {
        width: 100%;
        height: 260px;
        object-fit: cover;
        border-radius: 12px 12px 0 0;
    }

    .slide-caption {
        background: rgba(0,0,0,0.6);
        color: #dfd085;
        padding: 8px 0;
        font-size: 1.1em;
        border-radius: 0 0 12px 12px;
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
    }

    /* Animation automatique */
    @keyframes slideAuto {
        0% { transform: translateX(0); }
        20% { transform: translateX(0); }
        25% { transform: translateX(-100%); }
        45% { transform: translateX(-100%); }
        50% { transform: translateX(-200%); }
        70% { transform: translateX(-200%); }
        75% { transform: translateX(-300%); }
        95% { transform: translateX(-300%); }
        100% { transform: translateX(0); }
    }

    /* Adapte la durée selon le nombre de slides */
    .slider-css {
        animation-duration: <?= max(1, $slideCount) * 4 ?>s;
    }
  </style>


  <footer>
    <?php include('footer.php') ?>
 </footer>

</body>  
</html>
