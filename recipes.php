<?php 
require('connexion.php');

// Récupérer toutes les catégories distinctes
$cat_stmt = $db->prepare("SELECT DISTINCT category FROM recipes ORDER BY category ASC");
$cat_stmt->execute();
$categories = $cat_stmt->fetchAll(PDO::FETCH_COLUMN);

// Filtre éventuel
$selected_category = $_GET['category'] ?? null;

if ($selected_category) {
    $recipes = $db->prepare("SELECT id, name, category, description, ingredients, preparation, application, frequency FROM recipes WHERE category = ? ORDER BY created_at DESC");
    $recipes->execute([$selected_category]);
} else {
    $recipes = $db->prepare("SELECT id, name, category, description, ingredients, preparation, application, frequency FROM recipes ORDER BY created_at DESC");
    $recipes->execute();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Merlita Hair - Recettes</title> 
    <link rel="stylesheet" href="global_style.css">
    <link rel="stylesheet" href="recipes_style.css">
</head>
<body class="recipes_page">
    <?php include('header.php'); ?>

    <main class="recipes-container">
        <div class="recipe-header">
            <img src="recipe_logo.png" alt="Logo recettes">
            <h1>Nos recettes</h1>
        </div>
        <div class="mini-category-header">
            <span>Filtrer par catégorie :</span>
            <?php foreach($categories as $cat): ?>
                <a 
                    href="recipes.php?category=<?= urlencode($cat) ?>" 
                    class="category-filter<?= ($selected_category === $cat) ? ' active' : '' ?>">
                    <?= htmlspecialchars($cat) ?>
                </a>
            <?php endforeach; ?>
            <?php if ($selected_category): ?>
                <a href="recipes.php" class="category-filter reset">Toutes</a>
            <?php endif; ?>
        </div>
        <div class="recipe-content">
            <?php foreach($recipes as $recipe): ?>
                    <div class="recipe_card">
                        <div class="head_recipe">
                            <h3 class="recipe_name"><?=htmlspecialchars($recipe['name']);?></h3>
                            <h4 class="recipe_category"><?=htmlspecialchars($recipe['category']); ?></h4>
                            <p class="recipe_desc"><?=htmlspecialchars($recipe['description']); ?></p>
                        </div>

                        <div class="recipe_footer" style="text-align: right;">
                            <a href="recipe_detail.php?id=<?= $recipe['id'] ?>" class="recipe-link">Voir plus</a>    

                        </div>    
                    </div>   
            <?php endforeach ?>
        </div>
    </main>

    <?php include('footer.php'); ?>
</body>
</html>