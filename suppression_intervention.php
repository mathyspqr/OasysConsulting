<?php
session_start();

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $interventionId = $_GET['id'];
    
    // Connexion à la base de données
    $user = 'root';
    $pass = 'root';

    try {
        $db = new PDO('mysql:host=localhost;dbname=oasys', $user, $pass);
        
        // Supprimer l'intervention
        $sql = "DELETE FROM intervention WHERE id_intervention = :interventionId";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':interventionId', $interventionId, PDO::PARAM_INT);
        
        if ($stmt->execute()) {
            header('Location: indexconnecter.php');
        } else {
            echo "La suppression de l'intervention a échoué.";
        }
    } catch (PDOException $e) {
        echo "Erreur de connexion à la base de données : " . $e->getMessage();
    }
} else {
    echo "ID d'intervention non valide.";
}
