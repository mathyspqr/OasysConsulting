<?php
session_start();

$user = 'root';
$pass = 'root';

try {
    $db = new PDO('mysql:host=localhost;dbname=oasys', $user, $pass);

    if (isset($_GET['id'])) {
        $user_id = $_GET['id'];

        $checkQuerySalarie = "SELECT * FROM salarie WHERE id_user = :id_user";
        $checkStmtSalarie = $db->prepare($checkQuerySalarie);
        $checkStmtSalarie->bindParam(':id_user', $user_id, PDO::PARAM_INT);
        $checkStmtSalarie->execute();
        $isSalarie = $checkStmtSalarie->fetch(PDO::FETCH_ASSOC);

        $checkQueryIntervenant = "SELECT * FROM intervenant_externe WHERE id_user = :id_user";
        $checkStmtIntervenant = $db->prepare($checkQueryIntervenant);
        $checkStmtIntervenant->bindParam(':id_user', $user_id, PDO::PARAM_INT);
        $checkStmtIntervenant->execute();
        $isIntervenantExterne = $checkStmtIntervenant->fetch(PDO::FETCH_ASSOC);

        $checkQueryPersonne = "SELECT * FROM personne WHERE id_user = :id_user";
        $checkStmtPersonne = $db->prepare($checkQueryPersonne);
        $checkStmtPersonne->bindParam(':id_user', $user_id, PDO::PARAM_INT);
        $checkStmtPersonne->execute();
        $isPersonne = $checkStmtPersonne->fetch(PDO::FETCH_ASSOC);

        $checkQueryEntreprise = "SELECT * FROM entreprise WHERE id_user = :id_user";
        $checkStmtEntreprise = $db->prepare($checkQueryEntreprise);
        $checkStmtEntreprise->bindParam(':id_user', $user_id, PDO::PARAM_INT);
        $checkStmtEntreprise->execute();
        $isEntreprise = $checkStmtEntreprise->fetch(PDO::FETCH_ASSOC);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['salarie'])) {
                if (!$isSalarie) {
                    $insertQuerySalarie = "INSERT INTO salarie (id_user) VALUES (:id_user)";
                    $insertStmtSalarie = $db->prepare($insertQuerySalarie);
                    $insertStmtSalarie->bindParam(':id_user', $user_id, PDO::PARAM_INT);
                    $insertStmtSalarie->execute();
                }
            } elseif ($isSalarie) {
                $deleteQuerySalarie = "DELETE FROM salarie WHERE id_user = :id_user";
                $deleteStmtSalarie = $db->prepare($deleteQuerySalarie);
                $deleteStmtSalarie->bindParam(':id_user', $user_id, PDO::PARAM_INT);
                $deleteStmtSalarie->execute();
            }

            if (isset($_POST['intervenant_externe'])) {
                if (!$isIntervenantExterne) {
                    $insertQueryIntervenant = "INSERT INTO intervenant_externe (id_user) VALUES (:id_user)";
                    $insertStmtIntervenant = $db->prepare($insertQueryIntervenant);
                    $insertStmtIntervenant->bindParam(':id_user', $user_id, PDO::PARAM_INT);
                    $insertStmtIntervenant->execute();
                }
            } elseif ($isIntervenantExterne) {
                $deleteQueryIntervenant = "DELETE FROM intervenant_externe WHERE id_user = :id_user";
                $deleteStmtIntervenant = $db->prepare($deleteQueryIntervenant);
                $deleteStmtIntervenant->bindParam(':id_user', $user_id, PDO::PARAM_INT);
                $deleteStmtIntervenant->execute();
            }

            if (isset($_POST['personne'])) {
                if (!$isPersonne) {
                    $insertQueryPersonne = "INSERT INTO personne (id_user) VALUES (:id_user)";
                    $insertStmtPersonne = $db->prepare($insertQueryPersonne);
                    $insertStmtPersonne->bindParam(':id_user', $user_id, PDO::PARAM_INT);
                    $insertStmtPersonne->execute();
                }
            } elseif ($isPersonne) {
                $deleteQueryPersonne = "DELETE FROM personne WHERE id_user = :id_user";
                $deleteStmtPersonne = $db->prepare($deleteQueryPersonne);
                $deleteStmtPersonne->bindParam(':id_user', $user_id, PDO::PARAM_INT);
                $deleteStmtPersonne->execute();
            }

            if (isset($_POST['entreprise'])) {
                if (!$isEntreprise) {
                    $insertQueryEntreprise = "INSERT INTO entreprise (id_user) VALUES (:id_user)";
                    $insertStmtEntreprise = $db->prepare($insertQueryEntreprise);
                    $insertStmtEntreprise->bindParam(':id_user', $user_id, PDO::PARAM_INT);
                    $insertStmtEntreprise->execute();
                }
            } elseif ($isEntreprise) {
                // Si l'utilisateur est déjà associé en tant qu'entreprise, vous pouvez le désassocier ici
                $deleteQueryEntreprise = "DELETE FROM entreprise WHERE id_user = :id_user";
                $deleteStmtEntreprise = $db->prepare($deleteQueryEntreprise);
                $deleteStmtEntreprise->bindParam(':id_user', $user_id, PDO::PARAM_INT);
                $deleteStmtEntreprise->execute();
            }

            header('Location: indexconnecter.php');
            exit();
        }

        echo '<form method="POST">';
        echo '<label>Salarie</label>';
        echo '<input type="checkbox" name="salarie" ' . ($isSalarie ? 'checked' : '') . '><br>';
        echo '<label>Intervenant Externe</label>';
        echo '<input type="checkbox" name="intervenant_externe" ' . ($isIntervenantExterne ? 'checked' : '') . '><br>';
        echo '<label>Personne</label>';
        echo '<input type="checkbox" name="personne" ' . ($isPersonne ? 'checked' : '') . '><br>';
        echo '<label>Entreprise</label>';
        echo '<input type="checkbox" name="entreprise" ' . ($isEntreprise ? 'checked' : '') . '><br>';
        echo '<input type="submit" value="Modifier">';
        echo '</form>';
    } else {
        echo "ID de l'utilisateur manquant dans l'URL.";
    }
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}
?>
