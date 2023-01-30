<?php

use App\AgoraScript\Config\Conf;
use App\AgoraScript\Lib\ConnexionUtilisateur;
use App\AgoraScript\Lib\MessageFlash;
use App\AgoraScript\Model\HTTP\Session;

if (!isset($url)) {
    $url = Conf::getUrlBase();
}
?>

<!DOCTYPE html>
<html lang="fr" class="h-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $pagetitle; ?></title>
    <link href="css/styles/bootstrap.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.css">
</head>
<body class="bg-image d-flex flex-column h-100">
<header class="fw-bold h-27 d-flex align-items-end justify-content-center">
    <div class="alert alert-success d-flex px-5 position-relative opacity-0 mb-5 top-40\"></div>
    <nav class="navbar fixed-top navbar-expand-lg navbar-light p-md-3 bg-image">
        <div id="navPC" class="container-fluid">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item"><a href="<?php echo $url; ?>frontController.php" class='nav-link active'>PollEmploi</a>
                <?php
                //partie de gauche seulement visible pour un utilisateur connecté
                if (ConnexionUtilisateur::estConnecte()) {
                    echo "<li class=\"nav-item\"><a href=\"$url" . "frontController.php?controller=question&action=readAll&etat=0\" class=\"nav-link active\">Questions</a></li>";
                    echo "<li class=\"nav-item\"><a href=\"$url" . "frontController.php?controller=question&action=readAll&etat=3\" class=\"nav-link active\">Commentaires</a></li>";
                    echo "<li class=\"nav-item\"><a href=\"$url" . "frontController.php?controller=question&action=readAll&etat=5\" class=\"nav-link active\">Propositions 2nd phase</a></li>";
                    echo "<li class=\"nav-item\"><a href=\"$url" . "frontController.php?controller=question&action=readAll&etat=7\" class=\"nav-link active\">Votes</a></li>";
                    echo "<li class=\"nav-item\"><a href=\"$url" . "frontController.php?controller=utilisateur&action=readMyQuestions\" class=\"nav-link active\">Mes questions</a></li>";
                }
                ?>
                <?php
                if (ConnexionUtilisateur::estAdministrateur()) {
                    echo "<li class=\"nav-item\"><a href=\"$url" . "frontController.php?controller=utilisateur&action=readAll\" class=\"nav-link active\">Utilisateurs</a></li>";
                }
                ?>
                <li class="nav-item"><a
                            href="<?php echo $url; ?>frontController.php?controller=question&action=readAll&etat=8"
                            class="nav-link active">Résultats</a></li>
            </ul>

            <?php
            //boutons de management de comptes
            if (!ConnexionUtilisateur::estConnecte()) {
                echo "<a href=\"$url" . "frontController.php?controller=utilisateur&action=connect\" class=\"nav-link active\">Se connecter</a>";
                echo "<a href=\"$url" . "frontController.php?controller=utilisateur&action=create\"  class=\"nav-link active mx-4\">S'inscrire</a>";
            } else {
                $login = ConnexionUtilisateur::getLoginUtilisateurConnecte();
                $loginURL = urlencode($login);

                if (!ConnexionUtilisateur::estAdministrateur()) {
                    echo "<a href=\"$url" . "frontController.php?controller=utilisateur&action=demandeRole&login=$loginURL\">Demande d'affectation</a>";
                }
                echo "<a href=\"$url" . "frontController.php?controller=utilisateur&action=read&login=$loginURL\"  class=\"nav-link active ms-4\"><img src=\"./img/user.png\" alt=\"informations\" title=\"enter\"></a>";
                echo "<a href=\"$url" . "frontController.php?controller=utilisateur&action=deconnecter\" class=\"nav-link active mx-4\"><img src=\"./img/logout.png\" alt=\"logout\" title=\"out\"></a>";
            }
            ?>
        </div>
        <!--affichage de la navbar pour mobile-->
        <div id="navMobile" class="flex-column mx-3">
            <div id="burger" class="d-none">
                <img alt="burger" width="50" height="50" src="./img/burger.png">
                <div id="menu_deroulant" class="navbar-nav d-none">
                    <div class="nav-item"><a
                                href="<?php echo $url; ?>frontController.php?controller=question&action=readAll&etat=8"
                                class="nav-link active">Résultats</a></div>
                    <?php
                    if (ConnexionUtilisateur::estConnecte()) {
                        echo "<div class=\"nav-item\"><a href=\"$url" . "frontController.php?controller=question&action=readAll&etat=0\" class=\"nav-link active\">Questions</a></div>";
                        echo "<div class=\"nav-item\"><a href=\"$url" . "frontController.php?controller=question&action=readAll&etat=3\" class=\"nav-link active\">Commentaire</a></div>";
                        echo "<div class=\"nav-item\"><a href=\"$url" . "frontController.php?controller=question&action=readAll&etat=5\" class=\"nav-link active\">Propositions 2nd phase</a></div>";
                        echo "<div class=\"nav-item\"><a href=\"$url" . "frontController.php?controller=question&action=readAll&etat=7\" class=\"nav-link active\">Votes</a></div>";
                        echo "<div class=\"nav-item\"><a href=\"$url" . "frontController.php?controller=utilisateur&action=readMyQuestions\" class=\"nav-link active\">Mes questions</a></div>";
                    }
                    ?>
                    <?php
                    if (ConnexionUtilisateur::estAdministrateur()) {
                        echo "<div class=\"nav-item\"><a href=\"$url" . "frontController.php?controller=utilisateur&action=readAll\" class=\"nav-link active\">Utilisateurs</a></div>";
                    }
                    ?>
                    <?php
                    if (!ConnexionUtilisateur::estConnecte()) {
                        echo "<div class=\"nav-item\"><a href=\"$url" . "frontController.php?controller=utilisateur&action=connect\" class=\"nav-link active my-1\">Se connecter</a></div>";
                        echo "<div class=\"nav-item\"><a href=\"$url" . "frontController.php?controller=utilisateur&action=create\"  class=\"nav-link active my-1\">S'inscrire</a></div>";
                    } else {
                        $login = ConnexionUtilisateur::getLoginUtilisateurConnecte();
                        $loginURL = urlencode($login);

                        if (!ConnexionUtilisateur::estAdministrateur()) {
                            echo "<div class=\"nav-item\"><a href=\"$url" . "frontController.php?controller=utilisateur&action=demandeRole&login=$loginURL\">Demandes de rôle</a></div>";
                        }
                        echo "<div class=\"nav-item\"><a href=\"$url" . "frontController.php?controller=utilisateur&action=read&login=$loginURL\"  class=\"nav-link active my-1\"><img src=\"./img/user.png\" alt=\"informations\" title=\"enter\"></a></div>";
                        echo "<div class=\"nav-item\"><a href=\"$url" . "frontController.php?controller=utilisateur&action=deconnecter\" class=\"nav-link active my-1\"><img src=\"./img/logout.png\" alt=\"logout\" title=\"out\"></a></div>";
                    }
                    ?>
                </div>
            </div>
        </div>
    </nav>
</header>

<main class="d-flex mx-10 flex-column">
    <?php

    Session::getInstance();

    $messages = MessageFlash::lireTousMessages();
    for ($i = 0; $i < 4; $i++) {
        switch ($i) {
            case 0:
                foreach ($messages[$i] as $message) {
                    echo "<div class=\"alert alert-success\" role=\"alert\">$message</div>";
                }
                break;
            case 1:
                foreach ($messages[$i] as $message) {
                    echo "<div class=\"alert alert-info\" role=\"alert\">$message</div>";
                }
                break;
            case 2:
                foreach ($messages[$i] as $message) {
                    echo "<div class=\"alert alert-warning\" role=\"alert\">$message</div>";
                }
                break;
            default:
                foreach ($messages[$i] as $message) {
                    echo "<div class=\"alert alert-danger\" role=\"alert\">$message</div>";
                }
        }
    }
    //redirection vers une des pages en fonction des controller
    require __DIR__ . "/$cheminVueBody";
    ?>
</main>


<footer class="text-lg-start h-25 d-flex justify-content-center pt-5">
    <p class="text-center top-50">© PollEmploi 2023</p>
</footer>
</body>
</html>
