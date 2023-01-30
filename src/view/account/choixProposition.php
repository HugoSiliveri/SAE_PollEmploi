<?php
$idQuestionHTML = htmlspecialchars($idQuestion);
$loginHTML = htmlspecialchars($login);
?>

<form method="post" action="frontController.php" ...>
    <fieldset class="bg-white d-flex flex-column p-5 rounded">
        <div class="mb-3">
            <h1 Choix de la proposition></h1>
            <input type="hidden" name="action" value="demandeAuteur">
            <input type="hidden" name="login" value="<?= $loginHTML ?>">
            <input type="hidden" name="idQuestion" value="<?= $idQuestionHTML ?>">
            <input type="hidden" name="controller" value="utilisateur">
        </div>
        <div>
            <?php
            echo "<div>";
            echo "<label for='role-select' class='form-label'>Choix de la proposition</label>
                        <select name='idProposition' class='form-select mb-3' id='role-select'>";

            foreach ($propositions as $proposition) {
                $intitulePropositionHTML = htmlspecialchars($proposition->getIntituleProposition());
                $idPropositionHTML = htmlspecialchars($proposition->getIdProposition());
                echo " <option value=\"$idPropositionHTML\">$intitulePropositionHTML</option>";
            }
            echo "</select>";
            echo "</div>";
            ?>

            <div class="d-flex justify-content-center pt-3">
                <input class="btn btn-lg btn-primary" type="submit" value="Envoyer"/>
            </div>


    </fieldset>
</form>
