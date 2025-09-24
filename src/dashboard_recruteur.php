<?php
session_start();
include 'db.php';

$stmt = $conn->prepare("SELECT c.*, u.nom, u.email FROM candidatures c JOIN utilisateurs u ON c.id_utilisateur = u.id");
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8" />
<title>Liste des candidatures</title>
<style>
/* ton CSS existant */
</style>
</head>
<body>

<h2>Liste des candidatures</h2>

<?php while ($row = $result->fetch_assoc()) { ?>
<div class="candidature">
<p><strong><?= htmlspecialchars($row['nom']) ?></strong> - <?= htmlspecialchars($row['poste']) ?> - Statut : <strong><?= htmlspecialchars($row['statut']) ?></strong></p>
<?php if (!empty($row['cv'])): ?>
<p>📎 CV : <a href="<?= htmlspecialchars($row['cv']) ?>" target="_blank" rel="noopener noreferrer">Voir / Télécharger</a></p>
<?php else: ?>
<p>📎 Aucun CV fourni</p>
<?php endif; ?>

<form action="update_statut.php" method="POST">
<input type="hidden" name="id" value="<?= $row['id'] ?>">
<select name="statut">
<option <?= $row['statut']=='En attente'?'selected':'' ?>>En attente</option>
<option <?= $row['statut']=='En cours'?'selected':'' ?>>En cours</option>
<option <?= $row['statut']=='Acceptée'?'selected':'' ?>>Acceptée</option>
<option <?= $row['statut']=='Refusée'?'selected':'' ?>>Refusée</option>
</select>
<button type="submit">Mettre à jour</button>
</form>

<form action="supprimer_candidature.php" method="POST" onsubmit="return confirm('Confirmer la suppression ?');">
<input type="hidden" name="id" value="<?= $row['id'] ?>">
<button type="submit" class="btn-supprimer">🗑 Supprimer</button>
</form>
</div>
<?php } ?>

</body>
</html>
