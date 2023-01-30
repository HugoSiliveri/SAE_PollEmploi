<!--connection-->
<form method="post" action="frontController.php" ...>
    <fieldset class="bg-white d-flex flex-column p-5 rounded">
        <div class="mb-3">
            <label for="login_id" class="form-label">Login</label>
            <input type="text" placeholder="bloris" name="login" id="login_id" class="form-control" required/>
        </div>
        <div class="mb-3">
            <label class="form-label" for="mdp_id">Mot de passe&#42;</label>
            <input class="form-control" type="password" value="" placeholder="" name="mdp" id="mdp_id" required>

            <input type="hidden" name="action" value="connecter">
            <input type="hidden" name="controller" value="utilisateur">
        </div>

        <div class="d-flex justify-content-center pt-3">
            <input class="btn btn-lg btn-primary" type="submit" value="Envoyer"/>
        </div>
    </fieldset>
</form>