<?php
session_start();
include 'db.php';

$success = false;
$error = '';

if (isset($_POST['id']) && isset($_FILES['cv'])) {
    $id = $_POST['id'];

    // Rechercher l'ancien fichier
    $stmt = $conn->prepare("SELECT cv FROM candidatures WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $ancien_cv = $result->fetch_assoc()['cv'];

    // Supprimer l'ancien fichier du serveur
    if ($ancien_cv && file_exists($ancien_cv)) {
        unlink($ancien_cv);
    }

    // Enregistrer le nouveau fichier
    $upload_dir = "uploads/";
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    $nom_fichier = time() . '_' . basename($_FILES['cv']['name']);
    $chemin = $upload_dir . $nom_fichier;
    $tmp = $_FILES['cv']['tmp_name'];

    if (move_uploaded_file($tmp, $chemin)) {
        // Mettre à jour la base
        $stmt = $conn->prepare("UPDATE candidatures SET cv = ? WHERE id = ?");
        $stmt->bind_param("si", $chemin, $id);
        $stmt->execute();
        $success = true;
    } else {
        $error = "Échec de l'envoi du fichier.";
    }
} else {
    $error = "Informations incomplètes.";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Modifier le CV</title>
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

<?php if ($success): ?>
  <div class="message success">✅ Votre CV a été modifié avec succès.</div>
<?php else: ?>
  <div class="message error">❌ Une erreur est survenue : <?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<a href="dashboard_candidat.php">⬅ Retour au tableau de bord</a>

</body>
</html>