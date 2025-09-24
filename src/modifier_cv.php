<?php
session_start();
include 'db.php';
$success=false; $error='';

if(isset($_POST['id']) && isset($_FILES['cv'])) {
    $id = $_POST['id'];
    $stmt = $conn->prepare("SELECT cv FROM candidatures WHERE id=?");
    $stmt->bind_param("i",$id);
    $stmt->execute();
    $ancien_cv = $stmt->get_result()->fetch_assoc()['cv'];

    if($ancien_cv && file_exists($ancien_cv)) unlink($ancien_cv);

    $upload_dir = "uploads/";
    if(!is_dir($upload_dir)) mkdir($upload_dir,0777,true);

    $nom_fichier = time().'_'.basename($_FILES['cv']['name']);
    $chemin = $upload_dir.$nom_fichier;
    if(move_uploaded_file($_FILES['cv']['tmp_name'],$chemin)){
        $stmt = $conn->prepare("UPDATE candidatures SET cv=? WHERE id=?");
        $stmt->bind_param("si",$chemin,$id);
        $stmt->execute();
        $success=true;
    } else $error="Échec de l'envoi du fichier.";
} else $error="Informations incomplètes.";
?>
<!DOCTYPE html>
<html lang="fr">
<head><meta charset="UTF-8"><title>Modifier le CV</title></head>
<body>
<?php if($success): ?>
<div style="color:green;">✅ CV modifié avec succès</div>
<?php else: ?>
<div style="color:red;">❌ <?= htmlspecialchars($error) ?></div>
<?php endif; ?>
<a href="dashboard_candidat.php">⬅ Retour</a>
</body>
</html>
