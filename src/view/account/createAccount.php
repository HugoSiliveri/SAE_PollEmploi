<!--creation d'un compte-->
<form method="post" action="frontController.php" ...>
    <fieldset class="bg-white d-flex flex-column p-5 rounded">
        <div class="mb-3">
            <label for="login_id" class="form-label">Login</label>
            <input type="text" placeholder="rAndrew" name="login" id="login_id" class="form-control"/>
        </div>
        <div class="mb-3">
            <label for="nomUtilisateur_id" class="form-label">Nom </label>
            <input type="text" placeholder="Rocher" name="nomUtilisateur" id="nomUtilisateur_id" class="form-control"
                   required/>
        </div>
        <div class="mb-3">
            <label for="prenomUtilisateur_id" class="form-label">Prénom </label>
            <input type="text" placeholder="Andrew" name="prenomUtilisateur" id="prenomUtilisateur_id"
                   class="form-control" required/>
        </div>
        <div class="mb-3">
            <label for="adresseMail_id" class="form-label">Adresse Mail </label>
            <input type="email" placeholder="andrew.rocher@gmail.com" name="adresseMail" id="adresseMail_id"
                   class="form-control" required/>
        </div>
        <div class="mb-3">
            <label class="form-label" for="mdp_id">Mot de passe&#42;</label>
            <input class="form-control" type="password" value="" placeholder="" name="mdp" id="mdp_id" required>
        </div>
        <div class="mb-3">
            <label class="form-label" for="mdp2_id">Vérification du mot de passe&#42;</label>
            <input class="form-control" type="password" value="" placeholder="" name="mdp2" id="mdp2_id" required>
        </div>

        <input type="hidden" name="action" value="created">
        <input type="hidden" name="controller" value="utilisateur">
        <div class="d-flex justify-content-center pt-3">
            <input class="btn btn-lg btn-primary" type="submit" value="Envoyer"/>
        </div>
    </fieldset>
</form>

