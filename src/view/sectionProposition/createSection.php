<?php
$idPropositionHTML = htmlspecialchars($idProposition);
$idQuestionHTML = htmlspecialchars($idQuestion);
?>
<script src="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.js"></script>
<form method="post" action="frontController.php">
    <fieldset class="bg-white d-flex flex-column p-5 rounded">
        <div class="mb-3">
            <label for="vote-select" class="form-label">Question à laquelle vous souhaitez répondre</label>
            <select name="section" id="vote-select" class="form-select mb-3" required>
                <option value="">Choisissez la section de la question</option>
                <?php
                $i = 1;
                foreach ($tabSections as $section) {
                    $titreHTML = htmlspecialchars($section->getTitreSectionQuestion());
                    $idHTML = htmlspecialchars($section->getIdSectionQuestion());
                    echo "<option value=\"$idHTML\">$i) $titreHTML</option>";
                }
                ?>
            </select>

            <label for="texte_id" class="form-label">Texte </label>
            <textarea id="texte_id" class="form-control"></textarea>
        </div>
        <div>
            <div class="d-flex justify-content-center pt-3">
                <input class="btn btn-lg btn-primary" type="submit" value="Envoyer"/>
            </div>
            <input type="hidden" name="action" value="created">
            <input type="hidden" name="proposition" value="<?= $idPropositionHTML ?>">
            <input type="hidden" name="idQuestion" value="<?= $idQuestionHTML ?>">
            <input type="hidden" name="controller" value="sectionProposition">
        </div>

        <script>
            var simplemde = new SimpleMDE({
                element: document.getElementById("texte_id"),
                toolbar: ["bold", "italic", "heading", "|", "quote", "strikethrough", "unordered-list", "ordered-list", "|", "link", "table", "image", "|", "preview", "guide"]
            });

            const form = document.querySelector('form');

            form.addEventListener('submit', function (event) {
                const markdownValue = simplemde.value();

                const hiddenField = document.createElement('input');
                hiddenField.type = 'hidden';
                hiddenField.name = 'texte';
                hiddenField.value = markdownValue;
                hiddenField.required = true;
                form.appendChild(hiddenField);
            });
        </script>
    </fieldset>
</form>