<form method="post" action="frontController.php">
    <fieldset class="bg-white d-flex flex-column p-5 rounded">
        <label class="form-label" for="vote-select">Vote pour la proposition choisie <?= $question; ?></label>
        <?php
        $idQuestionHTML = htmlspecialchars($idQuestion);
        $loginHTML = htmlspecialchars($login);
        ?>
        <select name="proposition1" id="vote-select">
            <option value="">--Choisissez la proposition--</option>
            <?php
            foreach ($tabPropositions as $propositions) {
                $intituleHTML = htmlspecialchars($propositions->getIntituleProposition());
                $idProp1 = $propositions->getIdProposition();
                echo "<option value=\"$idProp1\">$intituleHTML</option>";
            }
            ?>
        </select>
        <p>
        <div class="d-flex justify-content-center pt-3">
            <input class="btn btn-lg btn-primary" type="submit" value="Envoyer"/>
        </div>
        <input type="hidden" name="action" value="votedMajoritaire">
        <input type="hidden" name="controller" value="proposition">
        <input type="hidden" name="id" value="<?= $idQuestionHTML; ?>">
        <input type="hidden" name="login" value="<?= $loginHTML; ?>">
        </p>
    </fieldset>
</form>