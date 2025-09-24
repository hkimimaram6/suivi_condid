<?php
session_start();
include 'db.php';

$id_utilisateur = isset($_SESSION['id']) ? $_SESSION['id'] : 0;
$poste = isset($_POST['poste']) ? $_POST['poste'] : '';

if ($id_utilisateur && $poste && isset($_FILES['cv']) && $_FILES['cv']['error']==0) {
    $dossier = "uploads/";
    if (!is_dir($dossier)) mkdir($dossier, 0777, true);

    $fichier_tmp = $_FILES['cv']['tmp_name'];
    $nom_fichier = time().'_'.basename($_FILES['cv']['name']);
    $chemin = $dossier.$nom_fichier;
    $extension = strtolower(pathinfo($nom_fichier, PATHINFO_EXTENSION));
    $extensions_autorisees = ['pdf','doc','docx'];

    if (in_array($extension,$extensions_autorisees)) {
        if (move_uploaded_file($fichier_tmp,$chemin)) {
            $stmt = $conn->prepare("INSERT INTO candidatures (id_utilisateur, poste, cv) VALUES (?,?,?)");
            $stmt->bind_param("iss",$id_utilisateur,$poste,$chemin);
            $stmt->execute();
            header("Location: dashboard_candidat.php"); exit();
        } else echo "Erreur lors de l'upload du fichier.";
    } else echo "Type de fichier non autorisé.";
} else echo "Veuillez remplir tous les champs et choisir un fichier.";
?>
