<?php
session_start();
include 'db.php'; // Fichier de connexion à la base

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $mot_de_passe = isset($_POST['mot_de_passe']) ? $_POST['mot_de_passe'] : '';
    $role = isset($_POST['type']) ? $_POST['type'] : ''; // 'type' vient du formulaire HTML

    if (!empty($email) && !empty($mot_de_passe) && !empty($role)) {
        $sql = "SELECT * FROM utilisateur WHERE email=? AND role=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $email, $role);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user) {
            if (password_verify($mot_de_passe, $user['mot_de_passe'])) {
                $_SESSION['id'] = $user['id'];
                $_SESSION['role'] = $user['role'];

                // Redirection selon le rôle
                if ($role == "recruteur") {
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
            echo "Utilisateur non trouvé ou rôle incorrect.";
        }
    } else {
        echo "Veuillez remplir tous les champs du formulaire.";
    }
} else {
    echo "Formulaire non soumis.";
}
?>
