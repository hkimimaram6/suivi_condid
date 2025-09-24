<?php
session_start();
include 'db.php';
$id = isset($_SESSION['id']) ? $_SESSION['id'] : 0;

$stmt = $conn->prepare("SELECT * FROM candidatures WHERE id_utilisateur = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Vos candidatures</title>
<style>
/* ton CSS existant ici */
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
        <form action="modifier_cv.php" method="POST" enctype="multipart/form-data">
          <input type="hidden" name="id" value="<?= $row['id'] ?>">
          <input type="file" name="cv" accept=".pdf,.doc,.docx" required>
          <button type="submit">🔄 Modifier le CV</button>
        </form>
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
