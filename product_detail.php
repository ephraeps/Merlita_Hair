<?php 
require('check_auth.php');
require('connexion.php');

// Look through images
function resolveProductImage($productId) {
    $basePath = "uploads/products/img_$productId";
    foreach (['jpg', 'jpeg', 'png', 'gif'] as $ext) {
        if (file_exists("$basePath.$ext")) {
            return "$basePath.$ext";
        }
    }
    return "images/default.jpg";
}

// Getting ID and city of the product
$product_id = $_GET['id'] ?? null;
$city = $_GET['city'] ?? null;

if (!$product_id || !$city) {
    header('Location: products.php');
    exit;
}

// Determinate table depending on city
switch($city) {
    case 'matadi':
        $table_name = 'products_matadi';
        break;
    case 'mbanzangungu':
        $table_name = 'products_mbanzangungu';
        break;
    case 'kinshasa':
        $table_name = 'products_kinshasa';
        break;
    default:
        header('Location: products.php');
        exit;
}

$stmt = $db->prepare("SELECT * FROM $table_name WHERE id = ?");
$stmt->execute([$product_id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    header('Location: products.php');
    exit;
}

$_SESSION['current_product_city'] = $city;
$success_msg = $_GET['success'] ?? null;
$error_msg = $_GET['error'] ?? null;

include('header.php'); 
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="global_style.css">
    <link rel="stylesheet" href="product_detail.css">
    <title><?= htmlspecialchars($product['name']) ?> - Merlita_Hair</title>
</head>
<body class="product_detail-page">
    <a href="products.php" class="back-link">← Retour aux produits</a>
    <div class="product-detail-container">  
         <div class="product-detail-image">
            <img src="<?=($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
        </div>
        <div class="product-detail-info">
            <h1><?= htmlspecialchars($product['name']) ?></h1>
            <p class="product-price"><?= htmlspecialchars($product['price']) ?> FC</p>
            
            <div class="product-description">
                <h3>Description</h3>
                <p><?= htmlspecialchars($product['description']) ?></p>
            </div>
            
            <?php if (isset($_SESSION['user_id'])): ?>
                <form action="add_to_cart.php" method="post" class="add-to-cart-form">
                    <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                    <input type="hidden" name="city" value="<?= $city ?>">
                    <button type="submit" class="cart_btn">Ajouter au panier</button>
                </form>
            <?php else: ?>
                <a href="login.php" class="login-to-add">Connectez-vous pour pouvoir ajouter ce produit au panier</a>
            <?php endif; ?>
        </div>
    </div>

    <div class="product-comments">
        <h2>Commentaires</h2>
        <?php if ($success_msg): ?>
            <div class="alert success"><?= htmlspecialchars($success_msg) ?></div>
        <?php endif; ?>
        
        <?php if ($error_msg): ?>
            <div class="alert error"><?= htmlspecialchars($error_msg) ?></div>
        <?php endif; ?>
        
        <details>
            <summary class="rule_cmt">Pourquoi commenter après utilisation ?</summary>    
            <p>Chaque commentaire positif :</p>
            <ul>
                <li>Vous rapporte 2 à 6 points</li>
                <li>Ces points peuvent être utilisés dans la boutique</li>
            </ul>
            <p>Vous ne pouvez commenter qu'après avoir acheté le produit</p>
        </details>
        
        <?php if (isset($_SESSION['user_id'])): // If the user is connected :?>
           <?php if (isset($_SESSION['user_id'])): ?>
        <form action="add_comment.php" method="POST" class="comment-form" enctype="multipart/form-data">
            <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
            <textarea name="comment" required placeholder="Votre commentaire ..." class="comment_area" minlength="5"></textarea>
            
            <!-- Ajout du champ pour l'image -->
            <div class="form-group">
                <label for="comment_image">Ajouter une photo (optionnel) :</label>
                <input type="file" name="comment_image" id="comment_image" accept="image/*">
                <small>Formats acceptés: JPG, PNG (max 2MB)</small>
            </div>
            
            <button type="submit" class="comment_btn">Poster</button>
        </form>
        <?php else: ?>
            <p>Veuillez <a href="login.php">vous connecter</a> pour poster un commentaire</p>
        <?php endif; ?>
                <?php else: ?>
            <p>Veuillez <a href="login.php">vous connecter</a> pour poster un commentaire</p>
        <?php endif; ?>

        <div class="comments-list">
        <?php
        // Comments
            $query = $db->prepare("SELECT pc.*, u.username, u.id AS user_id FROM product_comments pc INNER JOIN users u ON pc.user_id = u.id WHERE pc.product_id = ? AND pc.city = ? ORDER BY pc.created_at DESC");
            $query->execute([$product_id, $city]);
            $comments = $query->fetchAll(PDO::FETCH_ASSOC);

            if (empty($comments)) {
                echo "<p class='no-comments'>Aucun commentaire pour ce produit. Soyez le premier à commenter !</p>";
            } else {
                foreach ($comments as $comment) {
                    $anon_username = substr($comment['username'], 0, 1) . str_repeat('*', max(0, strlen($comment['username']) - 1));
                    $is_author = isset($_SESSION['user_id']) && $_SESSION['user_id'] == $comment['user_id'];
        ?>
            <div class="comment" id="comment-<?= htmlspecialchars($comment['id']) ?>">
                <div class="comment-header">
                    <strong><?= htmlspecialchars($anon_username) ?></strong>
                    <small><?= htmlspecialchars($comment['created_at']) ?></small>
                </div>
                <div class="comment-body">
                    <p><?= htmlspecialchars($comment['comment']) ?></p>
                    <?php if (!empty($comment['image_path'])): ?>
                        <div class="comment-image">
                            <img src="<?= htmlspecialchars($comment['image_path']) ?>" alt="Image du commentaire">
                        </div>
                    <?php endif; ?>
                </div>
                <?php if ($is_author): ?>
                <div class="comment-actions">
                    <a href="delete_comment.php?id=<?= $comment['id'] ?>&product_id=<?= $product_id ?>&city=<?= $city ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce commentaire ?')">Supprimer</a>
                </div>
                <?php endif; ?>
            </div>
        <?php
                }
            }
        ?>
        </div>
    </div>

</body>
<?php include('footer.php'); ?>
</html>

