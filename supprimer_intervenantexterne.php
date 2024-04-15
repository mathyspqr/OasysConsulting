<?php
session_start();

$user = 'root';
$pass = 'root';

try {
    $db = new PDO('mysql:host=localhost;dbname=oasys', $user, $pass);

    if (isset($_GET['id'])) {
        $intervenant_id = $_GET['id'];

        $deleteQuery = "DELETE FROM intervenant_externe WHERE id_intervenantexterne = :intervenant_id";
        $deleteStmt = $db->prepare($deleteQuery);
        $deleteStmt->bindParam(':intervenant_id', $intervenant_id, PDO::PARAM_INT);
        
        if ($deleteStmt->execute()) {
            header("refresh:3;url=http://localhost/");
            exit();
        } else {
            echo "Erreur lors de la suppression de l'intervenant externe.";
        }
    } else {
        echo "ID de l'intervenant externe manquant dans l'URL.";
    }
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}
?>
