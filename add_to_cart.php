<?php
require('connexion.php');
require('check_auth.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['product_id']) && isset($_POST['city'])) {
    $product_id = $_POST['product_id'];
    $city = $_POST['city'];
    
    // Déterminer la table en fonction de la région
    $table_name = '';
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
        case 'muanda':
            $table_name = 'products_muanda';
            break;    

        default:
            header('Location: products.php');
            exit;
    }
    
    // Récupérer les infos du produit
    $stmt = $db->prepare("SELECT * FROM $table_name WHERE id = ?");
    $stmt->execute([$product_id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($product) {
        // Initialiser le panier si inexistant
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = array();
        }
        
        // Ajouter ou mettre à jour le produit dans le panier
        if (isset($_SESSION['cart'][$product_id])) {
            // Produit déjà dans le panier, augmenter la quantité
            $_SESSION['cart'][$product_id]['quantity'] += 1;
        } else {
            // Nouveau produit dans le panier
            $_SESSION['cart'][$product_id] = array(
                'name' => $product['name'],
                'price' => $product['price'],
                'image' => $product['image'],
                'quantity' => 1,
                'city' => $city
            );
        }
        
        header("Location: cart.php?success=Produit ajouté au panier");
        exit();
    }
}

header("Location: products.php");
exit();
?><?php
require('connexion.php');
require('check_auth.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['product_id']) && isset($_POST['city'])) {
    $product_id = $_POST['product_id'];
    $city = $_POST['city'];
    
    // Déterminer la table en fonction de la région
    $table_name = '';
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
        case 'muanda':
            $table_name = 'products_muanda';
            break;    

        default:
            header('Location: products.php');
            exit;
    }
    
    // Récupérer les infos du produit
    $stmt = $db->prepare("SELECT * FROM $table_name WHERE id = ?");
    $stmt->execute([$product_id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($product) {
        // Initialiser le panier si inexistant
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = array();
        }
        
        // Ajouter ou mettre à jour le produit dans le panier
        if (isset($_SESSION['cart'][$product_id])) {
            // Produit déjà dans le panier, augmenter la quantité
            $_SESSION['cart'][$product_id]['quantity'] += 1;
        } else {
            // Nouveau produit dans le panier
            $_SESSION['cart'][$product_id] = array(
                'name' => $product['name'],
                'price' => $product['price'],
                'image' => $product['image'],
                'quantity' => 1,
                'city' => $city
            );
        }
        
        header("Location: cart.php?success=Produit ajouté au panier");
        exit();
    }
}

header("Location: products.php");
exit();
?>