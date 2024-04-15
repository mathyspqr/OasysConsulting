<?php
session_start();
$user = 'root';
$pass = 'root';

try {
    $db = new PDO('mysql:host=localhost;dbname=oasys', $user, $pass);

    if (isset($_GET['id_etape']) && is_numeric($_GET['id_etape'])) {
        $id_etape = $_GET['id_etape'];

        $sql = "SELECT * FROM etape WHERE id_etape = :id_etape";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':id_etape', $id_etape, PDO::PARAM_INT);
        $stmt->execute();
        $etape = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($etape) {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $libelleetape = $_POST['libelleetape'];
                $datedebutetape = $_POST['datedebutetape'];
                $datefinetape = $_POST['datefinetape'];

                $updateSql = "UPDATE etape SET libelleetape = :libelleetape, datedebutetape = :datedebutetape, datefinetape = :datefinetape WHERE id_etape = :id_etape";
                $updateStmt = $db->prepare($updateSql);
                $updateStmt->bindParam(':libelleetape', $libelleetape, PDO::PARAM_STR);
                $updateStmt->bindParam(':datedebutetape', $datedebutetape, PDO::PARAM_STR);
                $updateStmt->bindParam(':datefinetape', $datefinetape, PDO::PARAM_STR);
                $updateStmt->bindParam(':id_etape', $id_etape, PDO::PARAM_INT);

                if ($updateStmt->execute()) {
                    echo "L'étape a été modifiée avec succès.";
                    header('Location: http://localhost/oasys/index.html');

                } else {
                    echo "Erreur lors de la modification de l'étape.";
                }
            }
?>
<!DOCTYPE html>
<html>
<head>
    <title>Modifier Étape</title>
</head>
<body>
    <h1>Modifier Étape</h1>
    <form method="POST">
        <label for="libelleetape">Libellé de l'Étape:</label>
        <input type="text" id="libelleetape" name="libelleetape" value="<?php echo $etape['libelleetape']; ?>" required>
        <br>

        <label for="datedebutetape">Date de Début de l'Étape:</label>
        <input type="date" id="datedebutetape" name="datedebutetape" value="<?php echo $etape['datedebutetape']; ?>" required>
        <br>

        <label for="datefinetape">Date de Fin de l'Étape:</label>
        <input type="date" id="datefinetape" name="datefinetape" value="<?php echo $etape['datefinetape']; ?>" required>
        <br>

        <input type="submit" value="Modifier l'Étape">
    </form>
</body>
</html>
<?php
        } else {
            echo "L'ID de l'étape n'est pas valide ou l'étape n'existe pas.";
        }
    } else {
        echo "ID de l'étape manquant dans l'URL.";
    }
} catch (PDOException $e) {
    echo 'Erreur de connexion à la base de données : ' . $e->getMessage();
}
?>
