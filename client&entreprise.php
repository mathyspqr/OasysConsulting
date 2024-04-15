<!DOCTYPE html>
<html lang="fr">

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
                            <a class="nav-link btn-outline-primary rounded-pill px-3" href="work.php">Projet</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link btn-outline-primary rounded-pill px-3" href="contact.php">Salarie &
                                Intervenant Externe</a>
                        </li>

                        <?php
                        session_start();
                        $user = 'root';
                        $pass = 'root';

                        try {
                            $db = new PDO('mysql:host=localhost;dbname=oasys', $user, $pass);

                            if (isset($_SESSION['id'])) {
                                $email = $_SESSION['email'];
                                $query = "SELECT id FROM user WHERE email = :email";
                                $stmt = $db->prepare($query);
                                $stmt->bindParam(':email', $email, PDO::PARAM_STR);
                                $stmt->execute();
                                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                                $querySalarie = "SELECT * FROM salarie WHERE id_user = :id_user";
                                $stmtSalarie = $db->prepare($querySalarie);
                                $stmtSalarie->bindParam(':id_user', $user['id'], PDO::PARAM_INT);
                                $stmtSalarie->execute();
                                $isSalarie = $stmtSalarie->fetch(PDO::FETCH_ASSOC);

                                if ($isSalarie) {
                                    echo '<li class="nav-item">
                    <a class="nav-link btn-outline-primary rounded-pill px-3" href="pricing.php">Utilisateur</a>
                  </li>';
                                }
                            }
                        } catch (PDOException $e) {
                            echo "Erreur : " . $e->getMessage();
                        }
                        ?>
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
                <h1>Personne Physique</h1>
                <?php
                session_start();

                $user = 'root';
                $pass = 'root';

                try {
                    $db = new PDO('mysql:host=localhost;dbname=oasys', $user, $pass);

                    $query = "SELECT p.*, u.email 
        FROM personne p
        INNER JOIN user u ON p.id_user = u.id";
                    $stmt = $db->query($query);

                    echo '<table class="table">';
                    echo '<thead>';
                    echo '<tr>';
                    echo '<th>ID</th>';
                    echo '<th>Nom</th>';
                    echo '<th>Prénom</th>';
                    echo '<th>Date de naissance</th>';
                    echo '<th>Pays</th>';
                    echo '<th>Téléphone</th>';
                    echo '<th>Profession</th>';
                    echo '<th>Nationalité</th>';
                    echo '<th>Adresse e-mail</th>';
                    echo '<th>Actions</th>';
                    echo '</tr>';
                    echo '</thead>';
                    echo '<tbody>';

                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo '<tr>';
                        echo '<td>' . $row['id_personne'] . '</td>';
                        echo '<td>' . $row['nom'] . '</td>';
                        echo '<td>' . $row['prenom'] . '</td>';
                        echo '<td>' . $row['date_naissance'] . '</td>';
                        echo '<td>' . $row['pays'] . '</td>';
                        echo '<td>' . $row['telephone'] . '</td>';
                        echo '<td>' . $row['profession'] . '</td>';
                        echo '<td>' . $row['nationalite'] . '</td>';
                        echo '<td>' . $row['email'] . '</td>';

                        // Vérifier si l'utilisateur est également une entreprise, un intervenant externe et un salarié
                        $isEntreprise = isUserType($db, $_SESSION['id'], 'entreprise');
                        $isIntervenantExterne = isUserType($db, $_SESSION['id'], 'intervenant_externe');
                        $isSalarie = isUserType($db, $_SESSION['id'], 'salarie');

                        // Si toutes les conditions sont remplies, afficher le lien "Modifier"
                        if ($isEntreprise && $isIntervenantExterne && $isSalarie) {
                            echo '<td><a href="modifier_personne_physique.php?id=' . $row['id_personne'] . '">Modifier</a></td>';
                        } else {
                            echo '<td></td>';
                        }

                        echo '</tr>';
                    }
                    echo '</tbody>';
                    echo '</table>';
                } catch (PDOException $e) {
                    echo "Erreur : " . $e->getMessage();
                }
                ?>
            </div>


            <div class="row align-items-center justify-content-between">
    <h1>Entreprise</h1>
    <?php
    session_start();

    $user = 'root';
    $pass = 'root';

    try {
        $db = new PDO('mysql:host=localhost;dbname=oasys', $user, $pass);

        $query = "SELECT e.*, u.email 
            FROM entreprise e
            INNER JOIN user u ON e.id_user = u.id";
        $stmt = $db->query($query);

        echo '<table class="table">';
        echo '<thead>';
        echo '<tr>';
        echo '<th>ID</th>';
        echo '<th>Nom</th>';
        echo '<th>Prénom</th>';
        echo '<th>SIRET</th>';
        echo '<th>Raison Sociale</th>';
        echo '<th>Adresse e-mail</th>';
        echo '<th>Actions</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo '<tr>';
            echo '<td>' . $row['id_entreprise'] . '</td>';
            echo '<td>' . $row['nom'] . '</td>';
            echo '<td>' . $row['prenom'] . '</td>';
            echo '<td>' . $row['siret'] . '</td>';
            echo '<td>' . $row['raisonsociale'] . '</td>';
            echo '<td>' . $row['email'] . '</td>';

            // Vérifier si l'utilisateur est également une "Personne Physique," un "Intervenant Externe," et un "Salarié"
            $isPersonnePhysique = isUserType($db, $_SESSION['id'], 'personne');
            $isIntervenantExterne = isUserType($db, $_SESSION['id'], 'intervenant_externe');
            $isSalarie = isUserType($db, $_SESSION['id'], 'salarie');

            // Si toutes les conditions sont remplies, afficher le lien "Modifier"
            if ($isPersonnePhysique && $isIntervenantExterne && $isSalarie) {
                echo '<td><a href="modifier_entreprise.php?id=' . $row['id_entreprise'] . '">Modifier</a></td>';
            } else {
                echo '<td></td>';
            }

            echo '</tr>';
        }
        echo '</tbody>';
        echo '</table>';
    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }


