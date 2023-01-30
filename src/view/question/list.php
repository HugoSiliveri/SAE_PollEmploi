<?php
//liste des questions
use App\AgoraScript\Lib\ConnexionUtilisateur;

if (ConnexionUtilisateur::estAdministrateur() || ConnexionUtilisateur::estOrganisateur()) {
    echo "<div class=\"d-flex flex-row justify-content-center mb-3\">
                 <a class=\"nav-link active fw-bold\" href=\"$url" . "frontController.php?controller=question&action=create\">Poser une question</a>
            </div>";
}
?>
<script src="./JS/searchauto.js" defer></script>
<p class="d-flex mx-auto p-3 ">
    <input onkeyup="chercher()" id="input" class="form-control me-2 " type="search" placeholder="Chercher"
           aria-label="Search">
</p>
<table id="tabQuestion" class="table table-striped rounded rounded-3 overflow-hidden">
    <thead class="table-dark">
    <tr>
        <th scope="col col-8">Titre</th>
        <th scope="col">Phase</th>
        <th scope="col">Date</th>
    </tr>
    </thead>
    <tbody>
    <?php
    $i = 0;
    $id = 0;
    foreach ($questions as $question) {
        if ($i == 0) {
            echo "<tr id='$id' class='bg-light opacity-75 hover'>
                          <td class='col-8' onclick=location.href='" . $url . "frontController.php?etat=" . rawurlencode($question->getEtatQuestion()) . "&controller=question&action=read&id=" . rawurlencode($question->getIdQuestion()) . "'>" . htmlspecialchars($question->getIntituleQuestion()) . "</td>";
        } else {
            echo "<tr id='$id' class='bg-light opacity-75 hover'>
                          <td onclick=location.href='" . $url . "frontController.php?etat=" . rawurlencode($question->getEtatQuestion()) . "&controller=question&action=read&id=" . rawurlencode($question->getIdQuestion()) . "'>" . htmlspecialchars($question->getIntituleQuestion()) . "</td>";
        }
        //change de texte en fonction de l'etat des questions
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
        echo "</tr>";
        $i += 1;
        ++$id;
    }
    ?>
    </tbody>
</table>