<?php
session_start();

$user = 'root';
$pass = 'root';

try {
    // Établir une connexion à la base de données
    $db = new PDO('mysql:host=localhost;dbname=oasys', $user, $pass);

    // Vérifier si l'ID du salarié à modifier a été passé en paramètre dans l'URL
    if (isset($_GET['id'])) {
        $id_salarie = $_GET['id'];

        // Récupérer les informations actuelles du salarié à partir de la base de données
        $query = "SELECT s.*, u.email 
        FROM salarie s
        INNER JOIN user u ON s.id_user = u.id
        WHERE s.id_salarie = :id_salarie";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':id_salarie', $id_salarie, PDO::PARAM_INT);
        $stmt->execute();
        $salarie = $stmt->fetch(PDO::FETCH_ASSOC);

        // Vérifier si des données de formulaire ont été soumises pour la modification
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Récupérer les données du formulaire
            $nom = $_POST['nom'];
            $prenom = $_POST['prenom'];
            $email = $_POST['email'];
            $date_naissance = $_POST['datenaissance']; // Utilisez le nom du champ dans la base de données

            // Étape 2 : Exécuter la requête SQL pour mettre à jour les informations du salarié
            $updateQuery = "UPDATE salarie SET nom = :nom, prenom = :prenom, datenaissance = :datenaissance WHERE id_salarie = :id_salarie";
            $updateStmt = $db->prepare($updateQuery);
            $updateStmt->bindParam(':id_salarie', $id_salarie, PDO::PARAM_INT);
            $updateStmt->bindParam(':nom', $nom, PDO::PARAM_STR);
            $updateStmt->bindParam(':prenom', $prenom, PDO::PARAM_STR);
            $updateStmt->bindParam(':datenaissance', $date_naissance, PDO::PARAM_STR); // Assurez-vous que le format correspond à celui de votre base de données (par exemple, 'YYYY-MM-DD')
            $updateStmt->execute();

            $updateEmailQuery = "UPDATE user SET email = :email WHERE id = :id_user";
            $updateEmailStmt = $db->prepare($updateEmailQuery);
            $updateEmailStmt->bindParam(':id_user', $salarie['id_user'], PDO::PARAM_INT);
            $updateEmailStmt->bindParam(':email', $email, PDO::PARAM_STR);
            $updateEmailStmt->execute();

            header('Location: http://localhost/oasys/indexconnecter.php');
            exit();
        }

        // Afficher le formulaire de modification avec les informations actuelles
        echo '<form method="POST">';
        echo '<input type="hidden" name="id_salarie" value="' . $salarie['id_salarie'] . '">';
        echo '<label for="nom">Nom :</label>';
        echo '<input type="text" name="nom" value="' . $salarie['nom'] . '"><br>';
        echo '<label for="prenom">Prénom :</label>';
        echo '<input type="text" name="prenom" value="' . $salarie['prenom'] . '"><br>';
        echo '<label for="datenaissance">Date de naissance :</label>';
        echo '<input type="date" name="datenaissance" value="' . $salarie['datenaissance'] . '"><br>'; // Champ pour la date de naissance
        echo '<label for="email">Email :</label>';
        echo '<input type="email" name="email" value="' . $salarie['email'] . '"><br>'; // Champ pour l'adresse e-mail
        // Ajoutez d'autres champs pour les informations supplémentaires à modifier
        echo '<br>';
        echo '<input type="submit" value="Enregistrer les modifications">';
        echo '</form>';
    } else {
        echo "ID de salarié manquant dans l'URL.";
    }
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}
?>
