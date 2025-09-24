<?php 
session_start();
include 'db.php';

$result = $conn->query("SELECT c.*, u.nom, u.email FROM candidatures c JOIN utilisateurs u ON c.id_utilisateur = u.id");
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <title>Liste des candidatures</title>
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

    h2 {
      margin-bottom: 20px;
      color: #333;
    }

    .candidature {
      width: 100%;
      max-width: 600px;
      background-color: white;
      border-radius: 8px;
      padding: 20px;
      margin-bottom: 15px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    .candidature p {
      margin: 8px 0;
      font-size: 16px;
      color: #444;
    }

    .candidature strong {
      color: #222;
    }

    a {
      color: #007bff;
      text-decoration: none;
      font-weight: bold;
      transition: color 0.3s;
    }

    a:hover {
      color: #0056b3;
    }

    form {
      display: inline-block;
      margin-right: 10px;
    }

    select {
      padding: 5px 8px;
      border-radius: 4px;
      border: 1px solid #ccc;
      font-size: 14px;
    }

    button {
      padding: 6px 12px;
      border: none;
      border-radius: 4px;
      background-color: #007bff;
      color: white;
      font-weight: bold;
      cursor: pointer;
      transition: background-color 0.3s;
      font-size: 14px;
    }

    button:hover {
      background-color: #0056b3;
    }

    .btn-supprimer {
      background-color: transparent;
      color: #dc3545;
      font-weight: bold;
      padding: 0 6px;
    }

    .btn-supprimer:hover {
      color: #a71d2a;
      background-color: transparent;
    }
  </style>
</head>
<body>

  <h2>Liste des candidatures</h2>

  <?php while ($row = $result->fetch_assoc()) { ?>
    <div class="candidature">
      <p>
        <strong><?= htmlspecialchars($row['nom']) ?></strong> - 
        <?= htmlspecialchars($row['poste']) ?> - 
        Statut : <strong><?= htmlspecialchars($row['statut']) ?></strong>
      </p>

      <?php if (!empty($row['cv'])): ?>
        <p>
          📎 CV :
          <a href="<?= htmlspecialchars($row['cv']) ?>" target="_blank" rel="noopener noreferrer">Voir / Télécharger</a>
        </p>
      <?php else: ?>
        <p>📎 Aucun CV fourni</p>
      <?php endif; ?>

      <form action="update_statut.php" method="POST">
        <input type="hidden" name="id" value="<?= $row['id'] ?>">
        <select name="statut">
          <option <?= $row['statut'] == 'En attente' ? 'selected' : '' ?>>En attente</option>
          <option <?= $row['statut'] == 'En cours' ? 'selected' : '' ?>>En cours</option>
          <option <?= $row['statut'] == 'Acceptée' ? 'selected' : '' ?>>Acceptée</option>
          <option <?= $row['statut'] == 'Refusée' ? 'selected' : '' ?>>Refusée</option>
        </select>
        <button type="submit">Mettre à jour</button>
      </form>

      <form action="supprimer_candidature.php" method="POST" onsubmit="return confirm('Confirmer la suppression ?');">
        <input type="hidden" name="id" value="<?= $row['id'] ?>">
        <button type="submit" class="btn-supprimer" title="Supprimer la candidature">🗑 Supprimer</button>
      </form>
    </div>
  <?php } ?>

</body>
</html>