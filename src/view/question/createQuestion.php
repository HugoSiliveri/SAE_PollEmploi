<script src="JS/addNewSections.js"></script>
<script src="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.js"></script>
<form method="post" action="frontController.php" ...>
    <fieldset class="bg-white d-flex flex-column p-5 rounded">
        <div id="sections_1" class="mb-3">
            <div>
                <label class="form-label mt-2">Intitulé </label>
                <textarea name="intitule" id="intitule_id" rows='1' cols='50' class="form-control"
                          style="font-weight:bold;" required></textarea>

                <label class="form-label mt-2">Description de la question </label>
                <textarea id="description_id" class="form-control"></textarea>

                <br><br>

                <label for="titreSection1_id" class="form-label">Titre section 1</label>
                <textarea name="titreSection1" id="titreSection1_id" rows='1' cols='50' class="form-control"
                          style="font-weight:bold;" required></textarea>

                <label class="form-label mt-2">Description section 1</label>
                <textarea id="descriptionSection1_id" class="form-control"></textarea>

                <br><br>
            </div>
        </div>
        <div id="dates" class="mb-3">
            <label for="debutProposition_id" class="form-label">Date début des propositions</label>
            <input type="date" name="debutProposition" id="debutProposition_id" class="form-control" required/>

            <label for="finProposition_id" class="form-label">Date fin des propositions</label>
            <input type="date" name="finProposition" id="finProposition_id" class="form-control" required/>

            <label for="debutCommentaire_id" class="form-label">Date début des commentaires</label>
            <input type="date" name="debutCommentaire" id="debutCommentaire_id" class="form-control" required/>

            <label for="finCommentaire_id" class="form-label">Date fin des commentaires</label>
            <input type="date" name="finCommentaire" id="finCommentaire_id" class="form-control" required/>

            <label for="debutProposition2_id" class="form-label">Date début de la seconde phase des propositions</label>
            <input type="date" name="debutProposition2" id="debutProposition2_id" class="form-control" required/>

            <label for="finProposition2_id" class="form-label">Date fin de la seconde phase des propositions</label>
            <input type="date" name="finProposition2" id="finProposition2_id" class="form-control" required/>

            <label for="debutVote_id" class="form-label">Date début des votes</label>
            <input type="date" name="debutVote" id="debutVote_id" class="form-control" required/>

            <label for="finVote_id" class="form-label">Date fin des votes</label>
            <input type="date" name="finVote" id="finVote_id" class="form-control" required/>
            <br>
        </div>

        <div class="mb-3">
            <label for="vote-select" class="form-label">Choix du vote</label>
            <select name="typeVote" class="form-select" id="vote-select" required>
                <option value="scrutinMajoritaire">Scrutin majoritaire</option>
                <option value="voteCumulatif">Vote cumulatif</option>
                <option value="voteParValeur">Vote par valeur</option>
            </select>
        </div>

        <div class="mb-3 d-flex flex-row justify-content-evenly">
            <div class="d-flex justify-content-center pt-3">
                <input class="btn btn-lg btn-primary" type="button" value="Nouvelle Section" onclick="ajouter(1);"/>
            </div>
            <div class="d-flex justify-content-center pt-3">
                <input class="btn btn-lg btn-primary" type="button" value="Enlever Section" onclick="supprimer(1);"/>
            </div>
        </div>

        <div id="listeBoutons">
            <div class="d-flex justify-content-center pt-3">
                <input class="btn btn-lg btn-primary" type="submit" value="Envoyer"/>
            </div>
            <input type="hidden" name="action" value="created">
            <input type="hidden" name="controller" value="question">
        </div>


        <script id="listeMD1">
            var simplemde = new SimpleMDE({
                element: document.getElementById("description_id"),
                toolbar: ["bold", "italic", "heading", "|", "quote", "strikethrough", "unordered-list", "ordered-list", "|", "link", "table", "image", "|", "preview", "guide"]
            });
            var simplemde1 = new SimpleMDE({
                element: document.getElementById("descriptionSection1_id"),
                toolbar: ["bold", "italic", "heading", "|", "quote", "strikethrough", "unordered-list", "ordered-list", "|", "link", "table", "image", "|", "preview", "guide"]
            });

            const form = document.querySelector('form');

            form.addEventListener('submit', function (event) {
                const markdownValue = simplemde.value();

                const hiddenField = document.createElement('input');
                hiddenField.type = 'hidden';
                hiddenField.name = 'descriptionMd';
                hiddenField.value = markdownValue;
                hiddenField.required = true;
                form.appendChild(hiddenField);
            });

            form.addEventListener('submit', function (event) {
                const markdownValue = simplemde1.value();

                const hiddenField = document.createElement('input');
                hiddenField.type = 'hidden';
                hiddenField.name = 'descriptionSection1Md';
                hiddenField.value = markdownValue;
                hiddenField.required = true;
                form.appendChild(hiddenField);
            });
        </script>
    </fieldset>
</form>