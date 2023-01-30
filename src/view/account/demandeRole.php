<!DOCTYPE html>
<?php

$loginHTML = htmlspecialchars($login);
?>
<form method="post" action="frontController.php" ...>
    <fieldset class="bg-white d-flex flex-column p-5 rounded">
        <div class="mb-3">
            <input type="hidden" name="action" value="demander">
            <input type="hidden" name="login" value="<?= $loginHTML ?>">
            <input type="hidden" name="controller" value="utilisateur">
        </div>
        <div>
            <?php
            echo "<label for='role-select' class='form-label'>Choix du rôle</label>
                        <select name='role' class='form-select mb-3' id='role-select'>
                          <option value='ResponsableP'>Responsable de proposition</option>
                          <option value='Votant'>Votant</option>
                          <option value='Auteur'>Auteur</option>
                        </select>";
            echo "</div>";

            echo "<div>";
            echo "<label for='role-select' class='form-label'>Choix de la question</label>
                        <select name='idQuestion' class='form-select mb-3' id='role-select'>";

            foreach ($questions as $question) {
                $intituleQuestionHTML = htmlspecialchars($question->getIntituleQuestion());
                $idQuestionHTML = htmlspecialchars($question->getIdQuestion());
                echo " <option value=\"$idQuestionHTML\">$intituleQuestionHTML</option>";
            }
            echo "</select>";
            echo "</div>";

            ?>

            <div class="d-flex justify-content-center pt-3">
                <input class="btn btn-lg btn-primary" type="submit" value="Envoyer"/>
            </div>


    </fieldset>
</form>