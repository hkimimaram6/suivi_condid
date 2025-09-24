<?php
include 'db.php';

if ($conn) {
    echo "Connexion à la base réussie !";
} else {
    echo "Erreur de connexion : " . $conn->connect_error;
}
?>
