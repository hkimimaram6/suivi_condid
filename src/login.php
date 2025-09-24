<?php
session_start();
include 'db.php'; // Fichier de connexion à la base

// Vérifier si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les données en s'assurant qu'elles existent
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $mot_de_passe = isset($_POST['mot_de_passe']) ? $_POST['mot_de_passe'] : '';
    $type = isset($_POST['type']) ? $_POST['type'] : '';

    // Vérifier que tous les champs sont remplis
    if (!empty($email) && !empty($mot_de_passe) && !empty($type)) {
        $sql = "SELECT * FROM utilisateurs WHERE email=? AND type=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $email, $type);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user) {
            // Vérifier le mot de passe
            if (password_verify($mot_de_passe, $user['mot_de_passe'])) {
                $_SESSION['id'] = $user['id'];
                $_SESSION['type'] = $user['type'];

                // Rediriger selon le type
                if ($type == "recruteur") {
                    header("Location: dashboard_recruteur.php");
                    exit();
                } else {
                    header("Location: dashboard_candidat.php");
                    exit();
                }
            } else {
                echo "Mot de passe incorrect.";
            }
        } else {
            echo "Utilisateur non trouvé ou type incorrect.";
        }
    } else {
        echo "Veuillez remplir tous les champs du formulaire.";
    }
} else {
    echo "Formulaire non soumis.";
}
?>
