<?php
require('check_auth.php');
require('connexion.php');
session_start();

// Validation des données
if (!isset($_GET['id'], $_GET['product_id'], $_GET['city'])) {
    $_SESSION['error'] = "Données manquantes";
    header("Location: products.php");
    exit;
}

$comment_id = (int)$_GET['id'];
$product_id = (int)$_GET['product_id'];
$city = $_GET['city'];
$user_id = $_SESSION['user_id'];

try {
    // Vérifier que l'utilisateur est bien l'auteur du commentaire
    $stmt = $db->prepare("SELECT user_id FROM product_comments WHERE id = ?");
    $stmt->execute([$comment_id]);
    $comment = $stmt->fetch();
    
    if (!$comment || $comment['user_id'] != $user_id)
    {
        $_SESSION['error'] = "Vous n'êtes pas autorisé à supprimer ce commentaire";
        header("Location: product_detail.php?id=$product_id&city=$city");
        exit;
    }
    
    // Supprimer le commentaire
    $stmt = $db->prepare("DELETE FROM product_comments WHERE id = ?");
    $stmt->execute([$comment_id]);
   
    $_SESSION['success'] = "Commentaire supprimé avec succès";
    header("Location: product_detail.php?id=$product_id&city=$city");
    exit;

} catch (PDOException $e) {
    error_log("Database Error: ".$e->getMessage());
    $_SESSION['error'] = "Une erreur est survenue lors de la suppression du commentaire";
    header("Location: product_detail.php?id=$product_id&city=$city");
    exit;
}