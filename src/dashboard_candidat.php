<?php
session_start();
include 'db.php';
$id = $_SESSION['id'];

// Récupérer les candidatures existantes
$result = $conn->query("SELECT * FROM candidatures WHERE id_utilisateur=$id");
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Vos candidatures</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f0f2f5;
      padding: 20px;
    }

    h2, h3 {
      text-align: center;
      color: #333;
    }

    ul {
      list-style-type: none;
      padding: 0;
      max-width: 700px;
      margin: 0 auto 30px;
    }

    li {
      background-color: #fff;
      border-radius: 8px;
      padding: 15px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
      margin-bottom: 20px;
    }

    form {
      display: block;
      margin-top: 10px;
    }

    form[enctype] {
      background-color: #fff;
      padding: 20px;
      max-width: 700px;
      margin: 0 auto;
      border-radius: 8px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }

    label, select, input[type="file"] {
      display: block;
      margin-top: 10px;
      margin-bottom: 10px;
      width: 100%;
    }

    select, input[type="file"] {
      padding: 8px;
      border-radius: 5px;
      border: 1px solid #ccc;
    }

    button {
      background-color: #007BFF;
      color: white;
      border: none;
      padding: 10px 15px;
      border-radius: 5px;
      cursor: pointer;
      margin-top: 10px;
    }

    button:hover {
      background-color: #0056b3;
    }

    a {
      text-decoration: none;
      color: #007BFF;
    }

    a:hover {
      text-decoration: underline;
    }

    .action-buttons {
      display: flex;
      flex-wrap: wrap;
      gap: 10px;
      margin-top: 10px;
    }

    .action-buttons form {
      display: inline;
    }
  </style>
</head>
<body>

<h2>Vos candidatures</h2>
<ul>
<?php while ($row = $result->fetch_assoc()) { ?>
  <li>
    <strong>Poste :</strong> <?= htmlspecialchars($row['poste']) ?><br>
    <strong>Statut :</strong> <?= htmlspecialchars($row['statut']) ?><br>

    <?php if (!empty($row['cv'])): ?>
      📎 <a href="<?= htmlspecialchars($row['cv']) ?>" target="_blank">Voir CV</a>

      <div class="action-buttons">
        <!-- Modifier le CV -->
        <form action="modifier_cv.php" method="POST" enctype="multipart/form-data">
          <input type="hidden" name="id" value="<?= $row['id'] ?>">
          <input type="file" name="cv" accept=".pdf,.doc,.docx" required>
          <button type="submit">🔄 Modifier le CV</button>
        </form>

        <!-- Supprimer le CV -->
        <form action="supprimer_cv.php" method="POST" onsubmit="return confirm('Supprimer ce CV ?');">
          <input type="hidden" name="id" value="<?= $row['id'] ?>">
          <button type="submit">🗑 Supprimer le CV</button>
        </form>
      </div>
    <?php else: ?>
      <em>Aucun CV ajouté</em>
    <?php endif; ?>
  </li>
<?php } ?>
</ul>

<h3>Soumettre une nouvelle candidature</h3>
<form action="submit_candidature.php" method="POST" enctype="multipart/form-data">
  <label for="poste">Poste visé :</label>
  <select name="poste" id="poste" required>
    <option value="">-- Sélectionner un métier --</option>
    <option value="Développeur Web">Développeur Web</option>
    <option value="Data Scientist">Data Scientist</option>
    <option value="Chef de projet">Chef de projet</option>
    <option value="Administrateur système">Administrateur système</option>
    <option value="Technicien support">Technicien support</option>
    <option value="Ingénieur DevOps">Ingénieur DevOps</option>
    <option value="UX/UI Designer">UX/UI Designer</option>
    <option value="Analyste métier">Analyste métier</option>
    <option value="Testeur QA">Testeur QA</option>
    <option value="Architecte logiciel">Architecte logiciel</option>
  </select>

  <label for="cv">Votre CV (PDF ou Word) :</label>
  <input type="file" name="cv" id="cv" accept=".pdf,.doc,.docx" required>

  <button type="submit">Soumettre</button>
</form>

</body>
</html>