<?php
$user = 'root';
$pass = 'root';

try {
    $db = new PDO('mysql:host=localhost;dbname=oasys', $user, $pass);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $id_etape = $_POST['id_etape'];
    echo "ID de l'étape à supprimer : $id_etape";
    $sql = "DELETE FROM etape WHERE id_etape = :id_etape";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':id_etape', $id_etape, PDO::PARAM_INT);
    $stmt->execute();

    header('Location: indexconnecter.php'); 
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}
?>
