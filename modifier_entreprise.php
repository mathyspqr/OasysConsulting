<?php
session_start();

$user = 'root';
$pass = 'root';

try {
    $db = new PDO('mysql:host=localhost;dbname=oasys', $user, $pass);

    if (isset($_GET['id'])) {
        $id_entreprise = $_GET['id'];

        $query = "SELECT e.*, u.email 
        FROM entreprise e
        INNER JOIN user u ON e.id_user = u.id
        WHERE e.id_entreprise = :id_entreprise";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':id_entreprise', $id_entreprise, PDO::PARAM_INT);
        $stmt->execute();
        $entreprise = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom_entreprise = $_POST['nom']; 
            $email = $_POST['email'];
            $siret = $_POST['siret'];
            $raisonsociale = $_POST['raisonsociale'];

            $updateQuery = "UPDATE entreprise SET nom = :nom_entreprise, siret = :siret, raisonsociale = :raisonsociale WHERE id_entreprise = :id_entreprise";
            $updateStmt = $db->prepare($updateQuery);
            $updateStmt->bindParam(':id_entreprise', $id_entreprise, PDO::PARAM_INT);
            $updateStmt->bindParam(':nom_entreprise', $nom_entreprise, PDO::PARAM_STR);
            $updateStmt->bindParam(':siret', $siret, PDO::PARAM_STR);
            $updateStmt->bindParam(':raisonsociale', $raisonsociale, PDO::PARAM_STR);
            $updateStmt->execute();

            $updateEmailQuery = "UPDATE user SET email = :email WHERE id = :id_user";
            $updateEmailStmt = $db->prepare($updateEmailQuery);
            $updateEmailStmt->bindParam(':id_user', $entreprise['id_user'], PDO::PARAM_INT);
            $updateEmailStmt->bindParam(':email', $email, PDO::PARAM_STR);
            $updateEmailStmt->execute();

        // Vérifier si un enregistrement existe dans la table `client` pour cette entreprise
        $checkClientQuery = "SELECT id_entreprise FROM client WHERE id_entreprise = :id_entreprise";
        $checkClientStmt = $db->prepare($checkClientQuery);
        $checkClientStmt->bindParam(':id_entreprise', $id_entreprise, PDO::PARAM_INT);
        $checkClientStmt->execute();

        // Si aucun enregistrement n'existe dans la table `client`, alors effectuer l'insertion
        if ($checkClientStmt->rowCount() == 0) {
            $insertClientQuery = "INSERT INTO client (id_entreprise) VALUES (:id_entreprise)";
            $insertClientStmt = $db->prepare($insertClientQuery);
            $insertClientStmt->bindParam(':id_entreprise', $id_entreprise, PDO::PARAM_INT);
            $insertClientStmt->execute();
        }

            header('Location: http://localhost/oasys/indexconnecter.php');
            exit();
        }

        // Afficher le formulaire de modification avec les informations actuelles
        echo '<form method="POST">';
        echo '<input type="hidden" name="id_entreprise" value="' . $entreprise['id_entreprise'] . '">';
        echo '<label for="nom">Nom de l\'entreprise :</label>';
        echo '<input type="text" name="nom" value="' . $entreprise['nom'] . '"><br>'; // Champ "nom" pour le nom de l'entreprise
        echo '<label for="siret">SIRET :</label>';
        echo '<input type="text" name="siret" value="' . $entreprise['siret'] . '"><br>'; // Champ "siret" pour le SIRET
        echo '<label for="raisonsociale">Raison Sociale :</label>';
        echo '<input type="text" name="raisonsociale" value="' . $entreprise['raisonsociale'] . '"><br>'; // Champ "raisonsociale" pour la raison sociale
        echo '<label for="email">Email :</label>';
        echo '<input type="email" name="email" value="' . $entreprise['email'] . '"><br>'; // Champ "email" pour l'adresse e-mail
        echo '<br>';
        echo '<input type="submit" value="Enregistrer les modifications">';
        echo '</form>';
    } else {
        echo "ID d'entreprise manquant dans l'URL.";
    }
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}
?>
