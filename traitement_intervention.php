<?php
session_start();

// Assurez-vous de disposer des informations de connexion à la base de données
$user = 'root';
$pass = 'root';

try {
    $db = new PDO('mysql:host=localhost;dbname=oasys', $user, $pass);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Récupérer les données soumises par le formulaire
        $projet = $_POST['projet'];
        $etape = $_POST['etape'];
        $selectedIntervenant = $_POST['id_intervenant'];
        
        // Extrait l'ID de l'intervenant externe, le cas échéant
        $id_intervenantexterne = null;
        if (strpos($selectedIntervenant, 'Externe:') === 0) {
            $id_intervenantexterne = substr($selectedIntervenant, 8);
            $id_intervenant = null;
        } else {
            $id_intervenant = $selectedIntervenant;
            $id_intervenantexterne = null;
        }

        // Validez les données ici si nécessaire

        // Insérer les données dans la table d'intervention
        $sql = "INSERT INTO intervention (id_etape, id_intervenant, id_intervenantexterne) VALUES (:etape, :id_intervenant, :id_intervenantexterne)";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':etape', $etape, PDO::PARAM_INT);
        $stmt->bindParam(':id_intervenant', $id_intervenant, PDO::PARAM_INT);
        $stmt->bindParam(':id_intervenantexterne', $id_intervenantexterne, PDO::PARAM_INT);

        // Exécuter la requête
        if ($stmt->execute()) {
            // L'insertion a réussi
            echo "Intervention créée avec succès.";
        } else {
            // L'insertion a échoué
            echo "Une erreur est survenue lors de la création de l'intervention.";
        }
    } else {
        // Redirection ou message d'erreur en cas d'accès direct à ce script sans soumission de formulaire
        echo "Accès non autorisé.";
    }
} catch (PDOException $e) {
    // Gérez les erreurs de connexion à la base de données ici
    die("Erreur de base de données : " . $e->getMessage());
}
?>
