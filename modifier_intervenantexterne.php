<h1>Intervenant Externe</h1>
<?php
session_start();

$user = 'root';
$pass = 'root';

try {
    $db = new PDO('mysql:host=localhost;dbname=oasys', $user, $pass);

    // Vérifier si l'ID de l'intervenant externe à modifier a été passé en paramètre dans l'URL
    if (isset($_GET['id'])) {
        $id_intervenant_externe = $_GET['id'];

        // Récupérer les informations actuelles de l'intervenant externe à partir de la base de données
        $query = "SELECT ie.*, u.email 
        FROM intervenant_externe ie
        INNER JOIN user u ON ie.id_user = u.id
        WHERE ie.id_intervenantexterne = :id_intervenant_externe";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':id_intervenant_externe', $id_intervenant_externe, PDO::PARAM_INT);
        $stmt->execute();
        $intervenant_externe = $stmt->fetch(PDO::FETCH_ASSOC);

        // Vérifier si des données de formulaire ont été soumises pour la modification
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Récupérer les données du formulaire
            $numero_siret = $_POST['numero_siret'];
            $societe = $_POST['societe'];
            $nom = $_POST['nom'];
            $prenom = $_POST['prenom'];
            $email = $_POST['email'];

            // Étape 2 : Exécuter la requête SQL pour mettre à jour les informations de l'intervenant externe
            $updateQuery = "UPDATE intervenant_externe SET numero_siret = :numero_siret, societe = :societe, nom = :nom, prenom = :prenom WHERE id_intervenantexterne = :id_intervenant_externe";
            $updateStmt = $db->prepare($updateQuery);
            $updateStmt->bindParam(':id_intervenant_externe', $id_intervenant_externe, PDO::PARAM_INT);
            $updateStmt->bindParam(':numero_siret', $numero_siret, PDO::PARAM_STR);
            $updateStmt->bindParam(':societe', $societe, PDO::PARAM_STR);
            $updateStmt->bindParam(':nom', $nom, PDO::PARAM_STR);
            $updateStmt->bindParam(':prenom', $prenom, PDO::PARAM_STR);
            $updateStmt->execute();

            // Mettre à jour l'adresse e-mail dans la table 'user' si nécessaire
            $updateEmailQuery = "UPDATE user SET email = :email WHERE id = :id_user";
            $updateEmailStmt = $db->prepare($updateEmailQuery);
            $updateEmailStmt->bindParam(':id_user', $intervenant_externe['id_user'], PDO::PARAM_INT);
            $updateEmailStmt->bindParam(':email', $email, PDO::PARAM_STR);
            $updateEmailStmt->execute();

            header('Location: http://localhost/oasys/indexconnecter.php');
            exit();
        }

        // Afficher le formulaire de modification avec les informations actuelles
        echo '<form method="POST">';
        echo '<input type="hidden" name="id_intervenant_externe" value="' . $intervenant_externe['id_intervenantexterne'] . '">';
        echo '<label for="numero_siret">Numéro SIRET :</label>';
        echo '<input type="text" name="numero_siret" value="' . $intervenant_externe['numero_siret'] . '"><br>';
        echo '<label for="societe">Société :</label>';
        echo '<input type="text" name="societe" value="' . $intervenant_externe['societe'] . '"><br>';
        echo '<label for="nom">Nom :</label>';
        echo '<input type="text" name="nom" value="' . $intervenant_externe['nom'] . '"><br>';
        echo '<label for="prenom">Prénom :</label>';
        echo '<input type="text" name="prenom" value="' . $intervenant_externe['prenom'] . '"><br>';
        echo '<label for="email">Email :</label>';
        echo '<input type="email" name="email" value="' . $intervenant_externe['email'] . '"><br>';
        // Ajoutez d'autres champs pour les informations supplémentaires à modifier
        echo '<br>';
        echo '<input type="submit" value="Enregistrer les modifications">';
        echo '</form>';
    } else {
        echo "ID de l'intervenant externe manquant dans l'URL.";
    }
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}
?>
