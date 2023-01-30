<script src="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.js"></script>
<div class="d-flex flex-column align-items-center bg-white p-4 rounded">
    <h1><?php echo $question->getIntituleQuestion() ?></h1>
    <h5 class="d-flex flex-column align-items-center w-100 mb-3 white-space text-justify">
        <?php

        use App\AgoraScript\Lib\ConnexionUtilisateur;
        use App\AgoraScript\Lib\TypeVote;
        use App\AgoraScript\Model\Repository\AffectationsRepository;

        $auteur = (new AffectationsRepository())->getAuteur($question->getIdQuestion());
        echo "Auteur : " . htmlspecialchars($auteur);
        ?>
    </h5>
    <div class="d-flex flex-column w-100 mb-3 text-justify">
        <textarea id="description_id" class="form-control"></textarea>
        <?php
        $desc = $question->getDescriptionQuestion();
        ?>
        <script>
            var simplemde = new SimpleMDE({
                element: document.getElementById("description_id"),
                toolbar: false,
                status: false
            });
            simplemde.value(<?php echo json_encode($desc)?>);
            simplemde.togglePreview();
        </script>
    </div>


    <?php
    $i = 1;
    foreach ($sections as $section) {
        $sect = $section->getDescriptionSectionQuestion();
        echo "<div class='d-flex flex-column w-100 mb-3 text-justify'>
                      <h4>" . $i . ") " . htmlspecialchars($section->getTitreSectionQuestion()) . "</h4>
                      <textarea id='descriptionSection$i' class='form-control'></textarea>
                      <script> 
                            var simplemde" . $i . " = new SimpleMDE({ element: document.getElementById( \"descriptionSection" . $i . "\"), toolbar: false, status: false});
                            simplemde" . $i . ".value(" . json_encode($sect) . ")
                            simplemde" . $i . ".togglePreview();                  
                      </script>
                  </div>";
        $i++;
    }
    ?>

    <table class="table table-striped rounded rounded-3 overflow-hidden mt-4">
        <thead class="table-dark">
        <tr>
            <th scope="col">Propositions</th>
            <?php
            $etatQuestion = $question->getEtatQuestion();
            if ($etatQuestion == 7 || $etatQuestion == 8) echo "<th scope=\"col\">Progression</th>";
            ?>
        </tr>
        </thead>
        <tbody>
        <?php
        $etatQuestion = $question->getEtatQuestion();

        foreach ($propositions as $proposition) {
            echo "<tr class='border border-2'>
                          <td onclick=location.href='" . $url . "frontController.php?action=read&controller=proposition&id=" . rawurlencode($proposition->getIdProposition()) . "&idQuestion=" . rawurlencode($question->getIdQuestion()) . "'>" . htmlspecialchars($proposition->getIntituleProposition()) . "</td>";
            //echo "<td><canvas id=\"myChart\" width=\"200\" height=\"200\" role=\"img\" aria-label=\"chart\"></canvas></td>";
            if ($etatQuestion == 7 || $etatQuestion == 8) {
                $progression = "";
                if (strcmp($typeVote, TypeVote::VOTE_PAR_VALEUR) == 0) {
                    $tabNomVote = array("A rejeter", "Insuffisant", "Passable", "Assez bien", "Bien", "Très bien");
                    $i = 0;
                    foreach ($progressionVote[$proposition->getIdProposition()] as $nbVote) {
                        $progression .= $tabNomVote[$i] . " : " . $nbVote . " | ";
                        ++$i;
                    }
                } else {
                    $progression .= "Nombre de vote : " . $progressionVote[$proposition->getIdProposition()];
                }

                echo "<td>$progression</td>";
            }
        }

        ?>

        </tbody>
    </table>
    <?php

    if ($question->getEtatQuestion() == 1 || $question->getEtatQuestion() == 5) {
        if (ConnexionUtilisateur::estAdministrateur() || (ConnexionUtilisateur::estResponsableP($question->getIdQuestion()) && !$aDejaEcritUneProp)) {
            echo "<form method='post' action='frontController.php'>
                              <input class='btn mb-5' type='submit' value='Rajouter une proposition'>
                              <input type='hidden' name='action' value='create'>
                              <input type='hidden' name='controller' value='proposition'>
                              <input type='hidden' name='idQuestion' value='" . rawurlencode($question->getIdQuestion()) . "'>
                               <input type='hidden' name='login' value='" . rawurlencode(ConnexionUtilisateur::getLoginUtilisateurConnecte()) . "'>
                          </form>";
        }
        if ((ConnexionUtilisateur::estOrganisateurSurQuestion($question->getIdQuestion())) || ConnexionUtilisateur::estAdministrateur()) {

            echo "<form method='post' action='frontController.php'>
                              <input class='btn mb-5' type='submit' value='Rajouter un responsable de proposition'>
                              <input type='hidden' name='action' value='readAllPourUserProps'>
                              <input type='hidden' name='controller' value='utilisateur'>
                              <input type='hidden' name='idQuestion' value='" . rawurlencode($question->getIdQuestion()) . "'>
                               <input type='hidden' name='votant' value='" . "0" . "'>
                          </form>";

        }
        if ((ConnexionUtilisateur::estOrganisateurSurQuestion($question->getIdQuestion())) || ConnexionUtilisateur::estAdministrateur()) {

            echo "<form method='post' action='frontController.php'>
                              <input class='btn mb-5' type='submit' value='Rajouter un votant'>
                              <input type='hidden' name='action' value='readAllPourUserProps'>
                              <input type='hidden' name='controller' value='utilisateur'>
                              <input type='hidden' name='idQuestion' value='" . rawurlencode($question->getIdQuestion()) . "'>
                              <input type='hidden' name='votant' value='" . "1" . "'>
                          </form>";

        }
    }
    $idURL = rawurlencode($question->getIdQuestion());
    $etatQuestion = $question->getEtatQuestion();

    if ($etatQuestion == 0) {
        if ((ConnexionUtilisateur::estOrganisateurSurQuestion($question->getIdQuestion())) || ConnexionUtilisateur::estAdministrateur()) {
            echo "<div class=\"d-flex flex-row justify-content-evenly w-100\">";
            echo "<a class=\"btn btn-lg btn-primary\" href=\"" . $url . "frontController.php?etat=" . $question->getEtatQuestion() . "&controller=question&action=update&id=" . $idURL . "\">Modifier</a>";
            echo "<a class=\"btn btn-lg btn-primary\" href=\"" . $url . "frontController.php?etat=" . rawurlencode($question->getEtatQuestion()) . "&controller=question&action=delete&id=" . $idURL . "\">Supprimer</a>";
            echo "</div>";
        }
    }

    if ((ConnexionUtilisateur::estVotant($question->getIdQuestion())) || ConnexionUtilisateur::estAdministrateur()) {
        if ($etatQuestion == 7) {
            $login = urlencode(ConnexionUtilisateur::getLoginUtilisateurConnecte());
            if (!$aVote) {
                echo "<div class=\"d-flex flex-row justify-content-evenly w-100\">";
                echo "<a class=\"btn btn-lg btn-primary\" href=\"" . $url . "frontController.php?controller=proposition&action=vote&id=" . $idURL . "&login=$login" . "\">Voter</a>";
                echo "</div>";
            }
        }
    }

    if ($etatQuestion == 8) {
        echo "<div class=\"d-flex flex-row justify-content-evenly w-100\">";
        echo "<a class=\"btn btn-lg btn-primary\" href=\"" . $url . "frontController.php?controller=question&action=resultat&id=" . $idURL . "\">Voir Résultats</a>";
        echo "</div>";
    }
    ?>


    <div class="d-flex justify-content-center pt-3">
        <input class="btn btn-lg btn-primary" type="button" value="Retour" onclick="history.back()"/>
    </div>
</div>
