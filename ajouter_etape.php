<?php
session_start();
$user = 'root';
$pass = 'root';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $libelleetape = $_POST["libelleetape"];
    $datedebutetape = $_POST["datedebutetape"];
    $datefinetape = $_POST["datefinetape"];
    $id_projet = $_POST["id_projet"];

    try {
        $pdo = new PDO("mysql:host=localhost;dbname=oasys", $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "INSERT INTO etape (id_projet, libelleetape, datedebutetape, datefinetape) VALUES (:id_projet, :libelleetape, :datedebutetape, :datefinetape)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':id_projet', $id_projet);
        $stmt->bindValue(':libelleetape', $libelleetape);
        $stmt->bindValue(':datedebutetape', $datedebutetape);
        $stmt->bindValue(':datefinetape', $datefinetape);
        $stmt->execute();

        header('Location: http://localhost/oasys/indexconnecter.php');
        exit();
    } catch (PDOException $e) {
        die("Erreur : " . $e->getMessage());
    }
}
?>
