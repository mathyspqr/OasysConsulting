<?php
session_start();
$user = 'root';
$pass = 'root';

try {
    $db = new PDO('mysql:host=localhost;dbname=oasys', $user, $pass);

    $id_projet = $_POST['id_projet'];

    $sql = "SELECT * FROM etape WHERE id_projet = :id_projet";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':id_projet', $id_projet, PDO::PARAM_INT);
    $stmt->execute();

    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($result) {
        echo "<table border='1'>";
        echo "<tr><th>Libellé Étape</th><th>Date Début Étape</th><th>Date Fin Étape</th><th>ID Projet</th></tr>";

        foreach ($result as $row) {
            echo "<tr>";
            echo "<td>" . $row["libelleetape"] . "</td>";
            echo "<td>" . $row["datedebutetape"] . "</td>";
            echo "<td>" . $row["datefinetape"] . "</td>";
            echo "<td>" . $row["id_projet"] . "</td>";
            echo "</tr>";
        }

        echo "</table>";
    } else {
        echo "Aucune étape disponible pour ce projet.";
    }
} catch (PDOException $e) {
    echo "Erreur de connexion à la base de données : " . $e->getMessage();
}
?>

<h2>Ajouter une nouvelle étape</h2>

<form action="ajouter_etape.php" method="post">
    <input type="hidden" name="id_projet" value="<?php echo $id_projet; ?>">
    <label for="libelleetape">Libellé Étape:</label>
    <input type="text" name="libelleetape" id="libelleetape" required>
    <label for="datedebutetape">Date Début Étape:</label>
    <input type="date" name="datedebutetape" id="datedebutetape" required>
    <label for="datefinetape">Date Fin Étape:</label>
    <input type="date" name="datefinetape" id="datefinetape" required>
    <br>
    <input type="submit" value="Ajouter Étape">
</form>



