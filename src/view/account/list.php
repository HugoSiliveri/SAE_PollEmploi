<!--liste de tous les utilisateurs pour les admins seulement-->
<?php

use App\AgoraScript\Config\Conf;

$url = Conf::getUrlBase();
echo "<div class=\"d-flex flex-row justify-content-center mb-3\">
<a class=\"nav-link active fw-bold\" href=\"$url" . "frontController.php?controller=utilisateur&action=voirDemandes\">Voir les demandes</a>
</div>";
?>

<table class="table table-striped rounded rounded-3 overflow-hidden">
    <thead class="table-dark">
    <tr>
        <th scope="col col-8">Nom</th>
        <th scope="col">Prénom</th>
    </tr>
    </thead>
    <tbody>

    <?php
    $i = 0;
    foreach ($utilisateurs as $utilisateur) {
        if ($i == 0) {
            echo "<tr class='bg-light opacity-75 hover'>
            <td class='col-8' onclick=location.href='" . $url . "frontController.php?controller=utilisateur&action=attribuerRole&login=" . rawurlencode($utilisateur->getLogin()) . "'>" . htmlspecialchars($utilisateur->getNomUtilisateur()) . "</td>";
        } else {
            echo "<tr class='bg-light opacity-75 hover'>
            <td class='col-8' onclick=location.href='" . $url . "frontController.php?controller=utilisateur&action=attribuerRole&login=" . rawurlencode($utilisateur->getLogin()) . "'>" . htmlspecialchars($utilisateur->getNomUtilisateur()) . "</td>";
        }
        $prenomHTML = htmlspecialchars($utilisateur->getPrenomUtilisateur());
        echo "<td>$prenomHTML</td>";
        $i++;
    }
    ?>


    </tbody>
</table>
