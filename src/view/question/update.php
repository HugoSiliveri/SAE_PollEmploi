<?php

use App\AgoraScript\Model\Repository\QuestionRepository;

$intituleHTML = htmlspecialchars($question->getIntituleQuestion());
$description = $question->getDescriptionQuestion();
$idHTML = htmlspecialchars($question->getIdQuestion());
$debutPropositionHTML = htmlspecialchars($question->getDateDebutProposition());
$finPropositionHTML = htmlspecialchars($question->getDateFinProposition());
$debutCommentaireHTML = htmlspecialchars($question->getDateDebutCommentaire());
$finCommentaireHTML = htmlspecialchars($question->getDateFinCommentaire());
$debutProposition2HTML = htmlspecialchars($question->getDateDebutProposition2());
$finProposition2HTML = htmlspecialchars($question->getDateFinProposition2());
$debutVoteHTML = htmlspecialchars($question->getDateDebutVote());
$finVoteHTML = htmlspecialchars($question->getDateFinVote());
$etatQuestionHTML = htmlspecialchars($question->getEtatQuestion());
?>
<script src="JS/addNewSections.js"></script>
<script src="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.js"></script>
<form method="post" action="frontController.php">
    <fieldset class="bg-white d-flex flex-column p-5 rounded">
        <div id="sections_2" class="mb-3">
            <div>
                <label for="intitule_id" class="form-label">Intitulé </label>
                <textarea name="intitule" id="intitule_id" rows='1' cols='50' class="form-control"
                          style="font-weight:bold;" required><?= $intituleHTML; ?></textarea>
                <br>
                <label for="description_id" class="form-label">Description de la question </label>
                <textarea id="description_id" class="form-control"></textarea>

                <br><br>
            </div>
            <?php
            $id = "_id";
            $i = 1;
            foreach ($tabSections as $section) {
                $titreHTML = htmlspecialchars($section->getTitreSectionQuestion());
                $descriptionSection = htmlspecialchars($section->getDescriptionSectionQuestion());
                echo "<div>";
                echo '<label for="titreSection' . $i . '_id" class="form-label">' . "Titre section " . $i . '</label>';
                echo "<textarea name=\"titreSection$i\" id=\"titreSection$i$id\" rows='1' cols='50' class='form-control' style='font-weight:bold;' required>$titreHTML</textarea>";
                echo "<br>";
                echo '<label for="descriptionSection' . $i . '_id" class="form-label">' . "Description section " . $i . '</label>';
                echo "<textarea id=\"descriptionSection$i$id\" class='form-control'></textarea>";
                echo "<br><br>";
                echo "</div>";
                echo "<script>
                    var simplemde" . $i . " = new SimpleMDE({ element: document.getElementById(\"descriptionSection" . $i . "_id\"),
                        toolbar : [\"bold\", \"italic\", \"heading\", \"|\", \"quote\", \"strikethrough\", \"unordered-list\", \"ordered-list\", \"|\", \"link\", \"table\", \"image\", \"|\", \"preview\", \"side-by-side\", \"guide\"]});
        
                    simplemde" . $i . ".value(" . json_encode($descriptionSection) . ")
                    var form = document.querySelector('form');
        
                    form.addEventListener('submit', function(event) {
                        var markdownValue = simplemde" . $i . ".value();
                        var hiddenField = document.createElement('input');
                        hiddenField.type = 'hidden';
                        hiddenField.name = \"descriptionSection" . $i . "MD\";
                        hiddenField.value = markdownValue;
                        hiddenField.required = true;
                        form.appendChild(hiddenField);
                    });
                </script>";
                $i++;
            }

            $nbSectionsDepart = count((new QuestionRepository)->getSectionsQuestion($question->getIdQuestion()));
            echo "<input type=\"hidden\" name=\"nbSectionsDepart\" value= \"$nbSectionsDepart\">";
            ?>
        </div>
        <p>
            <?php
            echo '<label for="debutProposition_id" class="form-label">Date début des propositions</label>';
            echo "<input type=\"date\" name=\"debutProposition\" id=\"debutProposition_id\" value=\"$debutPropositionHTML\" class='form-control' required/>";

            echo '<label for="finProposition_id" class="form-label">Date fin des propositions</label>';
            echo "<input type=\"date\" name=\"finProposition\" id=\"finProposition_id\" value=\"$finPropositionHTML\" class='form-control'required/>";

            echo '<br>';

            echo "<label for=\"debutCommentaire_id\" class=\"form-label\">Date début des commentaires</label>
            <input type=\"date\" name=\"debutCommentaire\" id=\"debutCommentaire_id\" value='$debutCommentaireHTML' class=\"form-control\" required/>

            <label for=\"finCommentaire_id\" class=\"form-label\">Date fin des commentaires</label>
            <input type=\"date\" name=\"finCommentaire\" id=\finCommentaire_id\" value='$finCommentaireHTML' class=\"form-control\" required/>

            <label for=\"debutProposition2_id\" class=\"form-label\">Date début de la seconde phase des propositions</label>
            <input type=\"date\" name=\"debutProposition2\" id=\"debutProposition2_id\" value='$debutProposition2HTML' class=\"form-control\" required/>

            <label for=\"finProposition2_id\" class=\"form-label\">Date fin de la seconde phase des propositions</label>
            <input type=\"date\" name=\"finProposition2\" id=\"finProposition2_id\" value='$finProposition2HTML' class=\"form-control\" required/>";

            echo '<label for="debutVote_id" class="form-label">Date début des votes</label>';
            echo "<input type=\"date\" name=\"debutVote\" id=\"debutVote_id\" value=\"$debutVoteHTML\" class='form-control' required/>";

            echo '<label for="finVote_id" class="form-label">Date fin des votes</label>';
            echo "<input type=\"date\" name=\"finVote\" id=\"finVote_id\" value=\"$finVoteHTML\" class='form-control' required/>";

            echo "<input type='hidden' name='etatQuestion' value=\"$etatQuestionHTML\"/>";
            echo "<input type='hidden' name='id' value=\"$idHTML\"/>";
            ?>
        </p>

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
                <input class="btn btn-lg btn-primary" type="button" value="Nouvelle Section" onclick="ajouter(2);"/>
            </div>
            <div class="d-flex justify-content-center pt-3">
                <input class="btn btn-lg btn-primary" type="button" value="Enlever Section" onclick="supprimer(2);"/>
            </div>
        </div>

        <div>
            <div class="d-flex justify-content-center pt-3">
                <input class="btn btn-lg btn-primary" type="submit" value="Envoyer"/>
            </div>
            <input type="hidden" name="action" value="updated">
            <input type="hidden" name="controller" value="question">
        </div>
        <script>
            var simplemde = new SimpleMDE({
                element: document.getElementById("description_id"),
                toolbar: ["bold", "italic", "heading", "|", "quote", "strikethrough", "unordered-list", "ordered-list", "|", "link", "table", "image", "|", "preview", "side-by-side", "guide"]
            });

            simplemde.value(<?php echo json_encode($description); ?>);
            var form = document.querySelector('form');

            form.addEventListener('submit', function (event) {
                var markdownValue = simplemde.value();
                var hiddenField = document.createElement('input');
                hiddenField.type = 'hidden';
                hiddenField.name = 'descriptionMd';
                hiddenField.value = markdownValue;
                hiddenField.required = true;
                form.appendChild(hiddenField);
            });
        </script>
    </fieldset>
</form>