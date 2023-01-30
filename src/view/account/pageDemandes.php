<!--pour demander l'acces a un certain role -->
<table class="table table-striped rounded rounded-3 overflow-hidden">
    <thead class="table-dark">
    <tr>
        <th scope="col col-8">Login</th>
        <th scope="col">Question</th>
    </tr>
    </thead>
    <tbody>

    <?php

    use App\AgoraScript\Config\Conf;

    $url = Conf::getUrlBase();
    $i = 0;
    foreach ($tabLoginParQuestion as $login => $tabQuestionRole) {
        $loginURL = urlencode($login);
        $loginHTML = htmlspecialchars($login);
        $intituleHTML = htmlspecialchars($tabQuestionRole[0]->getIntituleQuestion());
        $intituleURL = urlencode($tabQuestionRole[0]->getIntituleQuestion());
        $roleURL = urlencode($tabQuestionRole[1]);
        if ($i == 0) {
            echo "<tr class='bg-light opacity-75 hover'>
            <td class='col-8' onclick=location.href='" . $url . "frontController.php?controller=utilisateur&action=readDemande&login=$loginURL" . "&intitule=$intituleURL" . "&role=$roleURL" . "'>" . $loginHTML . "</td>";
        } else {
            echo "<tr class='bg-light opacity-75 hover'>
            <td class='col-8' onclick=location.href='" . $url . "frontController.php?controller=utilisateur&action=readDemande&login=$loginURL" . "&intitule=$intituleURL" . "&role=$roleURL" . "'>" . $loginHTML . "</td>";
        }
        echo "<td>$intituleHTML</td>";
        $i++;
    }
    ?>


    </tbody>
</table>
