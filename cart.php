<?php

require('check_auth.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Récupérer les articles du panier depuis la session
$cart_items = isset($_SESSION['cart']) ? $_SESSION['cart'] : array();
 
// Traitement de la suppression d'un article
if (isset($_GET['action']) && $_GET['action'] == 'remove' && isset($_GET['id'])) {
    $product_id = $_GET['id'];
    if (isset($cart_items[$product_id])) {
        unset($cart_items[$product_id]);
        $_SESSION['cart'] = $cart_items;
    }
}

// Traitement de la mise à jour des quantités
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_cart'])) {
    foreach ($_POST['quantities'] as $product_id => $quantity) {
        if (isset($cart_items[$product_id]) && $quantity > 0) {
            $cart_items[$product_id]['quantity'] = $quantity;
        }
    }
    $_SESSION['cart'] = $cart_items;
}
?>
<!DOCTYPE html>
<html lang="fr"> 
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Votre Panier</title>
    <link rel="stylesheet" href="global_style.css">
    <link rel="stylesheet" href="cart_style.css">
</head>
<body class="cart-page">
    <?php include('header.php'); ?>
    
    <div class="cart-container">
        <h1>Votre Panier</h1>
        
        <?php if (isset($_GET['success'])): ?>
            <div class="message success"><?= htmlspecialchars($_GET['success']) ?></div>
        <?php endif; ?>
        
        <?php if (isset($_GET['error'])): ?>
            <div class="message error"><?= htmlspecialchars($_GET['error']) ?></div>
        <?php endif; ?>
        
        <p class="notice">Après avoir passé votre commande, vous recevrez un mail de l'adresse : support@merlitahair.com</p>
        <p class="notice">Le mail fera office de facture et vous détaillera les différentes prochaines étapes, rassurez vous donc de correctement saisir non seulement votre adresse email mais aussi vos coordonées lors de la commande.</p>

        <?php if (empty($cart_items)): ?>
            <div class="empty-cart">
                <p>Votre panier est vide</p>
                <a href="products.php" class="continue-shopping">Continuer vos achats</a>
            </div>
        <?php else: ?>
            <form action="cart.php" method="post">
                <table class="cart-table">
                    <thead class="thead">
                        <tr>
                            <th>Produit</th>
                            <th>Prix</th>
                            <th>Quantité</th>
                            <th>Total</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $total = 0;
                        foreach ($cart_items as $product_id => $item): 
                            $item_total = $item['price'] * $item['quantity'];
                            $total += $item_total;
                        ?>  
                            <tr>
                                <td>
                                    <div class="product-info">
                                        <?php if (!empty($item['image'])): ?>
                                            <img src="<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>">
                                        <?php else: ?>
                                            <div class="no-image">Pas d'image</div>
                                        <?php endif; ?>
                                        <div class="product-name"><?= htmlspecialchars($item['name']) ?></div>
                                    </div> 
                                </td>
                                <td class ="product_price"><?php echo number_format($item['price'], 2); ?> FC</td>
                                <td>
                                    <input type="number" name="quantities[<?php echo $product_id; ?>]" 
                                           value="<?php echo $item['quantity']; ?>" min="1">
                                </td>
                                <td class="total_price_product"><?php echo number_format($item_total, 2); ?> FC</td>
                                <td>
                                    <a href="cart.php?action=remove&id=<?php echo $product_id; ?>" class="remove-btn">Supprimer</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" class="total-label">Total</td>
                            <td colspan="2" class="total-price"><?php echo number_format($total, 2); ?> FC</td>
                        </tr>-
                    </tfoot>
                </table>

               <?php 
                $total_amount = $total;
                $_SESSION['cart_total'] = $total_amount; ?>
                
                <div class="cart-actions">
                    <button type="submit" name="update_cart" class="update-btn">Mettre à jour le total</button>
                    <a href="products.php" class="continue-shopping">Continuer vos achats</a>
                    <a href="checkout.php" class="checkout-btn">Passer la commande</a>
                </div>
            </form>
        <?php endif; ?>
    </div>
    <?php include('footer.php'); ?>
</body>
</html>
