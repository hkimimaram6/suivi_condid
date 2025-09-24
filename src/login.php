<?php
session_start();
include 'db.php'; // Fichier de connexion à la base

$email = $_POST['email'];
$mot_de_passe = $_POST['mot_de_passe'];
$type = $_POST['type'];

$sql = "SELECT * FROM utilisateurs WHERE email=? AND type=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $email, $type);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($user && password_verify($mot_de_passe, $user['mot_de_passe'])) {
    $_SESSION['id'] = $user['id'];
    $_SESSION['type'] = $user['type'];
    
    if ($type == "recruteur") {
        header("Location: dashboard_recruteur.php");
        exit();
    } else {
        header("Location: dashboard_candidat.php");
        exit();
    }
} else {
    echo "Identifiants incorrects.";
}
?>