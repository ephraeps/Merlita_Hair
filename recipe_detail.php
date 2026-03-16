<?php

require('connexion.php');


if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: recipes.php');
    exit();
}

$id = $_GET['id'];


$stmt = $db->prepare("SELECT * FROM recipes WHERE id = ?");
$stmt->execute([$id]);
$recipe = $stmt->fetch();

if (!$recipe) {
    header('Location: recipes.php');
    exit();
}

// error / success messages
$success_msg = $_GET['success'] ?? null;
$error_msg = $_GET['error'] ?? null;

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Merlita Hair - <?= htmlspecialchars($recipe['name']) ?></title>
    <link rel="stylesheet" href="global_style.css">
    <link rel="stylesheet" href="recipe_detail_style.css">
</head>
<body class="recipe-detail-page">
    <?php include('header.php'); ?>

    <main class="recipe-detail-container">
        <a href="recipes.php" class="back-link">← Retour aux recettes</a>
        
        <div class="recipe-detail-header">
            <h1><?= htmlspecialchars($recipe['name']) ?></h1>
            <span class="recipe-category"><?= htmlspecialchars($recipe['category']) ?></span>
        </div>

        <div class="recipe-detail-content">
            <section class="recipe-description">
                <h2>Description</h2>
                <p><?= nl2br(htmlspecialchars($recipe['description'])) ?></p>
            </section>

            <section class="recipe-ingredients">
                <h2>Ingrédients</h2>
                <p><?= nl2br(htmlspecialchars($recipe['ingredients'])) ?></p>
            </section>

            <section class="recipe-preparation">
                <h2>Préparation</h2>
                <p><?= nl2br(htmlspecialchars($recipe['preparation'])) ?></p>
            </section>

            <section class="recipe-application">
                <h2>Mode d'application</h2>
                <p><?= nl2br(htmlspecialchars($recipe['application'])) ?></p>
            </section>

            <section class="recipe-frequency">
                <h2>Fréquence d'utilisation</h2>
                <p><?= nl2br(htmlspecialchars($recipe['frequency'])) ?></p>
            </section>
        </div>
<!-- Comments -->
<?php
$success_msg = $_SESSION['success_msg'] ?? null;
$error_msg   = $_SESSION['error_msg']   ?? null;
$recipe_id   = $recipe['id'];            // current ID recipe
?>

<div class="recipe_comments">
    <h2>Commentaires</h2>

    <?php if ($success_msg): ?>
        <div class="alert success"><?= htmlspecialchars($success_msg) ?></div>
    <?php endif; ?>

    <?php if ($error_msg): ?>
        <div class="alert error"><?= htmlspecialchars($error_msg) ?></div>
    <?php endif; ?>

    <?php if (isset($_SESSION['user_id'])): ?>
        <!-- Adding form for comment -->
        <form action="add_recipe_comment.php" method="POST" class="comment-form" enctype="multipart/form-data">
            <input type="hidden" name="recipe_id" value="<?= $recipe_id ?>">
            <textarea name="comment" class="comment_area" minlength="5" required placeholder="Votre commentaire ..."></textarea>

            <!-- Adding images -->
            <label for="comment_image">Joindre une image (optionnel) :</label>
            <input type="file" name="comment_image" id="comment_image" accept="image/*">

            <button type="submit" class="comment_btn">Poster</button>
        </form>

    <?php else: ?>
        <p>
            Veuillez <a href="login.php">vous connecter</a> pour poster un commentaire.
        </p>
    <?php endif; ?>

    <!-- comments list -->
    <div class="comments-list">
        <?php
        $stmt = $db->prepare("
            SELECT rc.*, u.username, u.id AS user_id
            FROM   recipe_comments rc
            JOIN   users   u ON rc.user_id = u.id
            WHERE  rc.recipe_id = :recipe_id
            ORDER  BY rc.created_at DESC
        ");
        $stmt->execute(['recipe_id' => $recipe_id]);
        $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!$comments) {
            echo "<p class='no-comments'>Aucun commentaire pour cette recette. Soyez le premier à commenter !</p>";
        } else {
            foreach ($comments as $comment):

                // Anonymisation
                $username      = $comment['username'] ?? 'Utilisateur inconnu';
                $anon_username = substr($username, 0, 1) . str_repeat('*', max(0, strlen($username) - 1));

                // Is he the author
                $is_author = isset($_SESSION['user_id']) && $_SESSION['user_id'] == $comment['user_id'];
                ?>
                <div class="comment" id="comment-<?= htmlspecialchars($comment['id']) ?>">
                    <div class="comment-header">
                        <strong><?= htmlspecialchars($anon_username) ?></strong>
                        <small><?= htmlspecialchars($comment['created_at']) ?></small>
                    </div>

                    <div class="comment-body">
                        <p><?= nl2br(htmlspecialchars($comment['comment'])) ?></p>
                    </div>

                    <?php if (!empty($comment['image_url'])): ?>
                        <div class="comment-image">
                            <img src="<?= htmlspecialchars($comment['image_url']) ?>" alt="image du commentaire" style="max-width: 300px; border-radius: 8px; margin-top: 10px;">
                        </div>
                    <?php endif; ?>


                    <?php if ($is_author): ?>
                            <a href="del_recipe_comment.php?recipe_id=<?= $recipe_id ?>&id=<?= $comment['id'] ?>"
                               onclick="return confirm('Êtes‑vous sûr de vouloir supprimer ce commentaire ?')">
                                Supprimer
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
        <?php
            endforeach;
        }
        ?>
    </div>
</div>
    <?php include('footer.php'); ?>
</body>
</html>