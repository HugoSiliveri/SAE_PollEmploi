<?php
$idHTML = htmlspecialchars($idQuestion);
$nbPropHTML = htmlspecialchars($nbProp);
?>

<form method="post" action="frontController.php" ...>
    <fieldset class="bg-white d-flex flex-column p-5 rounded">
        <div class="mb-3">
            <label for="nbProp_id" class="form-label">Nombre de propositions</label>
            <input type="number" placeholder="0" name="nbProp" id="nbProp_id" max="<?= $nbPropHTML ?>" min="0"
                   class="form-control" required/>
        </div>
        <p>
        <div class="d-flex justify-content-center pt-3">
            <input class="btn btn-lg btn-primary" type="submit" value="Envoyer"/>
        </div>
        <input type="hidden" name="action" value="voteCumulatif">
        <input type="hidden" name="idQuestion" value="<?= $idHTML ?>">
        <input type="hidden" name="controller" value="proposition">
        </p>
    </fieldset>
</form>