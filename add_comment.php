<?php
require('connexion.php');
session_start();

// Vérification de la connexion utilisateur
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "Veuillez vous connecter pour commenter";
    header("Location: login.php");
    exit;
}

// Validation des données
if (!isset($_POST['product_id'], $_POST['comment'], $_POST['city'])) {
    $_SESSION['error'] = "Données manquantes pour le commentaire";
    header("Location: products.php");
    exit;
}

$product_id = (int)$_POST['product_id'];
$city = $_POST['city'];
$comment = trim($_POST['comment']);
$user_id = $_SESSION['user_id'];

// Validation supplémentaire
if (empty($comment) || strlen($comment) < 5) {
    $_SESSION['error'] = "Le commentaire doit contenir au moins 5 caractères";
    header("Location: product_detail.php?id=$product_id&city=$city");
    exit;
}

$image_path = null;

// Gestion de l'upload de l'image (optionnelle)
if (
    isset($_FILES['comment_image']) &&
    $_FILES['comment_image']['error'] === UPLOAD_ERR_OK &&
    !empty($_FILES['comment_image']['name'])
) {
    $upload_dir = 'uploads/products_comments/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }

    // Vérification du type de fichier
    $allowed_types = ['image/jpeg', 'image/png'];
    $file_type = $_FILES['comment_image']['type'];
    
    if (!in_array($file_type, $allowed_types)) {
        $_SESSION['error'] = "Seuls les formats JPG et PNG sont autorisés";
        header("Location: product_detail.php?id=$product_id&city=$city");
        exit;
    }

    // Vérification de la taille
    if ($_FILES['comment_image']['size'] > 15000000) { // 15MB
        $_SESSION['error'] = "L'image est trop lourde (max 15MB)";
        header("Location: product_detail.php?id=$product_id&city=$city");
        exit;
    }

    // Génération d'un nom unique
    $extension = pathinfo($_FILES['comment_image']['name'], PATHINFO_EXTENSION);
    $filename = uniqid('comment_', true) . '.' . $extension;
    $target_path = $upload_dir . $filename;
    
    // Déplacement du fichier
    if (move_uploaded_file($_FILES['comment_image']['tmp_name'], $target_path)) {
        $image_path = $target_path;
    } else {
        $_SESSION['error'] = "Erreur lors de l'upload de l'image";
        header("Location: product_detail.php?id=$product_id&city=$city");
        exit;
    }
}

try {
    // Insertion du commentaire
    $stmt = $db->prepare("
        INSERT INTO product_comments 
        (user_id, product_id, city, comment, image_path, created_at) 
        VALUES (?, ?, ?, ?, ?, NOW())
    ");
    
    $stmt->execute([
        $user_id,
        $product_id,
        $city,
        $comment,
        $image_path
    ]);

    $_SESSION['success'] = "Votre commentaire a été ajouté avec succès";
    
} catch (PDOException $e) {
    error_log("Database Error: ".$e->getMessage());
    $_SESSION['error'] = "Une erreur est survenue lors de l'ajout du commentaire";
}

header("Location: product_detail.php?id=$product_id&city=$city");
exit;