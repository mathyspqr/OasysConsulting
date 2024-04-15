<!DOCTYPE html>
<html lang="fr">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">


<head>
    <title>Oasys Consulting</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="apple-touch-icon" href="assets/img/apple-icon.png">
    <link rel="shortcut icon" type="image/x-icon" href="assets/img/favicon.ico">
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/boxicon.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/templatemo.css">
    <link rel="stylesheet" href="assets/css/custom.css">

</head>

<body>
    <nav id="main_nav" class="navbar navbar-expand-lg navbar-light bg-white shadow">
        <div class="container d-flex justify-content-between align-items-center">
            <a class="navbar-brand h1" href="index.html">
                <i class='bx bx-buildings bx-sm text-dark'></i>
                <span class="text-dark h4">Oasys</span> <span class="text-primary h4">Consulting</span>
            </a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbar-toggler-success" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="align-self-center collapse navbar-collapse flex-fill  d-lg-flex justify-content-lg-between"
                id="navbar-toggler-success">
                <div class="flex-fill mx-xl-5 mb-2">
                    <ul class="nav navbar-nav d-flex justify-content-between mx-xl-5 text-center text-dark">
                        <li class="nav-item">
                            <a class="nav-link btn-outline-primary rounded-pill px-3" href="index.html">Accueil</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link btn-outline-primary rounded-pill px-3" href="about.html">A propos</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link btn-outline-primary rounded-pill px-3" href="contact.php">Salarie &
                                Intervenant Externe</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link btn-outline-primary rounded-pill px-3"
                                href="client&entreprise.php">Client</a>
                        </li>
                    </ul>
                </div>
                <div class="navbar align-self-center d-flex">
                    <a class="nav-link" href="#"><i class='bx bx-bell bx-sm bx-tada-hover text-primary'></i></a>
                    <a class="nav-link" href="#"><i class='bx bx-cog bx-sm text-primary'></i></a>
                    <a class="nav-link" href="form.html"><i class='bx bx-user-circle bx-sm text-primary'></i></a>
                </div>
            </div>
        </div>
    </nav>

    <section class="bg-light">
        <div class="container py-4">
            <div class="row align-items-center justify-content-between">

                <?php
                session_start();
                $user = 'root';
                $pass = 'root';

                try {
                    $db = new PDO('mysql:host=localhost;dbname=oasys', $user, $pass);

                    $id = $_SESSION['id'];

                    $sql = "SELECT p.*, s.nom AS chef_nom, s.prenom AS chef_prenom, d.nom_domaine AS domaine_nom, c.id_client, s.id_user
                    FROM projet p
                    INNER JOIN salarie s ON p.id_chefprojet = s.id_salarie
                    INNER JOIN domaine d ON p.id_domaine = d.id_domaine
                    LEFT JOIN client c ON p.id_client = c.id_client
                    WHERE s.id_user = :id_user";

                    $stmt = $db->prepare($sql);

                    $stmt->bindParam(':id_user', $id, PDO::PARAM_INT);
                    $stmt->execute();

                    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    if ($result) {
                        echo "<h1>Projet</h1>";
                        echo "<br>";
                        echo "<br>";
                        echo "<table border='1'>";
                        echo "<br>";
                        echo "<tr><th>ID Projet</th><th>Libellé Projet</th><th>Taux Horaire</th><th>Date Début Projet</th><th>Date Fin Projet</th><th>Chef de Projet</th><th>Domaine</th><th>Informations du Client</th><th>Montant Total du Projet</th><th>Avancement du Projet</th><th>Actions</th><th>ID Utilisateur</th></tr>";

                        foreach ($result as $row) {
                            echo "<tr>";
                            echo "<td>" . $row["id_projet"] . "</td>";
                            echo "<td>" . $row["libelleproj"] . "</td>";
                            echo "<td>" . $row["taux_horaire"] . "</td>";
                            echo "<td>" . $row["datedebutproj"] . "</td>";
                            echo "<td>" . $row["datefinproj"] . "</td>";
                            echo "<td>" . $row["chef_nom"] . " " . $row["chef_prenom"] . "</td>";
                            echo "<td>" . $row["domaine_nom"] . "</td>";

                            $clientInfo = "SELECT id_personne, id_entreprise FROM client WHERE id_client = :id_client";
                            $clientInfoStmt = $db->prepare($clientInfo);
                            $clientInfoStmt->bindParam(':id_client', $row["id_client"], PDO::PARAM_INT);
                            $clientInfoStmt->execute();
                            $clientData = $clientInfoStmt->fetch(PDO::FETCH_ASSOC);

                            if (!is_null($clientData["id_personne"])) {
                                $personInfo = "SELECT * FROM personne WHERE id_personne = :id_personne";
                                $personInfoStmt = $db->prepare($personInfo);
                                $personInfoStmt->bindParam(':id_personne', $clientData["id_personne"], PDO::PARAM_INT);
                                $personInfoStmt->execute();
                                $personData = $personInfoStmt->fetch(PDO::FETCH_ASSOC);
                                echo "<td>ID Personne: " . $clientData["id_personne"] . ", Nom: " . $personData["nom"] . ", Prénom: " . $personData["prenom"] . "</td>";
                            } elseif (!is_null($clientData["id_entreprise"])) {
                                $entrepriseInfo = "SELECT * FROM entreprise WHERE id_entreprise = :id_entreprise";
                                $entrepriseInfoStmt = $db->prepare($entrepriseInfo);
                                $entrepriseInfoStmt->bindParam(':id_entreprise', $clientData["id_entreprise"], PDO::PARAM_INT);
                                $entrepriseInfoStmt->execute();
                                $entrepriseData = $entrepriseInfoStmt->fetch(PDO::FETCH_ASSOC);
                                echo "<td>ID Entreprise: " . $clientData["id_entreprise"] . ", Nom: " . $entrepriseData["Nom"] . ", Prénom: " . $entrepriseData["prenom"] . ", SIRET: " . $entrepriseData["siret"] . "</td>";
                            } else {
                                echo "<td>Aucune information du client disponible.</td>";
                            }

                            $projectId = $row['id_projet'];

                            $sql_sum_total_amount = "SELECT SUM(montant_total) AS total_amount FROM intervention WHERE id_etape IN (SELECT id_etape FROM etape WHERE id_projet = :project_id)";
                            $stmt_sum_total_amount = $db->prepare($sql_sum_total_amount);
                            $stmt_sum_total_amount->bindParam(':project_id', $projectId, PDO::PARAM_INT);
                            $stmt_sum_total_amount->execute();
                            $totalAmount = $stmt_sum_total_amount->fetch(PDO::FETCH_ASSOC);

                            echo "<td>" . number_format($totalAmount['total_amount'], 2) . " €</td>";
                
                            $sql_interventions_count = "SELECT COUNT(*) AS intervention_count FROM intervention WHERE id_etape IN (SELECT id_etape FROM etape WHERE id_projet = :project_id) AND id_status_intervention = 5";
                            $stmt_interventions_count = $db->prepare($sql_interventions_count);
                            $stmt_interventions_count->bindParam(':project_id', $projectId, PDO::PARAM_INT);
                            $stmt_interventions_count->execute();
                            $interventionsCount = $stmt_interventions_count->fetch(PDO::FETCH_ASSOC);

                            $sql_total_interventions = "SELECT COUNT(*) AS total_interventions FROM intervention WHERE id_etape IN (SELECT id_etape FROM etape WHERE id_projet = :project_id)";
                            $stmt_total_interventions = $db->prepare($sql_total_interventions);
                            $stmt_total_interventions->bindParam(':project_id', $projectId, PDO::PARAM_INT);
                            $stmt_total_interventions->execute();
                            $totalInterventions = $stmt_total_interventions->fetch(PDO::FETCH_ASSOC);

                            if ($totalInterventions['total_interventions'] > 0) {
                                $progressPercentage = ($interventionsCount['intervention_count'] / $totalInterventions['total_interventions']) * 100;
                            } else {
                                $progressPercentage = 0;
                            }

                            if ($progressPercentage == 100) {
                                echo '<td style="background-color: green; color: white;">Terminé</td>';
                            } else {
                                echo '<td>' . number_format($progressPercentage, 2) . '%</td>';
                            }

                            echo "<td>
                                <form action='supprimer_projet.php' method='post'>
                                    <input type='hidden' name='id_projet' value='" . $row["id_projet"] . "'>
                                    <input type='submit' value='Supprimer'>
                                </form>
                                <br>
                                <br>
                                <br>
                                <a href='modifier_projet.php?id_projet=" . $row["id_projet"] . "'>Modifier</a>
                            </td>";
                            echo "<td>ID Utilisateur: $id</td>";
                            echo "</tr>";
                        }
                        echo "</table>";
                    } else {
                        session_start();
                        $user = 'root';
                        $pass = 'root';
                        $db = new PDO('mysql:host=localhost;dbname=oasys', $user, $pass);

                        $sqlClientProjects = "SELECT p.*, s.nom AS chef_nom, s.prenom AS chef_prenom, d.nom_domaine AS domaine_nom, c.id_client, 
                        CASE
                            WHEN c.id_entreprise IS NOT NULL THEN e.id_user
                            WHEN c.id_personne IS NOT NULL THEN pe.id_user
                        END AS id_user
                    FROM projet p
                    INNER JOIN salarie s ON p.id_chefprojet = s.id_salarie
                    INNER JOIN domaine d ON p.id_domaine = d.id_domaine
                    LEFT JOIN client c ON p.id_client = c.id_client
                    LEFT JOIN entreprise e ON c.id_entreprise = e.id_entreprise
                    LEFT JOIN personne pe ON c.id_personne = pe.id_personne
                    WHERE 
                        (c.id_entreprise IS NOT NULL AND e.id_user = :session_id)
                        OR
                        (c.id_personne IS NOT NULL AND pe.id_user = :session_id)";

                        $stmtClientProjects = $db->prepare($sqlClientProjects);
                        $stmtClientProjects->bindParam(':session_id', $id, PDO::PARAM_INT);
                        $stmtClientProjects->execute();

                        $resultClientProjects = $stmtClientProjects->fetchAll(PDO::FETCH_ASSOC);

                        if ($resultClientProjects) {
                            echo "<h1>Tableau des Projets du client</h1>";
                            echo "<br>";
                            echo "<table border='1'>";
                            echo "<br>";
                            echo "<tr><th>ID Projet</th><th>Libellé Projet</th><th>Taux Horaire</th><th>Date Début Projet</th><th>Date Fin Projet</th><th>Chef de Projet</th><th>Domaine</th><th>ID Utilisateur</th><th>Montant Total du Projet</th><th>Avancement du Projet</th></tr>";

                            foreach ($resultClientProjects as $rowClientProjects) {
                                echo "<tr>";
                                echo "<td>" . $rowClientProjects["id_projet"] . "</td>";
                                echo "<td>" . $rowClientProjects["libelleproj"] . "</td>";
                                echo "<td>" . $rowClientProjects["taux_horaire"] . "</td>";
                                echo "<td>" . $rowClientProjects["datedebutproj"] . "</td>";
                                echo "<td>" . $rowClientProjects["datefinproj"] . "</td>";
                                echo "<td>" . $rowClientProjects["chef_nom"] . " " . $rowClientProjects["chef_prenom"] . "</td>";
                                echo "<td>" . $rowClientProjects["domaine_nom"] . "</td>"; // Ajout du domaine
                                echo "<td>ID Utilisateur: " . $rowClientProjects["id_user"] . "</td>"; // Ajout de l'id_user
                
                                $projectId = $rowClientProjects['id_projet'];

                                $sql_sum_total_amount = "SELECT SUM(montant_total) AS total_amount FROM intervention WHERE id_etape IN (SELECT id_etape FROM etape WHERE id_projet = :project_id)";
                                $stmt_sum_total_amount = $db->prepare($sql_sum_total_amount);
                                $stmt_sum_total_amount->bindParam(':project_id', $projectId, PDO::PARAM_INT);
                                $stmt_sum_total_amount->execute();
                                $totalAmount = $stmt_sum_total_amount->fetch(PDO::FETCH_ASSOC);

                                echo "<td>" . number_format($totalAmount['total_amount'], 2) . " €</td>"; // Montant total du projet
                
                                $sql_interventions_count = "SELECT COUNT(*) AS intervention_count FROM intervention WHERE id_etape IN (SELECT id_etape FROM etape WHERE id_projet = :project_id) AND id_status_intervention = 5";
                                $stmt_interventions_count = $db->prepare($sql_interventions_count);
                                $stmt_interventions_count->bindParam(':project_id', $projectId, PDO::PARAM_INT);
                                $stmt_interventions_count->execute();
                                $interventionsCount = $stmt_interventions_count->fetch(PDO::FETCH_ASSOC);

                                $sql_total_interventions = "SELECT COUNT(*) AS total_interventions FROM intervention WHERE id_etape IN (SELECT id_etape FROM etape WHERE id_projet = :project_id)";
                                $stmt_total_interventions = $db->prepare($sql_total_interventions);
                                $stmt_total_interventions->bindParam(':project_id', $projectId, PDO::PARAM_INT);
                                $stmt_total_interventions->execute();
                                $totalInterventions = $stmt_total_interventions->fetch(PDO::FETCH_ASSOC);

                                if ($totalInterventions['total_interventions'] > 0) {
                                    $progressPercentage = ($interventionsCount['intervention_count'] / $totalInterventions['total_interventions']) * 100;
                                } else {
                                    $progressPercentage = 0;
                                }

                                if ($progressPercentage == 100) {
                                    echo '<td style="background-color: green; color: white;">Terminé</td>';
                                } else {
                                    echo '<td>' . number_format($progressPercentage, 2) . '%</td>';
                                }

                                echo "</tr>";
                            }
                            echo "</table>";
                        }

                        if ($resultClientProjects) {
                            echo "<br>";
                            echo "<h1>Tableau des Étapes des Projets du client</h1>";
                            echo "<br>";
                            echo '<table border="1">';
                            echo "<br>";
                            echo '<tr><th>ID Étape</th><th>Libellé Étape</th><th>Libellé Projet</th><th>Date de Début Étape</th><th>Date de Fin Étape</th><th>Montant Total</th><th>ID Factures</th><th>Terminé</th></tr>';
                            foreach ($resultClientProjects as $rowClientProjects) {
                                $projectId = $rowClientProjects['id_projet'];

                                $sql_etapes = "SELECT e.id_etape, e.libelleetape, p.libelleproj, e.datedebutetape, e.datefinetape
                                FROM etape e
                                JOIN projet p ON e.id_projet = p.id_projet
                                WHERE e.id_projet = :id_projet";
                                $stmt_etapes = $db->prepare($sql_etapes);
                                $stmt_etapes->bindParam(':id_projet', $projectId, PDO::PARAM_INT);
                                $stmt_etapes->execute();
                                $etapes = $stmt_etapes->fetchAll(PDO::FETCH_ASSOC);

                                if ($etapes) {
                                    foreach ($etapes as $etape) {
                                        echo "<tr>";
                                        echo "<td>" . $etape['id_etape'] . "</td>";
                                        echo "<td>" . $etape['libelleetape'] . "</td>";
                                        echo "<td>" . $etape['libelleproj'] . "</td>";
                                        echo "<td>" . $etape['datedebutetape'] . "</td>";
                                        echo "<td>" . $etape['datefinetape'] . "</td>";

                                        $sql_sum_montant = "SELECT SUM(montant_total) AS total_montant FROM intervention WHERE id_etape = :etapeId";
                                        $stmt_sum_montant = $db->prepare($sql_sum_montant);
                                        $stmt_sum_montant->bindParam(':etapeId', $etape['id_etape'], PDO::PARAM_INT);
                                        $stmt_sum_montant->execute();
                                        $sum_montant = $stmt_sum_montant->fetch(PDO::FETCH_ASSOC);

                                        $sql_id_factures = "SELECT id_facture FROM intervention WHERE id_etape = :etapeId";
                                        $stmt_id_factures = $db->prepare($sql_id_factures);
                                        $stmt_id_factures->bindParam(':etapeId', $etape['id_etape'], PDO::PARAM_INT);
                                        $stmt_id_factures->execute();
                                        $id_factures = $stmt_id_factures->fetchAll(PDO::FETCH_COLUMN);

                                        echo '<td>' . number_format($sum_montant['total_montant'], 2) . ' €</td>';
                                        echo '<td>' . implode(', ', $id_factures) . '</td>';

                                        $sql_statut_interventions = "SELECT COUNT(*) AS total_interventions, SUM(CASE WHEN id_status_intervention = 5 THEN 1 ELSE 0 END) AS terminées FROM intervention WHERE id_etape = :etapeId";
                                        $stmt_statut_interventions = $db->prepare($sql_statut_interventions);
                                        $stmt_statut_interventions->bindParam(':etapeId', $etape['id_etape'], PDO::PARAM_INT);
                                        $stmt_statut_interventions->execute();
                                        $statut_interventions = $stmt_statut_interventions->fetch(PDO::FETCH_ASSOC);

                                        $pourcentage_terminées = ($statut_interventions['terminées'] / $statut_interventions['total_interventions']) * 100;

                                        echo '<td>' . number_format($pourcentage_terminées, 2) . '%</td>';

                                        echo '<td>';
                                        if ($pourcentage_terminées == 100) {
                                            echo "Toutes les interventions sont terminées";
                                        } else {
                                            echo "Interventions en cours";
                                        }
                                        echo '</td>';

                                        echo "</tr>";
                                    }
                                }
                            }

                            echo '</table>';

                            if ($resultClientProjects) {
                                echo "<br>";
                                echo "<h1>Tableau des Factures des Interventions du client</h1>";
                                echo "<br>";
                                echo '<table border="1">';
                                echo '<tr><th>ID Facture</th><th>Montant Total</th></tr>';

                                $totalAmount = 0;
                
                                foreach ($resultClientProjects as $rowClientProjects) {
                                    $projectId = $rowClientProjects['id_projet'];

                                    $sql_factures = "SELECT id_facture, SUM(montant_total) AS total_montant
                                        FROM intervention
                                        WHERE id_etape IN (SELECT id_etape FROM etape WHERE id_projet = :project_id)
                                        GROUP BY id_facture";
                                    $stmt_factures = $db->prepare($sql_factures);
                                    $stmt_factures->bindParam(':project_id', $projectId, PDO::PARAM_INT);
                                    $stmt_factures->execute();
                                    $factures = $stmt_factures->fetchAll(PDO::FETCH_ASSOC);

                                    if ($factures) {
                                        foreach ($factures as $facture) {
                                            echo "<tr>";
                                            echo "<td>" . $facture['id_facture'] . "</td>";
                                            echo "<td>" . number_format($facture['total_montant'], 2) . ' €</td>';
                                            echo "</tr>";

                                            $totalAmount += $facture['total_montant'];
                                        }
                                    }
                                }
                                echo '<tr><th>Total</th><th>' . number_format($totalAmount, 2) . ' €</th><th><button>Régler Maintenant</button></th></tr>';
                                echo '</table>';
                            }




                        } else {
                            session_start();
                            $user = 'root';
                            $pass = 'root';
                            $db = new PDO('mysql:host=localhost;dbname=oasys', $user, $pass);

                            $id_session = $_SESSION['id'];

                            $sql_user_projects = "SELECT DISTINCT p.id_projet, p.libelleproj, p.taux_horaire, p.datedebutproj, p.datefinproj, s.nom AS chef_nom, s.prenom AS chef_prenom, p.id_projet
                            FROM projet p
                            INNER JOIN etape e ON p.id_projet = e.id_projet
                            INNER JOIN salarie s ON p.id_chefprojet = s.id_salarie
                            WHERE e.id_etape IN (
                                SELECT DISTINCT id_etape FROM intervention 
                                WHERE 
                                (
                                    (id_intervenant IS NOT NULL AND id_intervenant IN (
                                        SELECT id_salarie FROM salarie WHERE id_user = :id_session 
                                        UNION 
                                        SELECT id_intervenantexterne FROM intervenant_externe WHERE id_user = :id_session
                                    ))
                                    OR
                                    (id_intervenant IS NULL AND id_intervenantexterne IN (
                                        SELECT id_intervenantexterne FROM intervenant_externe WHERE id_user = :id_session
                                    ))
                                )
                            )";

                            $stmt_user_projects = $db->prepare($sql_user_projects);
                            $stmt_user_projects->bindParam(':id_session', $id_session, PDO::PARAM_INT);
                            $stmt_user_projects->execute();
                            $user_projects = $stmt_user_projects->fetchAll(PDO::FETCH_ASSOC);

                            if ($user_projects) {
                                echo '<h1>Projets associés aux Interventions de l\'utilisateur</h1>';
                                echo '<br>';
                                echo '<table border="1" style="border-collapse: collapse; width: 100%;">';
                                echo '<tr><th>ID Projet</th><th>Libellé Projet</th><th>Taux Horaire</th><th>Date Début Projet</th><th>Date Fin Projet</th><th>Chef de Projet</th><th>Montant Total du Projet</th><th>Avancement du Projet</th></tr>';

                                foreach ($user_projects as $user_project) {
                                    $projectId = $user_project['id_projet'];

                                    $sql_sum_total_amount = "SELECT SUM(montant_total) AS total_amount FROM intervention WHERE id_etape IN (SELECT id_etape FROM etape WHERE id_projet = :project_id)";
                                    $stmt_sum_total_amount = $db->prepare($sql_sum_total_amount);
                                    $stmt_sum_total_amount->bindParam(':project_id', $projectId, PDO::PARAM_INT);
                                    $stmt_sum_total_amount->execute();
                                    $totalAmount = $stmt_sum_total_amount->fetch(PDO::FETCH_ASSOC);

                                    $sql_count_etapes = "SELECT COUNT(*) AS etape_count FROM etape WHERE id_projet = :project_id";
                                    $stmt_count_etapes = $db->prepare($sql_count_etapes);
                                    $stmt_count_etapes->bindParam(':project_id', $projectId, PDO::PARAM_INT);
                                    $stmt_count_etapes->execute();
                                    $etapeCount = $stmt_count_etapes->fetch(PDO::FETCH_ASSOC);

                                    $sql_total_interventions = "SELECT COUNT(*) AS total_interventions FROM intervention WHERE id_etape IN (SELECT id_etape FROM etape WHERE id_projet = :project_id)";
                                    $stmt_total_interventions = $db->prepare($sql_total_interventions);
                                    $stmt_total_interventions->bindParam(':project_id', $projectId, PDO::PARAM_INT);
                                    $stmt_total_interventions->execute();
                                    $totalInterventions = $stmt_total_interventions->fetch(PDO::FETCH_ASSOC);

                                    $sql_interventions_count = "SELECT COUNT(*) AS intervention_count FROM intervention WHERE id_etape IN (SELECT id_etape FROM etape WHERE id_projet = :project_id) AND id_status_intervention = 5";
                                    $stmt_interventions_count = $db->prepare($sql_interventions_count);
                                    $stmt_interventions_count->bindParam(':project_id', $projectId, PDO::PARAM_INT);
                                    $stmt_interventions_count->execute();
                                    $interventionsCount = $stmt_interventions_count->fetch(PDO::FETCH_ASSOC);

                                    $allInterventionsCompleted = ($interventionsCount['intervention_count'] == $totalInterventions['total_interventions']);

                                    $progressPercentage = ($interventionsCount['intervention_count'] / $totalInterventions['total_interventions']) * 100;

                                    echo '<tr>';
                                    echo '<td style="padding: 5px;">' . $user_project['id_projet'] . '</td>';
                                    echo '<td style="padding: 5px;">' . $user_project['libelleproj'] . '</td>';
                                    echo '<td style="padding: 5px;">' . $user_project['taux_horaire'] . '</td>';
                                    echo '<td style="padding: 5px;">' . $user_project['datedebutproj'] . '</td>';
                                    echo '<td style="padding: 5px;">' . $user_project['datefinproj'] . '</td>';
                                    echo '<td style="padding: 5px;">' . $user_project['chef_nom'] . ' ' . $user_project['chef_prenom'] . '</td>';
                                    echo '<td style="padding: 5px;">' . number_format($totalAmount['total_amount'], 2) . ' €</td>';

                                    if ($allInterventionsCompleted) {
                                        echo '<td style="padding: 5px;">Terminé</td>';
                                    } else {
                                        echo '<td style="padding: 5px;">' . $progressPercentage . '%</td>';
                                    }

                                    echo '</tr>';
                                }
                                echo '</table>';

                            } else {
                                echo "Aucun projet associé aux interventions de cet utilisateur n'a été trouvé.";
                            }

                        }

                    }

                } catch (PDOException $e) {
                    echo "Erreur de connexion à la base de données : " . $e->getMessage();
                }
                ?>

            </div>
            <br>

            <?php
            session_start();
            $user = 'root';
            $pass = 'root';
            try {
                $db = new PDO('mysql:host=localhost;dbname=oasys', $user, $pass);

                $isPersonnePhysique = isUserType($db, $_SESSION['id'], 'personne');
                $isIntervenantExterne = isUserType($db, $_SESSION['id'], 'intervenant_externe');
                $isSalarie = isUserType($db, $_SESSION['id'], 'salarie');

                if ($isPersonnePhysique && $isIntervenantExterne && $isSalarie) {
                    echo '<h1>Formulaire de Création de Projet</h1>';
                    echo '<form action="traitement_projet.php" method="post">';
                    echo '<label for="libelleproj">Libellé du Projet:</label>';
                    echo '<input type="text" id="libelleproj" name="libelleproj" required>';
                    echo '<br>';

                    echo '<label for="taux_horaire">Taux Horaire:</label>';
                    echo '<input type="text" id="taux_horaire" name="taux_horaire" required>';
                    echo '<br>';

                    echo '<label for="datedebutproj">Date de Début du Projet:</label>';
                    echo '<input type="date" id="datedebutproj" name="datedebutproj" required>';
                    echo '<br>';

                    echo '<label for="datefinproj">Date de Fin du Projet:</label>';
                    echo '<input type="date" id="datefinproj" name="datefinproj" required>';
                    echo '<br>';

                    echo '<label for="id_domaine">Domaine:</label>';
                    echo '<select id="id_domaine" name="id_domaine" required>';
                    $sql = "SELECT id_domaine, nom_domaine FROM domaine";
                    $result = $db->query($sql);
                    foreach ($result as $row) {
                        echo "<option value='" . $row["id_domaine"] . "'>" . $row["nom_domaine"] . "</option>";
                    }
                    echo '</select>';
                    echo '<br>';
                    echo '<label for="id_client">Client:</label>';
                    echo '<select id="id_client" name="id_client" required>';
                    $sql = "SELECT id_client, id_personne, id_entreprise FROM client";
                    $result = $db->query($sql);
                    foreach ($result as $row) {
                        if (!is_null($row["id_personne"])) {
                            $personInfo = "SELECT * FROM personne WHERE id_personne = :id_personne";
                            $personInfoStmt = $db->prepare($personInfo);
                            $personInfoStmt->bindParam(':id_personne', $row["id_personne"], PDO::PARAM_INT);
                            $personInfoStmt->execute();
                            $personData = $personInfoStmt->fetch(PDO::FETCH_ASSOC);
                            echo "<option value='" . $row["id_client"] . "'>" . $personData["nom"] . " " . $personData["prenom"] . "</option>";
                        } elseif (!is_null($row["id_entreprise"])) {
                            $entrepriseInfo = "SELECT Nom, prenom FROM entreprise WHERE id_entreprise = :id_entreprise";
                            $entrepriseInfoStmt = $db->prepare($entrepriseInfo);
                            $entrepriseInfoStmt->bindParam(':id_entreprise', $row["id_entreprise"], PDO::PARAM_INT);
                            $entrepriseInfoStmt->execute();
                            $entrepriseData = $entrepriseInfoStmt->fetch(PDO::FETCH_ASSOC);
                            echo "<option value='" . $row["id_client"] . "'>" . $entrepriseData["Nom"] . " " . $entrepriseData["prenom"] . "</option>";
                        }
                    }
                    echo '</select>';
                    echo '<br>';


                    echo '<label for="id_chefprojet">Chef de Projet:</label>';
                    echo '<select id="id_chefprojet" name="id_chefprojet" required>';
                    $sql = "SELECT id_salarie, nom FROM salarie";
                    $result = $db->query($sql);
                    foreach ($result as $row) {
                        echo "<option value='" . $row["id_salarie"] . "'>" . $row["nom"] . "</option>";
                    }
                    echo '</select>';
                    echo '<br>';
                    echo '<input type="submit" value="Créer le Projet">';
                    echo '</form>';
                } else {
                    echo 'Vous n\'avez pas les autorisations nécessaires pour accéder au formulaire de création de projet.';
                }
            } catch (PDOException $e) {
                echo "Erreur : " . $e->getMessage();
            }
            function isUserType($db, $id, $type)
            {
                $query = "SELECT * FROM $type WHERE id_user = :id";
                $stmt = $db->prepare($query);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->execute();
                return $stmt->fetch(PDO::FETCH_ASSOC) !== false;
            }
            ?>

            <hr>

            <?php
            session_start();
            $user = 'root';
            $pass = 'root';

            try {
                $db = new PDO('mysql:host=localhost;dbname=oasys', $user, $pass);

                $id_utilisateur = $_SESSION['id'];

                $sql_projets = "SELECT id_projet FROM projet
                INNER JOIN salarie ON projet.id_chefprojet = salarie.id_salarie
                WHERE salarie.id_user = :id_utilisateur";
                $stmt_projets = $db->prepare($sql_projets);
                $stmt_projets->bindParam(':id_utilisateur', $id_utilisateur, PDO::PARAM_INT);
                $stmt_projets->execute();
                $projets = $stmt_projets->fetchAll(PDO::FETCH_ASSOC);


                if ($projets) {
                    echo '<h1>Tableau des Étapes des Projets</h1>';
                    echo '<br>';
                    echo '<table border="1">';
                    echo '<tr><th>ID Étape</th><th>Libellé Étape</th><th>Date de Début</th><th>Date de Fin</th><th>Nom du Projet</th><th>Montant Total</th><th>ID Factures</th><th>Action</th></tr>';

                    foreach ($projets as $projet) {
                        $sql_etapes = "SELECT e.id_etape, e.libelleetape, e.datedebutetape, e.datefinetape, p.libelleproj
                            FROM etape e
                            INNER JOIN projet p ON e.id_projet = p.id_projet
                            WHERE e.id_projet = :id_projet";
                        $stmt_etapes = $db->prepare($sql_etapes);
                        $stmt_etapes->bindParam(':id_projet', $projet['id_projet'], PDO::PARAM_INT);
                        $stmt_etapes->execute();
                        $etapes = $stmt_etapes->fetchAll(PDO::FETCH_ASSOC);

                        foreach ($etapes as $etape) {
                            echo '<tr>';
                            echo '<td>' . $etape['id_etape'] . '</td>';
                            echo '<td>' . $etape['libelleetape'] . '</td>';
                            echo '<td>' . $etape['datedebutetape'] . '</td>';
                            echo '<td>' . $etape['datefinetape'] . '</td>';
                            echo '<td>' . $etape['libelleproj'] . '</td>';

                            $sql_sum_montant = "SELECT SUM(montant_total) AS total_montant FROM intervention WHERE id_etape = :etapeId";
                            $stmt_sum_montant = $db->prepare($sql_sum_montant);
                            $stmt_sum_montant->bindParam(':etapeId', $etape['id_etape'], PDO::PARAM_INT);
                            $stmt_sum_montant->execute();
                            $sum_montant = $stmt_sum_montant->fetch(PDO::FETCH_ASSOC);

                            $sql_id_factures = "SELECT id_facture FROM intervention WHERE id_etape = :etapeId";
                            $stmt_id_factures = $db->prepare($sql_id_factures);
                            $stmt_id_factures->bindParam(':etapeId', $etape['id_etape'], PDO::PARAM_INT);
                            $stmt_id_factures->execute();
                            $id_factures = $stmt_id_factures->fetchAll(PDO::FETCH_COLUMN);

                            echo '<td>' . number_format($sum_montant['total_montant'], 2) . ' €</td>';
                            echo '<td>' . implode(', ', $id_factures) . '</td>';
                            echo '<td><a href="modifier_etape.php?id_etape=' . $etape['id_etape'] . '">Modifier</a></td>';
                            echo '</tr>';
                        }
                    }
                    echo '</table>';
                    echo '<br>';

                    echo '<hr>';
                    echo '<h1>Créer une Nouvelle Étape</h1>';
                    echo '<form action="ajouter_etape.php" method="post">';
                    echo '<label for="libelleetape">Libellé de l\'Étape:</label>';
                    echo '<input type="text" id="libelleetape" name="libelleetape" required>';
                    echo '<br>';

                    echo '<label for="datedebutetape">Date de Début de l\'Étape:</label>';
                    echo '<input type="date" id="datedebutetape" name="datedebutetape" required>';
                    echo '<br>';

                    echo '<label for="datefinetape">Date de Fin de l\'Étape:</label>';
                    echo '<input type="date" id="datefinetape" name="datefinetape" required>';
                    echo '<br>';

                    echo '<label for="id_projet">Projet:</label>';
                    echo '<select id="id_projet" name="id_projet" required>';
                    $sql = "SELECT id_projet, libelleproj FROM projet
                    INNER JOIN salarie ON projet.id_chefprojet = salarie.id_salarie
                    WHERE salarie.id_user = :id_utilisateur";
                    $stmt = $db->prepare($sql);
                    $stmt->bindParam(':id_utilisateur', $id_utilisateur, PDO::PARAM_INT);
                    $stmt->execute();
                    $projets = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    foreach ($projets as $projet) {
                        echo "<option value='" . $projet["id_projet"] . "'>" . $projet["libelleproj"] . "</option>";
                    }
                    echo '</select>';
                    echo '<br>';
                    echo '<br>';
                    echo '<input type="submit" value="Ajouter l\'Étape">';
                    echo '</form>';
                } else {
                    $db = new PDO('mysql:host=localhost;dbname=oasys', $user, $pass);

                    $id_session = $_SESSION['id'];

                    $sql_interv_etapes = "SELECT DISTINCT e.id_etape, e.libelleetape, e.datedebutetape, e.datefinetape, p.libelleproj 
                    FROM etape e 
                    INNER JOIN projet p ON e.id_projet = p.id_projet 
                    WHERE e.id_etape IN (
                        SELECT DISTINCT id_etape FROM intervention 
                        WHERE 
                        (
                            (id_intervenant IS NOT NULL AND id_intervenant IN (
                                SELECT id_salarie FROM salarie WHERE id_user = :id_session 
                                UNION 
                                SELECT id_intervenantexterne FROM intervenant_externe WHERE id_user = :id_session
                            ))
                            OR
                            (id_intervenant IS NULL AND id_intervenantexterne IN (
                                SELECT id_intervenantexterne FROM intervenant_externe WHERE id_user = :id_session
                            ))
                        )
                    )";


                    $stmt_interv_etapes = $db->prepare($sql_interv_etapes);
                    $stmt_interv_etapes->bindParam(':id_session', $id_session, PDO::PARAM_INT);
                    $stmt_interv_etapes->execute();
                    $interv_etapes = $stmt_interv_etapes->fetchAll(PDO::FETCH_ASSOC);

                    if ($interv_etapes) {
                        echo '<h1>Tableau des Étapes associées aux Interventions</h1>';
                        echo '<br>';
                        echo '<table border="1" style="border-collapse: collapse; width: 100%;">';
                        echo '<tr><th>ID Étape</th><th>Libellé Étape</th><th>Date de Début</th><th>Date de Fin</th><th>Libellé Projet</th><th>Montant Total</th><th>ID Factures</th></tr>';

                        foreach ($interv_etapes as $interv_etape) {
                            $etapeId = $interv_etape['id_etape'];

                            $sql_sum_montant = "SELECT SUM(montant_total) AS total_montant FROM intervention WHERE id_etape = :etapeId";
                            $stmt_sum_montant = $db->prepare($sql_sum_montant);
                            $stmt_sum_montant->bindParam(':etapeId', $etapeId, PDO::PARAM_INT);
                            $stmt_sum_montant->execute();
                            $sum_montant = $stmt_sum_montant->fetch(PDO::FETCH_ASSOC);

                            $sql_id_factures = "SELECT id_facture FROM intervention WHERE id_etape = :etapeId";
                            $stmt_id_factures = $db->prepare($sql_id_factures);
                            $stmt_id_factures->bindParam(':etapeId', $etapeId, PDO::PARAM_INT);
                            $stmt_id_factures->execute();
                            $id_factures = $stmt_id_factures->fetchAll(PDO::FETCH_COLUMN);

                            echo '<tr>';
                            echo '<td style="padding: 5px;">' . $interv_etape['id_etape'] . '</td>';
                            echo '<td style="padding: 5px;">' . $interv_etape['libelleetape'] . '</td>';
                            echo '<td style="padding: 5px;">' . $interv_etape['datedebutetape'] . '</td>';
                            echo '<td style="padding: 5px;">' . $interv_etape['datefinetape'] . '</td>';
                            echo '<td style="padding: 5px;">' . $interv_etape['libelleproj'] . '</td>';
                            echo '<td style="padding: 5px;">' . number_format($sum_montant['total_montant'], 2) . ' €</td>';
                            echo '<td style="padding: 5px;">' . implode(', ', $id_factures) . '</td>';
                            echo '</tr>';
                        }
                        echo '</table>';
                    } else {
                        echo "Aucune etape n'a été trouvée pour cet utilisateur.";
                    }
                }


            } catch (PDOException $e) {
                echo 'Erreur de connexion à la base de données : ' . $e->getMessage();
            }
            ?>

            <hr>


            <?php
            session_start();
            $user = 'root';
            $pass = 'root';

            try {
                $db = new PDO('mysql:host=localhost;dbname=oasys', $user, $pass);

                $sql_interventions = "SELECT 
                i.id_intervention, 
                i.id_intervenant, 
                i.id_intervenantexterne, 
                i.datedebutint, 
                i.datefinint, 
                i.nbheure, 
                i.id_facture, 
                i.date_facture, 
                i.montant_total, 
                i.id_etape, 
                e.libelleetape, 
                p.libelleproj,
                i.memo_intervention, 
                i.id_status_intervention
            FROM intervention i
            INNER JOIN etape e ON i.id_etape = e.id_etape
            INNER JOIN projet p ON e.id_projet = p.id_projet
            WHERE e.id_projet = :id_projet";

                $stmt_interventions = $db->prepare($sql_interventions);
                $stmt_interventions->bindParam(':id_projet', $projet['id_projet'], PDO::PARAM_INT);
                $stmt_interventions->execute();
                $interventions = $stmt_interventions->fetchAll(PDO::FETCH_ASSOC);

                if ($interventions) {
                    echo '<h1>Tableau des Interventions par Étapes</h1>';
                    echo '<br>';
                    echo '<table border="1" style="border-collapse: collapse; width: 100%;">';
                    echo '<tr><th>ID Intervention</th><th>ID Intervenant</th><th>ID Intervenant Externe</th><th>Nom Intervenant</th><th>Prénom Intervenant</th><th>Date de Début</th><th>Date de Fin</th><th>Nombre d\'Heures</th><th>ID Facture</th><th>Date Facture</th><th>Montant Total</th><th>ID Étape</th><th>Libellé Étape</th><th>Libellé Projet</th><th>Taux Horaire Projet</th><th>Memo Intervention</th><th>ID Status Intervention</th><th>Status Intervention</th><th>Action Intervention</th></tr>';

                    foreach ($interventions as $intervention) {
                        $sql_intervenant_info = '';
                        if (!is_null($intervention['id_intervenantexterne'])) {
                            // C'est un intervenant externe
                            $intervenantId = $intervention['id_intervenantexterne'];
                            $sql_intervenant_info = "SELECT nom, prenom FROM intervenant_externe WHERE id_intervenantexterne = :intervenantId";
                        } else {
                            // C'est un salarié
                            $intervenantId = $intervention['id_intervenant'];
                            $sql_intervenant_info = "SELECT nom, prenom FROM salarie WHERE id_salarie = :intervenantId";
                        }

                        $stmt_intervenant_info = $db->prepare($sql_intervenant_info);
                        $stmt_intervenant_info->bindParam(':intervenantId', $intervenantId, PDO::PARAM_INT);
                        $stmt_intervenant_info->execute();
                        $intervenant_info = $stmt_intervenant_info->fetch(PDO::FETCH_ASSOC);

                        $nom_intervenant = $intervenant_info['nom'];
                        $prenom_intervenant = $intervenant_info['prenom'];
                        $sql_etape_info = "SELECT libelleetape, id_projet FROM etape WHERE id_etape = :id_etape";
                        $stmt_etape_info = $db->prepare($sql_etape_info);
                        $stmt_etape_info->bindParam(':id_etape', $intervention['id_etape'], PDO::PARAM_INT);
                        $stmt_etape_info->execute();
                        $etape_info = $stmt_etape_info->fetch(PDO::FETCH_ASSOC);

                        $sql_projet_info = "SELECT libelleproj FROM projet WHERE id_projet = :id_projet";
                        $stmt_projet_info = $db->prepare($sql_projet_info);
                        $stmt_projet_info->bindParam(':id_projet', $etape_info['id_projet'], PDO::PARAM_INT);
                        $stmt_projet_info->execute();
                        $projet_info = $stmt_projet_info->fetch(PDO::FETCH_ASSOC);

                        $libelle_etape = $etape_info['libelleetape'];
                        $libelle_projet = $projet_info['libelleproj'];

                        $sql_statut_intervention = "SELECT description FROM status_intervention WHERE id_status_intervention = :id_status_intervention";
                        $stmt_statut_intervention = $db->prepare($sql_statut_intervention);
                        $stmt_statut_intervention->bindParam(':id_status_intervention', $intervention['id_status_intervention'], PDO::PARAM_INT);
                        $stmt_statut_intervention->execute();
                        $statut_intervention = $stmt_statut_intervention->fetch(PDO::FETCH_ASSOC);

                        $description_statut_intervention = $statut_intervention['description'];

                        $sql_taux_horaire = "SELECT taux_horaire FROM projet WHERE id_projet = :id_projet";
                        $stmt_taux_horaire = $db->prepare($sql_taux_horaire);
                        $stmt_taux_horaire->bindParam(':id_projet', $etape_info['id_projet'], PDO::PARAM_INT);
                        $stmt_taux_horaire->execute();
                        $taux_horaire = $stmt_taux_horaire->fetch(PDO::FETCH_ASSOC);
                        $taux_horaire_projet = $taux_horaire['taux_horaire'];

                        if (isset($_POST['calculer_montant'])) {
                            $nb_heures = (float) $intervention['nbheure'];
                            $taux_horaire = (float) $taux_horaire_projet;
                            $montant_total = $nb_heures * $taux_horaire;

                            $updateMontantTotalSql = "UPDATE intervention SET montant_total = :montant_total WHERE id_intervention = :id_intervention";
                            $updateMontantTotalStmt = $db->prepare($updateMontantTotalSql);
                            $updateMontantTotalStmt->bindParam(':montant_total', $montant_total, PDO::PARAM_STR);
                            $updateMontantTotalStmt->bindParam(':id_intervention', $intervention['id_intervention'], PDO::PARAM_INT);
                            $updateMontantTotalStmt->execute();

                            $sql_select_montant_total = "SELECT montant_total FROM intervention WHERE id_intervention = :id_intervention";
                            $stmt_select_montant_total = $db->prepare($sql_select_montant_total);
                            $stmt_select_montant_total->bindParam(':id_intervention', $intervention['id_intervention'], PDO::PARAM_INT);
                            $stmt_select_montant_total->execute();
                            $montant_total = $stmt_select_montant_total->fetchColumn();
                            header('Location: http://localhost/');

                        }

                        echo '<tr>';
                        echo '<td style="padding: 5px;">' . $intervention['id_intervention'] . '</td>';
                        echo '<td style="padding: 5px;">' . $intervention['id_intervenant'] . '</td>';
                        echo '<td style="padding: 5px;">' . $intervention['id_intervenantexterne'] . '</td>'; // Nouvelle colonne
                        echo '<td style="padding: 5px;">' . $nom_intervenant . '</td>';
                        echo '<td style="padding: 5px;">' . $prenom_intervenant . '</td>';
                        echo '<td style="padding: 5px;">' . $intervention['datedebutint'] . '</td>';
                        echo '<td style="padding: 5px;">' . $intervention['datefinint'] . '</td>';
                        echo '<td style="padding: 5px;">' . $intervention['nbheure'] . '</td>';
                        echo '<td style="padding: 5px;">' . $intervention['id_facture'] . '</td>';
                        echo '<td style="padding: 5px;">' . $intervention['date_facture'] . '</td>';
                        echo '<td style="padding: 5px;">' . $intervention['montant_total'] . '</td>';
                        echo '<td style="padding: 5px;">' . $intervention['id_etape'] . '</td>';
                        echo '<td style="padding: 5px;">' . $libelle_etape . '</td>';
                        echo '<td style="padding: 5px;">' . $libelle_projet . '</td>';
                        echo '<td style="padding: 5px;">' . $taux_horaire_projet . '</td>';
                        echo '<td style="padding: 5px; white-space: pre-wrap; overflow-wrap: break-word;">' . nl2br($intervention['memo_intervention']) . '</td>';
                        echo '<td style="padding: 5px;">' . $intervention['id_status_intervention'] . '</td>';
                        echo '<td style="padding: 5px;">' . $description_statut_intervention . '</td>';
                        echo '<td style="padding: 5px;"><a href="modification_intervention.php?id=' . $intervention['id_intervention'] . '">Modifier</a></td>';
                        echo '<td style="padding: 5px;"><a href="suppression_intervention.php?id=' . $intervention['id_intervention'] . '">Supprimer</a></td>';
                        echo '</tr>';

                    }

                    echo '</table>';
                    echo '<br>';
                    echo '<form method="post" action="">';
                    echo '<input type="submit" name="calculer_montant" value="Calculer Montant Total" />';
                    echo '</form>';


                } else {

                    session_start();
                    $user = 'root';
                    $pass = 'root';

                    try {
                        $db = new PDO('mysql:host=localhost;dbname=oasys', $user, $pass);

                        $id_session = $_SESSION['id'];

                        $sql_interventions = "SELECT * FROM intervention 
                            WHERE 
                                ((id_intervenant IS NOT NULL AND id_intervenant IN (
                                    SELECT id_salarie FROM salarie WHERE id_user = :id_session 
                                    UNION 
                                    SELECT id_intervenantexterne FROM intervenant_externe WHERE id_user = :id_session
                                ))
                                OR
                                (id_intervenant IS NULL AND id_intervenantexterne IN (
                                    SELECT id_intervenantexterne FROM intervenant_externe WHERE id_user = :id_session
                                )))";

                        $stmt_interventions = $db->prepare($sql_interventions);
                        $stmt_interventions->bindParam(':id_session', $id_session, PDO::PARAM_INT);
                        $stmt_interventions->execute();
                        $interventions = $stmt_interventions->fetchAll(PDO::FETCH_ASSOC);

                        if ($interventions) {
                            echo '<h1>Tableau des Interventions par Étapes</h1>';
                            echo '<br>';
                            echo '<table border="1" style="border-collapse: collapse; width: 100%;">';
                            echo '<tr><th>ID Intervention</th><th>ID Intervenant</th><th>ID Intervenant Externe</th><th>Nom Intervenant</th><th>Prénom Intervenant</th><th>Date de Début</th><th>Date de Fin</th><th>Nombre d\'Heures</th><th>ID Facture</th><th>Date Facture</th><th>Montant Total</th><th>ID Étape</th><th>Libellé Étape</th><th>Libellé Projet</th><th>Taux Horaire Projet</th><th>Memo Intervention</th><th>ID Status Intervention</th><th>Status Intervention</th><th>Action Intervention</th></tr>';

                            foreach ($interventions as $intervention) {
                                $sql_intervenant_info = '';
                                $intervenantId = null;

                                if (!empty($intervention['id_intervenant'])) {
                                    $intervenantId = $intervention['id_intervenant'];
                                    if (strpos($intervention['id_intervenant'], 'Externe:') === 0) {
                                        // C'est un intervenant externe
                                        $intervenantId = substr($intervention['id_intervenant'], 8);
                                        $sql_intervenant_info = "SELECT nom, prenom FROM intervenant_externe WHERE id_intervenantexterne = :intervenantId";
                                    } else {
                                        // C'est un salarié
                                        $sql_intervenant_info = "SELECT nom, prenom FROM salarie WHERE id_salarie = :intervenantId";
                                    }
                                } elseif (!empty($intervention['id_intervenantexterne'])) {
                                    $intervenantId = $intervention['id_intervenantexterne'];
                                    $sql_intervenant_info = "SELECT nom, prenom FROM intervenant_externe WHERE id_intervenantexterne = :intervenantId";
                                }

                                $stmt_intervenant_info = $db->prepare($sql_intervenant_info);
                                $stmt_intervenant_info->bindParam(':intervenantId', $intervenantId, PDO::PARAM_INT);
                                $stmt_intervenant_info->execute();
                                $intervenant_info = $stmt_intervenant_info->fetch(PDO::FETCH_ASSOC);

                                $nom_intervenant = $intervenant_info['nom'];
                                $prenom_intervenant = $intervenant_info['prenom'];

                                $stmt_intervenant_info = $db->prepare($sql_intervenant_info);
                                $stmt_intervenant_info->bindParam(':intervenantId', $intervenantId, PDO::PARAM_INT);
                                $stmt_intervenant_info->execute();
                                $intervenant_info = $stmt_intervenant_info->fetch(PDO::FETCH_ASSOC);

                                $nom_intervenant = $intervenant_info['nom'];
                                $prenom_intervenant = $intervenant_info['prenom'];
                                $sql_etape_info = "SELECT libelleetape, id_projet FROM etape WHERE id_etape = :id_etape";
                                $stmt_etape_info = $db->prepare($sql_etape_info);
                                $stmt_etape_info->bindParam(':id_etape', $intervention['id_etape'], PDO::PARAM_INT);
                                $stmt_etape_info->execute();
                                $etape_info = $stmt_etape_info->fetch(PDO::FETCH_ASSOC);

                                // Récupérez le libellé du projet
                                $sql_projet_info = "SELECT libelleproj FROM projet WHERE id_projet = :id_projet";
                                $stmt_projet_info = $db->prepare($sql_projet_info);
                                $stmt_projet_info->bindParam(':id_projet', $etape_info['id_projet'], PDO::PARAM_INT);
                                $stmt_projet_info->execute();
                                $projet_info = $stmt_projet_info->fetch(PDO::FETCH_ASSOC);

                                $libelle_etape = $etape_info['libelleetape'];
                                $libelle_projet = $projet_info['libelleproj'];

                                $sql_statut_intervention = "SELECT description FROM status_intervention WHERE id_status_intervention = :id_status_intervention";
                                $stmt_statut_intervention = $db->prepare($sql_statut_intervention);
                                $stmt_statut_intervention->bindParam(':id_status_intervention', $intervention['id_status_intervention'], PDO::PARAM_INT);
                                $stmt_statut_intervention->execute();
                                $statut_intervention = $stmt_statut_intervention->fetch(PDO::FETCH_ASSOC);

                                $description_statut_intervention = $statut_intervention['description'];

                                // Récupérez le taux horaire du projet
                                $sql_taux_horaire = "SELECT taux_horaire FROM projet WHERE id_projet = :id_projet";
                                $stmt_taux_horaire = $db->prepare($sql_taux_horaire);
                                $stmt_taux_horaire->bindParam(':id_projet', $etape_info['id_projet'], PDO::PARAM_INT);
                                $stmt_taux_horaire->execute();
                                $taux_horaire = $stmt_taux_horaire->fetch(PDO::FETCH_ASSOC);
                                $taux_horaire_projet = $taux_horaire['taux_horaire'];

                                if (isset($_POST['calculer_montant'])) {
                                    $nb_heures = (float) $intervention['nbheure'];
                                    $taux_horaire = (float) $taux_horaire_projet;
                                    $montant_total = $nb_heures * $taux_horaire;

                                    $updateMontantTotalSql = "UPDATE intervention SET montant_total = :montant_total WHERE id_intervention = :id_intervention";
                                    $updateMontantTotalStmt = $db->prepare($updateMontantTotalSql);
                                    $updateMontantTotalStmt->bindParam(':montant_total', $montant_total, PDO::PARAM_STR);
                                    $updateMontantTotalStmt->bindParam(':id_intervention', $intervention['id_intervention'], PDO::PARAM_INT);
                                    $updateMontantTotalStmt->execute();

                                    $sql_select_montant_total = "SELECT montant_total FROM intervention WHERE id_intervention = :id_intervention";
                                    $stmt_select_montant_total = $db->prepare($sql_select_montant_total);
                                    $stmt_select_montant_total->bindParam(':id_intervention', $intervention['id_intervention'], PDO::PARAM_INT);
                                    $stmt_select_montant_total->execute();
                                    $montant_total = $stmt_select_montant_total->fetchColumn();
                                    header('Location: http://localhost/');
                                }

                                echo '<tr>';
                                echo '<td style="padding: 5px;">' . $intervention['id_intervention'] . '</td>';
                                echo '<td style="padding: 5px;">' . $intervention['id_intervenant'] . '</td>';
                                echo '<td style="padding: 5px;">' . $intervention['id_intervenantexterne'] . '</td>'; // Nouvelle colonne
                                echo '<td style="padding: 5px;">' . $nom_intervenant . '</td>';
                                echo '<td style="padding: 5px;">' . $prenom_intervenant . '</td>';
                                echo '<td style="padding: 5px;">' . $intervention['datedebutint'] . '</td>';
                                echo '<td style="padding: 5px;">' . $intervention['datefinint'] . '</td>';
                                echo '<td style="padding: 5px;">' . $intervention['nbheure'] . '</td>';
                                echo '<td style="padding: 5px;">' . $intervention['id_facture'] . '</td>';
                                echo '<td style="padding: 5px;">' . $intervention['date_facture'] . '</td>';
                                echo '<td style="padding: 5px;">' . $intervention['montant_total'] . '</td>';
                                echo '<td style="padding: 5px;">' . $intervention['id_etape'] . '</td>';
                                echo '<td style="padding: 5px;">' . $libelle_etape . '</td>';
                                echo '<td style="padding: 5px;">' . $libelle_projet . '</td>';
                                echo '<td style="padding: 5px;">' . $taux_horaire_projet . '</td>';
                                echo '<td style="padding: 5px; white-space: pre-wrap; overflow-wrap: break-word;">' . nl2br($intervention['memo_intervention']) . '</td>';
                                echo '<td style="padding: 5px;">' . $intervention['id_status_intervention'] . '</td>';
                                echo '<td style="padding: 5px;">' . $description_statut_intervention . '</td>';
                                echo '<td style="padding: 5px;"><a href="modification_intervention.php?id=' . $intervention['id_intervention'] . '">Modifier</a></td>';
                                echo '</tr>';
                            }

                            echo '</table>';
                            echo '<br>';
                            echo '<form method="post" action="">';
                            echo '<input type="submit" name="calculer_montant" value="Calculer Montant Total" />';
                            echo '</form>';

                        } else {
                            echo "Aucune intervention n'a été trouvée pour cet utilisateur.";
                        }
                    } catch (PDOException $e) {
                        echo 'Erreur de connexion à la base de données : ' . $e->getMessage();
                    }
                }
                echo '</table>';

            } catch (PDOException $e) {
                echo 'Erreur de connexion à la base de données : ' . $e->getMessage();
            }
            ?>


            <?php
            session_start();
            $user = 'root';
            $pass = 'root';

            try {
                $db = new PDO('mysql:host=localhost;dbname=oasys', $user, $pass);

                $id_utilisateur = $_SESSION['id'];

                $sql_projets = "SELECT id_projet, libelleproj FROM projet
    INNER JOIN salarie ON projet.id_chefprojet = salarie.id_salarie
    WHERE salarie.id_user = :id_utilisateur";
                $stmt_projets = $db->prepare($sql_projets);
                $stmt_projets->bindParam(':id_utilisateur', $id_utilisateur, PDO::PARAM_INT);
                $stmt_projets->execute();
                $projets = $stmt_projets->fetchAll(PDO::FETCH_ASSOC);

                if ($projets) {
                    ?>
                    <title>Création d'une Intervention</title>

                    <body>
                        <form action="traitement_intervention.php" method="post">
                            <label for="projet">Projet :</label>
                            <select name="projet" id="projet">
                                <?php
                                foreach ($projets as $projet) {
                                    echo '<option value="' . $projet['id_projet'] . '">' . $projet['libelleproj'] . '</option>';
                                }
                                ?>
                            </select><br>
                            <label for="etape">Étape :</label>
                            <select name="etape" id="etape">
                                <?php
                                foreach ($etapes as $etape) {
                                    echo '<option value="' . $etape['id_etape'] . '">' . $etape['libelleetape'] . '</option>';
                                }
                                ?>
                            </select><br>
                            <label for="id_intervenant">ID de l'Intervenant :</label>
                            <select name="id_intervenant" id="id_intervenant" required>
                                <?php
                                $sql_externe_info = "SELECT id_intervenantexterne, nom, prenom FROM intervenant_externe";
                                $stmt_externe_info = $db->query($sql_externe_info);
                                $intervenants_externes_info = $stmt_externe_info->fetchAll(PDO::FETCH_ASSOC);

                                $sql_salarie_info = "SELECT id_salarie, nom, prenom FROM salarie";
                                $stmt_salarie_info = $db->query($sql_salarie_info);
                                $salaries_info = $stmt_salarie_info->fetchAll(PDO::FETCH_ASSOC);

                                echo '<optgroup label="Intervenants">';
                                foreach ($intervenants_externes_info as $externe) {
                                    echo '<option value="Externe:' . $externe['id_intervenantexterne'] . '">' . $externe['id_intervenantexterne'] . ' (Externe) - ' . $externe['nom'] . ' ' . $externe['prenom'] . '</option>';
                                }
                                echo '</optgroup';

                                echo '<optgroup label="Salariés">';
                                foreach ($salaries_info as $salarie) {
                                    echo '<option value="' . $salarie['id_salarie'] . '">' . $salarie['id_salarie'] . ' (Salarié) - ' . $salarie['nom'] . ' ' . $salarie['prenom'] . '</option>';
                                }
                                echo '</optgroup>';
                                ?>
                            </select><br>

                            <input type="submit" value="Créer Intervention">
                        </form>
                    </body>
                    <?php
                }
            } catch (PDOException $e) {
                echo 'Erreur de connexion à la base de données : ' . $e->getMessage();
            }
            ?>






    </section>

    </div>
    </div>

    <footer class="bg-secondary pt-4">
        <div class="container">
            <div class="row py-4">

                <div class="col-lg-3 col-12 align-left">
                    <a class="navbar-brand" href="index.html">
                        <i class='bx bx-buildings bx-sm text-light'></i>
                        <span class="text-light h5">Purple</span> <span class="text-light h5 semi-bold-600">Buzz</span>
                    </a>
                    <p class="text-light my-lg-4 my-2">
                        Lorem ipsum dolor sit amet, consectetur adipisicing elit,
                        sed do eiusmod tempor incididunt ut.
                    </p>
                    <ul class="list-inline footer-icons light-300">
                        <li class="list-inline-item m-0">
                            <a class="text-light" target="_blank" href="http://facebook.com/">
                                <i class='bx bxl-facebook-square bx-md'></i>
                            </a>
                        </li>
                        <li class="list-inline-item m-0">
                            <a class="text-light" target="_blank" href="https://www.linkedin.com/">
                                <i class='bx bxl-linkedin-square bx-md'></i>
                            </a>
                        </li>
                        <li class="list-inline-item m-0">
                            <a class="text-light" target="_blank" href="https://www.whatsapp.com/">
                                <i class='bx bxl-whatsapp-square bx-md'></i>
                            </a>
                        </li>
                        <li class="list-inline-item m-0">
                            <a class="text-light" target="_blank" href="https://www.flickr.com/">
                                <i class='bx bxl-flickr-square bx-md'></i>
                            </a>
                        </li>
                        <li class="list-inline-item m-0">
                            <a class="text-light" target="_blank" href="https://www.medium.com/">
                                <i class='bx bxl-medium-square bx-md'></i>
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="col-lg-3 col-md-4 my-sm-0 mt-4">
                    <h3 class="h4 pb-lg-3 text-light light-300">Our Company</h2>
                        <ul class="list-unstyled text-light light-300">
                            <li class="pb-2">
                                <i class='bx-fw bx bxs-chevron-right bx-xs'></i><a
                                    class="text-decoration-none text-light" href="index.html">Home</a>
                            </li>
                            <li class="pb-2">
                                <i class='bx-fw bx bxs-chevron-right bx-xs'></i><a
                                    class="text-decoration-none text-light py-1" href="about.html">About Us</a>
                            </li>
                            <li class="pb-2">
                                <i class='bx-fw bx bxs-chevron-right bx-xs'></i><a
                                    class="text-decoration-none text-light py-1" href="work.html">Work</a>
                            </li>
                            <li class="pb-2">
                                <i class='bx-fw bx bxs-chevron-right bx-xs'></i></i><a
                                    class="text-decoration-none text-light py-1" href="pricing.html">Price</a>
                            </li>
                            <li class="pb-2">
                                <i class='bx-fw bx bxs-chevron-right bx-xs'></i><a
                                    class="text-decoration-none text-light py-1" href="contact.html">Contact</a>
                            </li>
                        </ul>
                </div>

                <div class="col-lg-3 col-md-4 my-sm-0 mt-4">
                    <h2 class="h4 pb-lg-3 text-light light-300">Our Works</h2>
                    <ul class="list-unstyled text-light light-300">
                        <li class="pb-2">
                            <i class='bx-fw bx bxs-chevron-right bx-xs'></i><a
                                class="text-decoration-none text-light py-1" href="#">Branding</a>
                        </li>
                        <li class="pb-2">
                            <i class='bx-fw bx bxs-chevron-right bx-xs'></i><a
                                class="text-decoration-none text-light py-1" href="#">Business</a>
                        </li>
                        <li class="pb-2">
                            <i class='bx-fw bx bxs-chevron-right bx-xs'></i><a
                                class="text-decoration-none text-light py-1" href="#">Marketing</a>
                        </li>
                        <li class="pb-2">
                            <i class='bx-fw bx bxs-chevron-right bx-xs'></i><a
                                class="text-decoration-none text-light py-1" href="#">Social Media</a>
                        </li>
                        <li class="pb-2">
                            <i class='bx-fw bx bxs-chevron-right bx-xs'></i><a
                                class="text-decoration-none text-light py-1" href="#">Digital Solution</a>
                        </li>
                        <li class="pb-2">
                            <i class='bx-fw bx bxs-chevron-right bx-xs'></i><a
                                class="text-decoration-none text-light py-1" href="#">Graphic</a>
                        </li>
                    </ul>
                </div>

                <div class="col-lg-3 col-md-4 my-sm-0 mt-4">
                    <h2 class="h4 pb-lg-3 text-light light-300">For Client</h2>
                    <ul class="list-unstyled text-light light-300">
                        <li class="pb-2">
                            <i class='bx-fw bx bx-phone bx-xs'></i><a class="text-decoration-none text-light py-1"
                                href="tel:010-020-0340">010-020-0340</a>
                        </li>
                        <li class="pb-2">
                            <i class='bx-fw bx bx-mail-send bx-xs'></i><a class="text-decoration-none text-light py-1"
                                href="mailto:info@company.com">info@company.com</a>
                        </li>
                    </ul>
                </div>

            </div>
        </div>

        <div class="w-100 bg-primary py-3">
            <div class="container">
                <div class="row pt-2">
                    <div class="col-lg-6 col-sm-12">
                        <p class="text-lg-start text-center text-light light-300">
                            © Copyright 2021 Purple Buzz Company. All Rights Reserved.
                        </p>
                    </div>
                    <div class="col-lg-6 col-sm-12">
                        <p class="text-lg-end text-center text-light light-300">
                        </p>
                    </div>
                </div>
            </div>
        </div>

    </footer>

</body>

</html>