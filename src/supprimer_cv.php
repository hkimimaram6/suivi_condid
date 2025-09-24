<?php
session_start();
include 'db.php';

$message = '';
$messageClass = '';

if (isset($_POST['id'])) {
    $id = $_POST['id'];

    // Récupérer le chemin du CV
    $stmt = $conn->prepare("SELECT cv FROM candidatures WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $cv = $result->fetch_assoc()['cv'];

        // Supprimer le fichier du serveur
        if ($cv && file_exists($cv)) {
            if (unlink($cv)) {
                // Supprimer le champ CV de la base
                $stmt = $conn->prepare("UPDATE candidatures SET cv = NULL WHERE id = ?");
                $stmt->bind_param("i", $id);
                if ($stmt->execute()) {
                    $message = "Le CV a bien été supprimé.";
                    $messageClass = "success";
                } else {
                    $message = "Erreur lors de la mise à jour en base de données.";
                    $messageClass = "error";
                }
            } else {
                $message = "Impossible de supprimer le fichier CV sur le serveur.";
                $messageClass = "error";
            }
        } else {
            $message = "Le fichier CV n'existe pas.";
            $messageClass = "error";
        }
    } else {
        $message = "Aucune candidature trouvée avec cet ID.";
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
    <title>Suppression du CV</title>
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
    <div class="message <?php echo $messageClass; ?>">
        <?php echo htmlspecialchars($message); ?>
    </div>
    <a href="dashboard_candidat.php">Retour au tableau de bord</a>
</body>
</html>