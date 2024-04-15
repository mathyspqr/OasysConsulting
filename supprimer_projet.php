<?php
$user = 'root';
$pass = 'root';

try {
    $db = new PDO('mysql:host=localhost;dbname=oasys', $user, $pass);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $id_projet = $_POST['id_projet'];

    $sql = "DELETE FROM projet WHERE id_projet = :id_projet";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':id_projet', $id_projet, PDO::PARAM_INT);
    $stmt->execute();

    header('Location: indexconnecter.php');
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}
?>
