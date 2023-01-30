<form method="post" action="frontController.php" ...>
    <fieldset>
        <p>
            <label for="login_id">Login</label> :
            <input type="text" placeholder="bloris" name="login" id="login_id" required/>
            <br>

        <p class="InputAddOn">
            <label class="InputAddOn-item" for="mdp_id">Mot de passe&#42;</label>
            <input class="InputAddOn-field" type="password" value="" placeholder="" name="mdp" id="mdp_id" required>

            <input type="hidden" name="action" value="connecter">
            <input type="hidden" name="controller" value="utilisateur">

        </p>

        <p>
            <input type="submit" value="Envoyer"/>
        </p>
    </fieldset>
</form>