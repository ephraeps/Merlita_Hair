<?php
require_once('connexion.php');
require_once('check_auth.php');

// Vérifier que l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error_msg'] = "Vous devez être connecté pour effectuer cette action.";
    header("Location: login.php");
    exit();
}

// Vérifier que les paramètres sont valides
if (!isset($_GET['id']) || !is_numeric($_GET['id']) || !isset($_GET['recipe_id']) || !is_numeric($_GET['recipe_id'])) {
    $_SESSION['error_msg'] = "Paramètres invalides.";
    header("Location: recipes.php");
    exit(); 
}
$comment_id = (int)$_GET['id'];
$recipe_id = (int)$_GET['recipe_id'];

// Récupérer le commentaire pour vérifier que l'utilisateur est bien l'auteur
$stmt = $db->prepare("SELECT * FROM recipe_comments WHERE id = ? AND user_id = ?");
$stmt->execute([$comment_id, $_SESSION['user_id']]);
$comment = $stmt->fetch();

if (!$comment) {
    $_SESSION['error_msg'] = "Commentaire introuvable ou vous n'êtes pas autorisé à le supprimer.";
    header("Location: recipe_detail.php?id=" . $recipe_id);
    exit();
}

// Supprimer l'image associée si elle existe
if (!empty($comment['image_path']) && file_exists($comment['image_path'])) {
    unlink($comment['image_path']);
}

// Supprimer le commentaire de la base de données
try {
    $stmt = $db->prepare("DELETE FROM recipe_comments WHERE id = ?");
    $stmt->execute([$comment_id]);
    
    $_SESSION['success_msg'] = "Commentaire supprimé avec succès.";
} catch (PDOException $e) {
    $_SESSION['error_msg'] = "Une erreur s'est produite lors de la suppression du commentaire.";
    error_log("Erreur suppression commentaire: " . $e->getMessage());
}

// Rediriger vers la page de la recette
header("Location: recipe_detail.php?id=" . $recipe_id);
exit();
