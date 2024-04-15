<?php
session_start();

$user = 'root';
$pass = 'root';

try {
    // Établir une connexion à la base de données
    $db = new PDO('mysql:host=localhost;dbname=oasys', $user, $pass);

    // Vérifier si l'ID de la personne physique à modifier a été passé en paramètre dans l'URL
    if (isset($_GET['id'])) {
        $id_personne = $_GET['id'];

        // Récupérer les informations actuelles de la personne physique à partir de la base de données
        $query = "SELECT p.*, u.email 
        FROM personne p
        INNER JOIN user u ON p.id_user = u.id
        WHERE p.id_personne = :id_personne";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':id_personne', $id_personne, PDO::PARAM_INT);
        $stmt->execute();
        $personne = $stmt->fetch(PDO::FETCH_ASSOC);

        // Vérifier si des données de formulaire ont été soumises pour la modification
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Récupérer les données du formulaire
            $nom = $_POST['nom'];
            $prenom = $_POST['prenom'];
            $email = $_POST['email'];
            $date_naissance = $_POST['datenaissance']; // Utilisez le nom du champ dans la base de données
            $pays = $_POST['pays']; // Champ pour le pays
            $telephone = $_POST['telephone']; // Champ pour le téléphone
            $profession = $_POST['profession']; // Champ pour la profession
            $nationalite = $_POST['nationalite']; // Champ pour la nationalité

            // Étape 2 : Exécuter la requête SQL pour mettre à jour les informations de la personne physique
            $updateQuery = "UPDATE personne SET nom = :nom, prenom = :prenom, date_naissance = :date_naissance, pays = :pays, telephone = :telephone, profession = :profession, nationalite = :nationalite WHERE id_personne = :id_personne";
            $updateStmt = $db->prepare($updateQuery);
            $updateStmt->bindParam(':id_personne', $id_personne, PDO::PARAM_INT);
            $updateStmt->bindParam(':nom', $nom, PDO::PARAM_STR);
            $updateStmt->bindParam(':prenom', $prenom, PDO::PARAM_STR);
            $updateStmt->bindParam(':date_naissance', $date_naissance, PDO::PARAM_STR); // Assurez-vous que le format correspond à celui de votre base de données (par exemple, 'YYYY-MM-DD')
            $updateStmt->bindParam(':pays', $pays, PDO::PARAM_STR);
            $updateStmt->bindParam(':telephone', $telephone, PDO::PARAM_STR);
            $updateStmt->bindParam(':profession', $profession, PDO::PARAM_STR);
            $updateStmt->bindParam(':nationalite', $nationalite, PDO::PARAM_STR);
            $updateStmt->execute();

            $updateEmailQuery = "UPDATE user SET email = :email WHERE id = :id_user";
            $updateEmailStmt = $db->prepare($updateEmailQuery);
            $updateEmailStmt->bindParam(':id_user', $personne['id_user'], PDO::PARAM_INT);
            $updateEmailStmt->bindParam(':email', $email, PDO::PARAM_STR);
            $updateEmailStmt->execute();

            $checkClientQuery = "SELECT id_personne FROM client WHERE id_personne = :id_personne";
            $checkClientStmt = $db->prepare($checkClientQuery);
            $checkClientStmt->bindParam(':id_personne', $id_personne, PDO::PARAM_INT);
            $checkClientStmt->execute();
        
            // Si aucun enregistrement n'existe dans la table `client`, alors effectuer l'insertion
            if ($checkClientStmt->rowCount() == 0) {
                $insertClientQuery = "INSERT INTO client (id_personne) VALUES (:id_personne)";
                $insertClientStmt = $db->prepare($insertClientQuery);
                $insertClientStmt->bindParam(':id_personne', $id_personne, PDO::PARAM_INT);
                $insertClientStmt->execute();
            }


            header('Location: http://localhost/oasys/indexconnecter.php');
            exit();
        }

        // Afficher le formulaire de modification avec les informations actuelles
        echo '<form method="POST">';
        echo '<input type="hidden" name="id_personne" value="' . $personne['id_personne'] . '">';
        echo '<label for="nom">Nom :</label>';
        echo '<input type="text" name="nom" value="' . $personne['nom'] . '"><br>';
        echo '<label for="prenom">Prénom :</label>';
        echo '<input type="text" name="prenom" value="' . $personne['prenom'] . '"><br>';
        echo '<label for="datenaissance">Date de naissance :</label>';
        echo '<input type="date" name="datenaissance" value="' . $personne['date_naissance'] . '"><br>';
        echo '<label for="pays">Pays :</label>';
        echo '<input type="text" name="pays" value="' . $personne['pays'] . '"><br>'; // Champ pour le pays
        echo '<label for="telephone">Téléphone :</label>';
        echo '<input type="text" name="telephone" value="' . $personne['telephone'] . '"><br>'; // Champ pour le téléphone
        echo '<label for="profession">Profession :</label>';
        echo '<input type="text" name="profession" value="' . $personne['profession'] . '"><br>'; // Champ pour la profession
        echo '<label for="nationalite">Nationalité :</label>';
        echo '<input type="text" name="nationalite" value="' . $personne['nationalite'] . '"><br>'; // Champ pour la nationalité
        echo '<label for="email">Email :</label>';
        echo '<input type="email" name="email" value="' . $personne['email'] . '"><br>'; // Champ pour l'adresse e-mail
        echo '<br>';
        echo '<input type="submit" value="Enregistrer les modifications">';
        echo '</form>';
    } else {
        echo "ID de personne manquant dans l'URL.";
    }
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}
?>
