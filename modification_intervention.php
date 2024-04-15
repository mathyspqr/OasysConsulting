<?php
session_start();
$user = 'root';
$pass = 'root';

try {
    $db = new PDO('mysql:host=localhost;dbname=oasys', $user, $pass);

    if (isset($_GET['id']) && is_numeric($_GET['id'])) {
        $id_intervention = $_GET['id'];

        // Requête pour récupérer les informations de l'intervention en fonction de l'ID
        $sql = "SELECT * FROM intervention WHERE id_intervention = :id_intervention";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':id_intervention', $id_intervention, PDO::PARAM_INT);
        $stmt->execute();
        $intervention = $stmt->fetch(PDO::FETCH_ASSOC);


        if ($intervention) {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {

                $updateSql = "UPDATE intervention SET
                    datedebutint = :date_debut,
                    datefinint = :date_fin,
                    nbheure = :nombre_heures,
                    id_facture = :id_facture,
                    date_facture = :date_facture,
                    montant_total = :montant_total,
                    memo_intervention = :memo_intervention,
                    id_status_intervention = :id_status_intervention
                    WHERE id_intervention = :id_intervention";

                $updateStmt = $db->prepare($updateSql);

                $updateStmt->bindParam(':date_debut', $_POST['date_debut'], PDO::PARAM_STR);
                $updateStmt->bindParam(':date_fin', $_POST['date_fin'], PDO::PARAM_STR);
                $updateStmt->bindParam(':nombre_heures', $_POST['nb_heures'], PDO::PARAM_INT);
                $updateStmt->bindParam(':id_facture', $_POST['id_facture'], PDO::PARAM_INT);
                $updateStmt->bindParam(':date_facture', $_POST['date_facture'], PDO::PARAM_STR);
                $updateStmt->bindParam(':montant_total', $_POST['montant_total'], PDO::PARAM_STR);
                $updateStmt->bindParam(':memo_intervention', $_POST['memo_intervention'], PDO::PARAM_STR);
                $updateStmt->bindParam(':id_status_intervention', $_POST['id_status_intervention'], PDO::PARAM_INT);
                $updateStmt->bindParam(':id_intervention', $id_intervention, PDO::PARAM_INT);

                if ($updateStmt->execute()) {
                    echo "L'intervention a été modifiée avec succès.";
                    header('Location: indexconnecter.php');
                } else {
                    echo "Erreur lors de la modification de l'intervention.";
                }
            }
        }
    }
} catch (PDOException $e) {
    echo 'Erreur de connexion à la base de données : ' . $e->getMessage();
}
?>

<!DOCTYPE html>
<html>

<body>
    <h1>Modifier Intervention</h1>
    <form method="POST">
        <label for="date_debut">Date de Début :</label>
        <input type="date" id="date_debut" name="date_debut" value="<?php echo $intervention['datedebutint']; ?>"
            required>
        <br>

        <label for="date_fin">Date de Fin :</label>
        <input type="date" id="date_fin" name="date_fin" value="<?php echo $intervention['datefinint']; ?>" required>
        <br>

        <label for="id_status_intervention">Statut de l'Intervention :</label>
        <select id="id_status_intervention" name="id_status_intervention">
            <?php
            $user = 'root';
            $pass = 'root';

            try {
                $db = new PDO('mysql:host=localhost;dbname=oasys', $user, $pass);
                $sql = "SELECT id_status_intervention, description FROM status_intervention";
                $stmt = $db->prepare($sql);
                $stmt->execute();
                $status_interventions = $stmt->fetchAll(PDO::FETCH_ASSOC);

                foreach ($status_interventions as $status) {
                    $selected = ($status['id_status_intervention'] == $intervention['id_status_intervention']) ? 'selected' : '';
                    echo "<option value='{$status['id_status_intervention']}' $selected>{$status['description']}</option>";
                }
            } catch (PDOException $e) {
                echo 'Erreur de connexion à la base de données : ' . $e->getMessage();
            }
            ?>
        </select>
        <br>

        <label for="memo_intervention">Memo de l'Intervention :</label>
        <textarea id="memo_intervention" name="memo_intervention" rows="4"
            cols="50"><?php echo $intervention['memo_intervention']; ?></textarea>
        <br>

        <label for="nb_heures">Nombre d'Heures :</label>
        <input type="number" id="nb_heures" name="nb_heures" value="<?php echo $intervention['nbheure']; ?>" required>
        <br>

        <label for="id_facture">ID de la Facture :</label>
        <input type="text" id="id_facture" name="id_facture" value="<?php echo $intervention['id_facture']; ?>">
        <br>

        <label for="date_facture">Date de la Facture :</label>
        <input type="date" id="date_facture" name="date_facture" value="<?php echo $intervention['date_facture']; ?>">
        <br>
        <input type="submit" value="Modifier l'Intervention">
    </form>
</body>

</html>