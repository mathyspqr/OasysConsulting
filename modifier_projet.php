<?php
session_start();
$user = 'root';
$pass = 'root';

try {
    $db = new PDO('mysql:host=localhost;dbname=oasys', $user, $pass);

    if (isset($_GET['id_projet'])) {
        $id_projet = $_GET['id_projet'];

        $query = "SELECT * FROM projet WHERE id_projet = :id_projet";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':id_projet', $id_projet, PDO::PARAM_INT);
        $stmt->execute();
        $projet = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $libelleproj = $_POST['libelleproj'];
            $taux_horaire = $_POST['taux_horaire'];
            $datedebutproj = $_POST['datedebutproj'];
            $datefinproj = $_POST['datefinproj'];

            $updateQuery = "UPDATE projet SET libelleproj = :libelleproj, taux_horaire = :taux_horaire, datedebutproj = :datedebutproj, datefinproj = :datefinproj WHERE id_projet = :id_projet";
            $updateStmt = $db->prepare($updateQuery);
            $updateStmt->bindParam(':id_projet', $id_projet, PDO::PARAM_INT);
            $updateStmt->bindParam(':libelleproj', $libelleproj, PDO::PARAM_STR);
            $updateStmt->bindParam(':taux_horaire', $taux_horaire, PDO::PARAM_STR);
            $updateStmt->bindParam(':datedebutproj', $datedebutproj, PDO::PARAM_STR);
            $updateStmt->bindParam(':datefinproj', $datefinproj, PDO::PARAM_STR);
            $updateStmt->execute();

            header('Location: index.html');
            exit();
        }

        echo '<form method="POST">';
        echo '<input type="hidden" name="id_projet" value="' . $projet['id_projet'] . '">';
        echo '<label for="libelleproj">Libellé du Projet :</label>';
        echo '<input type="text" name="libelleproj" value="' . $projet['libelleproj'] . '"><br>';
        echo '<label for="taux_horaire">Taux Horaire :</label>';
        echo '<input type="text" name="taux_horaire" value="' . $projet['taux_horaire'] . '"><br>';
        echo '<label for="datedebutproj">Date de Début :</label>';
        echo '<input type="date" name="datedebutproj" value="' . $projet['datedebutproj'] . '"><br>';
        echo '<label for="datefinproj">Date de Fin :</label>';
        echo '<input type="date" name="datefinproj" value="' . $projet['datefinproj'] . '"><br>';
        echo '<br>';
        echo '<input type="submit" value="Enregistrer les modifications">';
        echo '</form>';
    } else {
        echo "ID de projet manquant dans l'URL.";
    }
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}
?>
