<?php
session_start();
require_once('connexion.php');

if (!isset($_SESSION['user_id']) || !isset($_POST['recipe_id']) || empty($_POST['comment'])) {
    $_SESSION['error_msg'] = "Données incomplètes.";
    header("Location: recipe_detail.php?id=" . ($_POST['recipe_id'] ?? 0));
    exit();
}

$recipe_id = (int)$_POST['recipe_id'];
$user_id   = $_SESSION['user_id'];
$comment   = trim($_POST['comment']);
$image_path = null;

// Gestion de l'image si elle est envoyée
if (
    isset($_FILES['comment_image']) &&
    $_FILES['comment_image']['error'] === UPLOAD_ERR_OK &&
    !empty($_FILES['comment_image']['name'])
) {
    $upload_dir = 'uploads/comments_recipes/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }

    $filename = basename($_FILES['comment_image']['name']);
    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    $allowed_exts = ['jpg', 'jpeg', 'png', 'gif'];

    if (in_array($ext, $allowed_exts)) {
        $new_name = uniqid("comment_", true) . '.' . $ext;
        $target_path = $upload_dir . $new_name;
        if (move_uploaded_file($_FILES['comment_image']['tmp_name'], $target_path)) {
            $image_path = $target_path;
        } else {
            $_SESSION['error_msg'] = "Erreur lors de l'upload de l'image.";
            header("Location: recipe_detail.php?id=" . $recipe_id);
            exit();
        }
    } else {
        $_SESSION['error_msg'] = "Format de fichier non autorisé.";
        header("Location: recipe_detail.php?id=" . $recipe_id);
        exit();
    }
}

// Insertion en base de données
try {
    $stmt = $db->prepare("INSERT INTO recipe_comments (recipe_id, user_id, comment,image_path , created_at) VALUES (?, ?, ?, ?, NOW())");
    $success = $stmt->execute([$recipe_id, $user_id, $comment, $image_path]);

    if ($success) {
        $_SESSION['success_msg'] = "Commentaire ajouté avec succès.";
    } else {
        // Affiche l'erreur PDO pour debug
        $errorInfo = $stmt->errorInfo();
        $_SESSION['error_msg'] = "Erreur lors de l'ajout du commentaire : " . $errorInfo[2];
    }
} catch (PDOException $e) {
    $_SESSION['error_msg'] = "Erreur SQL : " . $e->getMessage();
}

header("Location: recipe_detail.php?id=" . $recipe_id);
exit();
