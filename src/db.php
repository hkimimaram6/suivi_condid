<?php
$servername = "localhost";
$username = "root";
$password = ""; // par défaut vide sur XAMPP
$dbname = "suivi_condid"; // nom de la base de données changé

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connexion échouée: " . $conn->connect_error);
}
?>
