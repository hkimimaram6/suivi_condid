<?php
session_start();
include 'db.php';

$message = '';
$messageClass = '';

if (isset($_POST['id'])) {
    $id = $_POST['id'];

    // Optionnel : Supprimer aussi le fichier CV
    $stmt = $conn->prepare("SELECT cv FROM candidatures WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $fichier_cv = $row['cv'];
        if ($fichier_cv && file_exists($fichier_cv)) {
            if (unlink($fichier_cv)) {
                // fichier supprimé avec succès
            } else {
                $message = "Erreur lors de la suppression du fichier CV sur le serveur.";
                $messageClass = "error";
            }
        }

        // Supprimer la candidature de la base
        $stmtDel = $conn->prepare("DELETE FROM candidatures WHERE id = ?");
        $stmtDel->bind_param("i", $id);
        if ($stmtDel->execute()) {
            if (empty($message)) {
                $message = "La candidature a bien été supprimée.";
                $messageClass = "success";
            }
        } else {
            $message = "Erreur lors de la suppression de la candidature en base.";
            $messageClass = "error";
        }
    } else {
        $message = "Candidature introuvable.";
        $messageClass = "error";
    }
} else {
    $message = "ID non spécifié.";
    $messageClass = "error";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <title>Suppression de candidature</title>
    <style>
        body {
          font-family: Arial, sans-serif;
          background-color: #f4f6f9;
          margin: 0;
          padding: 40px;
          display: flex;
          flex-direction: column;
          align-items: center;
        }

        .message {
          width: 100%;
          max-width: 600px;
          padding: 20px;
          border-radius: 8px;
          text-align: center;
          font-size: 18px;
          box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .success {
          background-color: #d4edda;
          color: #155724;
          border: 1px solid #c3e6cb;
        }

        .error {
          background-color: #f8d7da;
          color: #721c24;
          border: 1px solid #f5c6cb;
        }

        a {
          margin-top: 25px;
          display: inline-block;
          text-decoration: none;
          color: #007bff;
          font-weight: bold;
          transition: color 0.3s;
        }

        a:hover {
          color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="message <?= $messageClass ?>">
        <?= htmlspecialchars($message) ?>
    </div>
    <a href="dashboard_recruteur.php">Retour au tableau de bord</a>
</body>
</html>