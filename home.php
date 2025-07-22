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
          <br/>notre rêve a toujours été de rendre la vie capillaire de nos clients meilleures en les permettant d'avoir des produits de première qualité afin de pouvoir transformer le quotidien de chacun
          <br/>daidiia zvidvjzui  ziu di fdeuui  uin du nusifdn ui nui nu nuinununsi n unnusuivdzn ui n nunuivniun iun  unununun uububububu yuuy uuu_è buè_ uhuuzufnvunuh içb nubuç zu_ç
          <br/>paveze ze o ao a jie keo  oieakvvievj okvezkvi ,ivz,i ,ai, a jen ken  eei enc,icie,c inii,,a  akc,ciin,cia,cneeconcacicnc
          cnicnccoanconioac
          enaineiacnonve
          je ne peux que dire bravo 
          apres si tu peux le faire 
          ca serait dommage quand meme 
          je e=ne pecxhx 
        </p>
      </div>  

  </div>


  <footer>
    <?php include('footer.php') ?>
 </footer>

</body>  
</html>