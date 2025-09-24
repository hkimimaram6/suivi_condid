<?php
include 'db.php';
$id = isset($_POST['id']) ? $_POST['id'] : 0;
$statut = isset($_POST['statut']) ? $_POST['statut'] : '';
$stmt = $conn->prepare("UPDATE candidatures SET statut=? WHERE id=?");
$stmt->bind_param("si",$statut,$id);
$stmt->execute();
header("Location: dashboard_recruteur.php");
?>
