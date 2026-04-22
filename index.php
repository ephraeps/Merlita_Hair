<?php 
require('check_auth.php');
require('db.php');

// Recover all valid images ,
$imgb = $db->prepare("SELECT image,name FROM slideshow WHERE image IS NOT NULL AND image != '' ORDER BY add_at DESC");
$imgb->execute();
$slides = $imgb->fetchAll(PDO::FETCH_ASSOC);
$slideCount = count($slides);
$username = $_SESSION['user_name'] ?? null;
?>


<!DOCTYPE html>
<html lang="fr">
<head> 
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="global_style.css">
  <link rel="stylesheet" href="index_style.css">
  <link rel="icon" href="logo_merlitahair.png" type="image/x-icon">
  <title>Accueil</title>
  <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400;700&display=swap" rel="stylesheet">
 <?php include('header.php'); ?>
 <style>
    html {
      scroll-behavior: smooth;
    }
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
        transition: transform 0.5s ease-in-out;
    }

    .slider-css.auto-play {
        animation: slideAuto linear infinite;
    }

    .slide-css {
        min-width: 100%;
        height: 100%;
        box-sizing: border-box;
        position: relative;
        text-align: center;
    }

    .slide-css img {
        width: 100%;
        height: auto;
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

    /* Boutons de navigation */
    .slider-nav {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        background-color: rgba(0, 0, 0, 0.6);
        color: #dfd085;
        border: none;
        padding: 10px 15px;
        cursor: pointer;
        font-size: 1.2em;
        transition: background-color 0.3s;
        z-index: 10;
    }

    .slider-nav:hover {
        background-color: rgba(0, 0, 0, 0.9);
    }

    .prev {
        left: 10px;
        border-radius: 0 5px 5px 0;
    }

    .next {
        right: 10px;
        border-radius: 5px 0 0 5px;
    }

    /* Indicateurs de points */
    .slider-dots {
        text-align: center;
        padding: 10px 0;
        background: #222;
    }

    .dot {
        height: 10px;
        width: 10px;
        margin: 0 5px;
        background-color: #bbb;
        border-radius: 50%;
        display: inline-block;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    .dot.active {
        background-color: #dfd085;
    }

    /* Animation automatique */
  
    /* Adapte la durée selon le nombre de slides */
    .slider-css.auto-play {
        animation-duration: <?= max(1, $slideCount) * 4 ?>s;
    }

    .linkshead{
      text-align: center;
      margin-top: 35px;
      margin-bottom: 10px;
      text-weight: bold;
      font-family: 'Dancing Script', cursive;
    }
    .greeting 
    {
      text-align: center;
      margin-top: 20px;
      font-family: 'Dancing Script', cursive;
      font-size: 2em;
    }
    @media screen  and (max-width: 768px) {
      .greeting {
        font-size: 3.5em;
      }
      
      /* Minimize slider buttons on mobile */
      .slider-nav {
        padding: 5px 8px;
        font-size: 0.8em;
      }
      .prev {
        display: none; /* Hide previous button on mobile for better UX */
      }
      .next {
        display: none;
      }
    }
  </style>
</head>

<body class="page-home">  
  <div class="loader">
    <img src="logo_merlitahair.png" alt="logo_merlita_hair">
  </div>

  <div class="greeting">
    <h1>Bienvenue chez Merlita_Hair <?php
      if (isset($username) && !empty($username)) {
        echo ", " . htmlspecialchars($username);
      }
    
    ?> !</h1>
  </div>

  <?php if ($slideCount > 0): ?>
  <div class="slider-container">
    <div class="slider-css auto-play" id="sliderCss">
      <?php foreach($slides as $i => $slide): ?>
        <div class="slide-css">
          <img src="<?= htmlspecialchars($slide['image']) ?>"
               onerror="this.src='fallback-image.jpg'"
               alt="<?= htmlspecialchars($slide['name'] ?? 'Slide') ?>">
          <div class="slide-caption"><?= htmlspecialchars($slide['name'] ?? '') ?></div>
        </div>
      <?php endforeach; ?>
    </div>
    <div class="slider-buttons">
      <button class="slider-nav prev" onclick="previousSlide()">❮</button>
      <button class="slider-nav next" onclick="nextSlide()">❯</button>
    </div>

  </div>
  
  <?php if ($slideCount > 1): ?>
  <div class="slider-dots" id="dotsContainer">
    <?php for($i = 0; $i < $slideCount; $i++): ?>
      <span class="dot <?= $i === 0 ? 'active' : '' ?>" onclick="currentSlide(<?= $i ?>)"></span>
    <?php endfor; ?>
  </div>
  <?php endif; ?>
  
  <script>
    let currentIndex = 0;
    const totalSlides = <?= $slideCount ?>;
    const slider = document.getElementById('sliderCss');
    let autoPlayInterval;

    function updateSlider() {
      slider.style.transform = `translateX(-${currentIndex * 100}%)`;
      updateDots();
    }

    function updateDots() {
      const dots = document.querySelectorAll('.dot');
      dots.forEach((dot, index) => {
        dot.classList.toggle('active', index === currentIndex);
      });
    }

    function nextSlide() {
      currentIndex = (currentIndex + 1) % totalSlides;
      updateSlider();
      resetAutoPlay();
    }

    function previousSlide() {
      currentIndex = (currentIndex - 1 + totalSlides) % totalSlides;
      updateSlider();
      resetAutoPlay();
    }

    function currentSlide(index) {
      currentIndex = index;
      updateSlider();
      resetAutoPlay();
    }

    function resetAutoPlay() {
      // Arrête l'animation et la relance quand l'utilisateur interagit
      clearInterval(autoPlayInterval);
      startAutoPlay();
    }

    function startAutoPlay() {
      autoPlayInterval = setInterval(() => {
        currentIndex = (currentIndex + 1) % totalSlides;
        slider.style.transform = `translateX(-${currentIndex * 100}%)`;
        updateDots();
      }, <?= max(1, $slideCount) * 1500 ?>);
    }

    // Démarrer l'autoplay au chargement
    startAutoPlay();
  </script>  
  <?php else: ?>
    <div class="no-slides">Aucune image disponible pour le diaporama</div>
  <?php endif; ?>

  <h3 class="banner_terms">Offrez à vos cheveux le traitement royal qu'ils méritent.</h3>
  <div class="links">
    <h2 class="linkshead">Vous pouvez consulter nos différents produits et recettes sur ces pages : </h2>
    <p class="banner_text"><a href="products.php">Voir nos produits</a></p>
    <p class="banner_text"><a href="recipes.php">Voir nos recettes</a></p>
  </div>  

 

  
  <div class="about">
      
        <img class="img_about" src="logo_merlitahair.png" alt="logo_merlita">
          <h3>Merlita_Hair : Votre allié pour des cheveux sains et éclatants</h3>
      <div class="title_about">
        <h2>A propos</h2>
      </div>
      <div class="descr_about">  
        <p>Bienvenue chez Merlita_Hair, votre destination de confiance pour des produits capillaires naturels et des recettes maison.</p>
        <p>Nous croyons que des cheveux sains commencent par des soins authentiques et respectueux de la nature.</p> 
        <p>C'est pourquoi nous proposons une gamme soigneusement sélectionnée de produits capillaires naturels, exempts de produits chimiques agressifs, pour nourrir et revitaliser vos cheveux en profondeur.</p> 
        <p>En plus de nos produits, nous partageons des recettes maison simples et efficaces, utilisant des ingrédients naturels que vous pouvez trouver dans votre cuisine. Que vous cherchiez à hydrater, renforcer ou embellir vos cheveux, Merlita_Hair est là pour vous accompagner dans votre parcours capillaire. Découvrez la beauté naturelle de vos cheveux avec nous !</p>
      </div>
      
  </div>

  <footer>
    <?php include('footer.php') ?>
 </footer>

</body>  
</html>
