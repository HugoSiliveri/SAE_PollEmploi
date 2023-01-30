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
    if (!isset($idProposition)) {
        foreach ($utilisateurs as $utilisateur) {
            //if (ConnexionUtilisateur::estSansRoleparLogin($utilisateur->getLogin())) {
            $parLienDirect = 1;
            if ($i == 0) {
                echo "<tr class='bg-light opacity-75 hover'>
            <td class='col-8' onclick=location.href='" . $url . "frontController.php?controller=utilisateur&action=attribuerRole&login=" . rawurlencode($utilisateur->getLogin()) . "&idQuestion=" . rawurlencode($idQuestion) . "&votant=" . rawurlencode($votant) . "&lien=" . rawurlencode($parLienDirect) . "'>" . htmlspecialchars($utilisateur->getNomUtilisateur()) . "</td>";
            } else {
                echo "<tr class='bg-light opacity-75 hover'>
            <td class='col-8' onclick=location.href='" . $url . "frontController.php?controller=utilisateur&action=attribuerRole&login=" . rawurlencode($utilisateur->getLogin()) . "&idQuestion=" . rawurlencode($idQuestion) . "&votant=" . rawurlencode($votant) . "&lien=" . rawurlencode($parLienDirect) . "'>" . htmlspecialchars($utilisateur->getNomUtilisateur()) . "</td>";
            }
            $prenomHTML = htmlspecialchars($utilisateur->getPrenomUtilisateur());
            echo "<td>$prenomHTML</td>";
            $i++;
            // }
        }
    } else {
        foreach ($utilisateurs as $utilisateur) {
            //if (ConnexionUtilisateur::estSansRoleparLogin($utilisateur->getLogin())) {
            $parLienDirect = 1;
            if ($i == 0) {
                echo "<tr class='bg-light opacity-75 hover'>
            <td class='col-8' onclick=location.href='" . $url . "frontController.php?controller=utilisateur&action=attribuerUnCoAuteur&login=" . rawurlencode($utilisateur->getLogin()) . "&idQuestion=" . rawurlencode($idQuestion) . "&idProposition=" . rawurlencode($idProposition) . "&lien=" . rawurlencode($parLienDirect) . "'>" . htmlspecialchars($utilisateur->getNomUtilisateur()) . "</td>";
            } else {
                echo "<tr class='bg-light opacity-75 hover'>
            <td class='col-8' onclick=location.href='" . $url . "frontController.php?controller=utilisateur&action=attribuerUnCoAuteur&login=" . rawurlencode($utilisateur->getLogin()) . "&idQuestion=" . rawurlencode($idQuestion) . "&idProposition=" . rawurlencode($idProposition) . "&lien=" . rawurlencode($parLienDirect) . "'>" . htmlspecialchars($utilisateur->getNomUtilisateur()) . "</td>";
            }
            $prenomHTML = htmlspecialchars($utilisateur->getPrenomUtilisateur());
            echo "<td>$prenomHTML</td>";
            $i++;
            // }
        }
    }
    ?>


    </tbody>
</table>

