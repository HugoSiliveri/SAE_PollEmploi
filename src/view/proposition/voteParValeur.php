<form method="post" action="frontController.php">
    <fieldset class="bg-white d-flex flex-column p-5 rounded">
        <label class="form-label" for="vote">Vote pour <?= $nbProp ?> propositions de la
            question <?= $question ?></label>
        <?php
        $idQuestionHTML = htmlspecialchars($idQuestion);
        $loginHTML = htmlspecialchars($login);

        for ($i = 0; $i < $nbProp; $i++) {


            $intituleHTML = htmlspecialchars($tabPropositions[$i]->getIntituleProposition());
            $idProp = $tabPropositions[$i]->getIdProposition();

            $idFinalTB = $idProp . "TB";
            $idFinalBB = $idProp . "BB";
            $idFinalAB = $idProp . "AB";
            $idFinalPP = $idProp . "PP";
            $idFinalII = $idProp . "II";
            $idFinalAR = $idProp . "AR";


            $idFinalTBHTML = htmlspecialchars($idFinalTB);
            $idFinalBBHTML = htmlspecialchars($idFinalBB);
            $idFinalABHTML = htmlspecialchars($idFinalAB);
            $idFinalPPHTML = htmlspecialchars($idFinalPP);
            $idFinalIIHTML = htmlspecialchars($idFinalII);
            $idFinalARHTML = htmlspecialchars($idFinalAR);

            echo "<label class='form-label' for=\"vote-select$i\">$intituleHTML</label>";
            echo "<select class='form-select' name=\"proposition$i\" id=\"vote-select$i\">";
            echo "<option value=\"$idFinalTBHTML\">Très bien</option>";
            echo "<option value=\"$idFinalBBHTML\">Bien</option>";
            echo "<option value=\"$idFinalABHTML\">Assez bien</option>";
            echo "<option value=\"$idFinalPPHTML\">Passable</option>";
            echo "<option value=\"$idFinalIIHTML\">Insuffisant</option>";
            echo "<option value=\"$idFinalARHTML\">A rejeter</option>";
            echo "</select>";
            echo "<p>--------------------------------------</p>";
        }
        ?>

        <p>
        <div class="d-flex justify-content-center pt-3">
            <input class="btn btn-lg btn-primary" type="submit" value="Envoyer"/>
        </div>
        <input type="hidden" name="action" value="votedValeur">
        <input type="hidden" name="nbProp" value="<?= $nbProp ?>">
        <input type="hidden" name="controller" value="proposition">
        <input type="hidden" name="id" value="<?= $idQuestionHTML; ?>">
        <input type="hidden" name="login" value="<?= $loginHTML; ?>">
        </p>
    </fieldset>
</form>