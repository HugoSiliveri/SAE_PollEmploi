<!--page pour modifier un commentaire-->
<?php
$idCommentaireHTML = htmlspecialchars($commentaire->getIdCommentaire());
$loginHTML = htmlspecialchars($commentaire->getLogin());
$idPropositionHTML = htmlspecialchars($commentaire->getIdProposition());
$messageHTML = htmlspecialchars($commentaire->getMessage());
$idQuestionHTML = htmlspecialchars($idQuestion);
?>

<form method="post" action="frontController.php">
    <fieldset class="bg-white d-flex flex-column p-5 rounded">
        <div class="mb-3">
            <div class="mb-3">
                <label for="login_id" class="form-label">Login</label>
                <input readonly type="text" value="<?= $loginHTML ?>" name="login" id="login_id" class="form-control"
                       required/>
            </div>
        </div>


        <div class="mb-3">
            <label for="message_id" class="form-label">Message </label>
            <textarea type="text" name="message" id="message_id" class="form-control"
                      required><?= $messageHTML ?></textarea>
        </div>

        <div class="mb-3">
            <input type="hidden" name="action" value="updated">
            <input type="hidden" name="idProposition" value="<?= $idPropositionHTML ?>">
            <input type="hidden" name="idCommentaire" value="<?= $idCommentaireHTML ?>">
            <input type="hidden" name="idQuestion" value="<?= $idQuestionHTML ?>">
            <input type="hidden" name="controller" value="commentaire">
        </div>

        <div class="d-flex justify-content-center pt-3">
            <input class="btn btn-lg btn-primary" type="submit" value="Envoyer"/>
        </div>
    </fieldset>
</form>

