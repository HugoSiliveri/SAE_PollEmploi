<!--modification du compte-->
<?php
$loginHTML = htmlspecialchars($utilisateur->getLogin());
$nomHTML = htmlspecialchars($utilisateur->getNomUtilisateur());
$prenomHTML = htmlspecialchars($utilisateur->getPrenomUtilisateur());
$adresseMailHTML = htmlspecialchars($utilisateur->getAdresseMail());
?>
<form method="post" action="frontController.php">
    <fieldset class="bg-white d-flex flex-column p-5 rounded">
        <div class="mb-3">
            <div class="mb-3">
                <label for="login_id" class="form-label">Login</label>
                <input readonly type="text" value="<?= $loginHTML ?>" name="login" id="login_id" class="form-control"
                       required/>
            </div>
            <div class="mb-3">
                <label for="AncienMdp_id" class="form-label">Ancien mot de passe</label>
                <input type="password" name="ancienMdp" id="AncienMdp_id" class="form-control" required/>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label" for="mdp_id">Nouveau mot de passe&#42;</label>
            <input class="form-control" type="password" value="" placeholder="" name="mdp" id="mdp_id" required>
        </div>
        <div class="mb-3">
            <label class="form-label" for="mdp2_id">Vérification du mot de passe&#42;</label>
            <input class="form-control" type="password" value="" placeholder="" name="mdp2" id="mdp2_id" required>
        </div>

        <div class="mb-3">
            <label for="adresseMail_id" class="form-label">Adresse Mail </label>
            <input type="text" value="<?= $adresseMailHTML ?>" placeholder="<?= $adresseMailHTML ?>" name="adresseMail"
                   id="adresseMail_id" class="form-control" required/>
        </div>

        <div class="mb-3">
            <label for="nom_id" class="form-label">Nom</label>
            <input type="text" value="<?= $nomHTML ?>" placeholder="<?= $nomHTML ?>" name="nom" id="nom_id"
                   class="form-control" required/>
        </div>
        <div class="mb-3">
            <label for="prenom_id" class="form-label">Prénom</label>
            <input type="text" value="<?= $prenomHTML ?>" placeholder="<?= $prenomHTML ?>" name="prenom" id="prenom_id"
                   class="form-control" required/>
        </div>
        <div class="mb-3">
            <input type="hidden" name="action" value="updated">
            <input type="hidden" name="controller" value="utilisateur">
        </div>

        <div class="d-flex justify-content-center pt-3">
            <input class="btn btn-lg btn-primary" type="submit" value="Envoyer"/>
        </div>
    </fieldset>
</form>