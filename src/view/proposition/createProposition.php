<?php
$idHTML = htmlspecialchars($idQuestion);
?>

<form method="post" action="frontController.php" ...>
    <fieldset class="bg-white d-flex flex-column p-5 rounded">
        <div class="mb-3">
            <p>
                <label for="intitule_id" class="form-label">Intitulé </label>
                <input type="text" name="intitule" id="intitule_id" class="form-control" required/>
            </p>
            <div id="coAuteurs">

            </div>
            <div id="newAuteur" class="mb-3 d-flex flex-row justify-content-evenly">
                <div class="d-flex justify-content-center pt-3" id="divCreateAuteurs">
                    <input class="btn btn-lg btn-primary" type="button" value="Nouvel auteur" id="ajoutButton"
                           onclick="ajouterCoAuteur();"/>
                </div>
                <div class="d-flex justify-content-center pt-3">
                    <input class="btn btn-lg btn-primary" type="button" value="Enlever Auteur"
                           onclick="supprimerCoAuteur();"/>
                </div>
            </div>
            <p id="inputs">
            <div class="d-flex justify-content-center pt-3">
                <input class="btn btn-lg btn-primary" type="submit" value="Envoyer" onclick="nombreAuteurs()"/>
            </div>
            <input type="hidden" name="action" value="created">
            <input type="hidden" name="controller" value="proposition">

            <input type="hidden" name="question" value="<?php echo $idQuestion ?>">
            <input type="hidden" name="login" value="<?php echo $login ?>">
            </p>
        </div>
    </fieldset>
</form>
<?php
$logins_json = JSON_Encode($logins);
$noms_json = JSON_Encode($noms);
?>
<script>
    let nbAuteurs = 0;
    let utilisateurs = <?php print $logins_json;?> ;
    let nomsUtilisateurs = <?php print $noms_json;?> ;

    function ajouterCoAuteur() {
        let length = document.getElementById('coAuteurs').childElementCount;
        if (length === 0 || !isEqual((document.getElementById('coAuteur-select' + (length - 1)).options[document.getElementById('coAuteur-select' + (length - 1)).selectedIndex].text), "choisissez un co-auteur")) {
            let div = document.getElementById('coAuteurs');
            let select = document.createElement('select');
            select.setAttribute('name', 'coAuteur' + length);
            select.setAttribute('id', 'coAuteur-select' + length);
            div.appendChild(select);

            let option1 = document.createElement('option');
            option1.innerText = "choisissez un co-auteur";
            select.appendChild(option1);

            for (key in utilisateurs) {
                let option = document.createElement('option');
                option.setAttribute("value", utilisateurs[key]);
                option.innerText = nomsUtilisateurs[key];
                select.appendChild(option);
            }
            nbAuteurs = length + 1;

            if (nbAuteurs === utilisateurs.length) {
                document.getElementById('ajoutButton').remove();
            }
        }

    }

    function supprimerCoAuteur() {
        let id = document.getElementById('coAuteurs').childElementCount - 1;
        if (id >= 0) {
            document.getElementById('coAuteur-select' + id).remove();
        }
        nbAuteurs = length - 1;

        if (!document.getElementById('ajoutButton')) {
            let input = document.createElement('input');
            input.setAttribute("class", "btn btn-lg btn-primary");
            input.setAttribute("type", "button");
            input.setAttribute("value", "Nouvel auteur");
            input.setAttribute("id", "ajoutButton");
            input.setAttribute("onclick", "ajouterCoAuteur()");
            document.getElementById('divCreateAuteurs').appendChild(input);
        }
    }

    function nombreAuteurs() {
        let input = document.createElement("input");
        input.setAttribute('type', 'hidden');
        input.setAttribute('name', 'nb');
        input.setAttribute('value', nbAuteurs);
        input.setAttribute('id', 'coAuteurs');
        document.getElementById("inputs").appendChild(input);
    }

    /*---------------------Gestion des cas particuliers------------------------*/

    function isEqual(str1, str2) {
        return str1.toUpperCase() === str2.toUpperCase();
    }

</script>
