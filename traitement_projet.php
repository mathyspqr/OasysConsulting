<?php
$libelleproj = $_POST['libelleproj'];
$taux_horaire = $_POST['taux_horaire'];
$datedebutproj = $_POST['datedebutproj'];
$datefinproj = $_POST['datefinproj'];
$id_domaine = $_POST['id_domaine'];
$id_client = $_POST['id_client'];
$id_chefprojet = $_POST['id_chefprojet'];

$user = 'root';
$pass = 'root';
$db = new PDO('mysql:host=localhost;dbname=oasys', $user, $pass);

$sql = "INSERT INTO projet (libelleproj, taux_horaire, datedebutproj, datefinproj, id_domaine, id_client, id_chefprojet) 
        VALUES (:libelleproj, :taux_horaire, :datedebutproj, :datefinproj, :id_domaine, :id_client, :id_chefprojet)";
$stmt = $db->prepare($sql);

$stmt->bindParam(':libelleproj', $libelleproj, PDO::PARAM_STR);
$stmt->bindParam(':taux_horaire', $taux_horaire, PDO::PARAM_STR);
$stmt->bindParam(':datedebutproj', $datedebutproj, PDO::PARAM_STR);
$stmt->bindParam(':datefinproj', $datefinproj, PDO::PARAM_STR);
$stmt->bindParam(':id_domaine', $id_domaine, PDO::PARAM_INT);
$stmt->bindParam(':id_client', $id_client, PDO::PARAM_INT);
$stmt->bindParam(':id_chefprojet', $id_chefprojet, PDO::PARAM_INT);

if ($stmt->execute()) {
    echo "Le projet a été créé avec succès.";
} else {
    echo "Erreur lors de la création du projet : " . $stmt->errorInfo()[2];
}
?>
