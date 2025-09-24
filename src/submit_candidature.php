<?php
session_start();
include 'db.php';

$id_utilisateur = $_SESSION['id'];
$poste = $_POST['poste'];

// Gérer le téléchargement du fichier
if (isset($_FILES['cv']) && $_FILES['cv']['error'] == 0) {
    $dossier = "uploads/";
    if (!is_dir($dossier)) {
        mkdir($dossier, 0777, true);
    }

    $fichier_tmp = $_FILES['cv']['tmp_name'];
    $nom_fichier = time() . '_' . basename($_FILES['cv']['name']);
    $chemin = $dossier . $nom_fichier;

    // Vérifier l'extension du fichier
    $extensions_autorisees = ['pdf', 'doc', 'docx'];
    $extension = strtolower(pathinfo($nom_fichier, PATHINFO_EXTENSION));

    if (in_array($extension, $extensions_autorisees)) {
        if (move_uploaded_file($fichier_tmp, $chemin)) {
            // Insérer dans la base de données
            $stmt = $conn->prepare("INSERT INTO candidatures (id_utilisateur, poste, cv) VALUES (?, ?, ?)");
            $stmt->bind_param("iss", $id_utilisateur, $poste, $chemin);
            $stmt->execute();

            header("Location: dashboard_candidat.php");
            exit();
        } else {
            echo "Erreur lors de l'upload du fichier.";
        }
    } else {
        echo "Type de fichier non autorisé. Seuls les PDF et Word sont acceptés.";
    }
} else {
    echo "Veuillez sélectionner un fichier valide.";
}
?>