// Fonction pour vérifier si un utilisateur est d'un certain type (personne, intervenant_externe, salarié)
function isUserType($db, $id, $type)
{
    $query = "SELECT * FROM $type WHERE id_user = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC) !== false;
}
?>
            </div>


            <br>

        </div>

        <footer class="bg-secondary pt-4">
            <div class="container">
                <div class="row py-4">

                    <div class="col-lg-3 col-12 align-left">
                        <a class="navbar-brand" href="index.html">
                            <i class='bx bx-buildings bx-sm text-light'></i>
                            <span class="text-light h5">Purple</span> <span
                                class="text-light h5 semi-bold-600">Buzz</span>
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
                                <i class='bx-fw bx bx-mail-send bx-xs'></i><a
                                    class="text-decoration-none text-light py-1"
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


        <script src="assets/js/bootstrap.bundle.min.js"></script>
        <script src="assets/js/jquery.min.js"></script>
        <script src="assets/js/isotope.pkgd.js"></script>
        <script>
            $(window).load(function () {
                // init Isotope
                var $projects = $('.projects').isotope({
                    itemSelector: '.project',
                    layoutMode: 'fitRows'
                });
                $(".filter-btn").click(function () {
                    var data_filter = $(this).attr("data-filter");
                    $projects.isotope({
                        filter: data_filter
                    });
                    $(".filter-btn").removeClass("active");
                    $(".filter-btn").removeClass("shadow");
                    $(this).addClass("active");
                    $(this).addClass("shadow");
                    return false;
                });
            });
        </script>
        <script src="assets/js/templatemo.js"></script>
        <script src="assets/js/custom.js"></script>

</body>

</html>