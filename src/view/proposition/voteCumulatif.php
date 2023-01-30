<form method="post" action="frontController.php">
    <fieldset class="bg-white d-flex flex-column p-5 rounded">
        <label class="form-label" for="vote-select">Vote pour <?= $nbProp ?> propositions de la
            question <?= $question ?></label>
        <?php
        $idQuestionHTML = htmlspecialchars($idQuestion);
        $loginHTML = htmlspecialchars($login);
        for ($i = 0; $i < $nbProp; $i++) {

            echo "<select class='form-select' name=\"proposition$i\" id=\"vote-select\">";
            echo "<option value=\"\">--Choisissez la " . $i . "e proposition--</option>";
            foreach ($tabPropositions as $propositions) {
                $intituleHTML = htmlspecialchars($propositions->getIntituleProposition());
                $idProp1 = $propositions->getIdProposition();
                echo "<option value=\"$idProp1\">$intituleHTML</option>";
            }
            echo "</select>";
            echo "<p>--------------------------------------</p>";
        }
        ?>

        <p>
        <div class="d-flex justify-content-center pt-3">
            <input class="btn btn-lg btn-primary" type="submit" value="Envoyer"/>
        </div>
        <input type="hidden" name="action" value="votedCumulatif">
        <input type="hidden" name="nbProp" value="<?= $nbProp ?>">
        <input type="hidden" name="controller" value="proposition">
        <input type="hidden" name="id" value="<?= $idQuestionHTML; ?>">
        <input type="hidden" name="login" value="<?= $loginHTML; ?>">
        </p>
    </fieldset>
</form>
