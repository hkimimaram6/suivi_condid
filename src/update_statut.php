<?php
include 'db.php';

$id = $_POST['id'];
$statut = $_POST['statut'];

$sql = "UPDATE candidatures SET statut=? WHERE id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("si", $statut, $id);
$stmt->execute();

header("Location: dashboard_recruteur.php");
?>