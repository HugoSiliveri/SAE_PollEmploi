<!--La question sur laquelle l'utilisateur travaille-->
<table id="tabQuestion" class="table table-striped rounded rounded-3 overflow-hidden">
    <thead class="table-dark">
    <tr>
        <th scope="col col-8">Titre</th>
        <th scope="col">Phase</th>
        <th scope="col">Date</th>
        <th scope="col">Role</th>
    </tr>
    </thead>
    <tbody>
    <?php

    $i = 0;
    foreach ($myQuestions as $question) {
        if ($i == 0) {
            echo "<tr class='bg-light opacity-75 hover'>
                          <td class='col-8' onclick=location.href='" . $url . "frontController.php?etat=" . rawurlencode($question->getEtatQuestion()) . "&controller=question&action=read&id=" . rawurlencode($question->getIdQuestion()) . "'>" . htmlspecialchars($question->getIntituleQuestion()) . "</td>";
        } else {
            echo "<tr class='bg-light opacity-75 hover'>
                          <td onclick=location.href='" . $url . "frontController.php?etat=" . rawurlencode($question->getEtatQuestion()) . "&controller=question&action=read&id=" . rawurlencode($question->getIdQuestion()) . "'>" . htmlspecialchars($question->getIntituleQuestion()) . "</td>";
        }
        switch ($question->getEtatQuestion()) {
            case 0:
                echo "<td>Début des propositions</td>
                              <td>" . htmlspecialchars($question->getDateDebutProposition()) . "</td>";
                break;
            case 1:
                echo "<td>Fin des propositions</td>
                              <td>" . htmlspecialchars($question->getDateFinProposition()) . "</td>";
                break;
            case 2:
                echo "<td>Début des commentaires</td>
                              <td>" . htmlspecialchars($question->getDateDebutCommentaire()) . "</td>";
                break;
            case 3:
                echo "<td>Fin des commentaires</td>
                              <td>" . htmlspecialchars($question->getDateFinCommentaire()) . "</td>";
                break;
            case 4:
                echo "<td>Debut de la seconde phase des propositions</td>
                              <td>" . htmlspecialchars($question->getDateDebutProposition2()) . "</td>";
                break;
            case 5:
                echo "<td>Fin de la seconde phase des propositions</td>
                              <td>" . htmlspecialchars($question->getDateFinProposition2()) . "</td>";
                break;
            case 6:
                echo "<td>Début des votes</td>
                              <td>" . htmlspecialchars($question->getDateDebutVote()) . "</td>";
                break;
            case 7:
                echo "<td>Fin des votes</td>
                              <td>" . htmlspecialchars($question->getDateFinVote()) . "</td>";
                break;
            default:
                echo "<td>Votes terminés</td>
                              <td>" . htmlspecialchars($question->getDateFinVote()) . "</td>";
        }
        echo "<td>" . htmlspecialchars($myRoles[$i]) . "</td>";
        echo "</tr>";
        $i += 1;
    }
    ?>
    </tbody>
</table>
