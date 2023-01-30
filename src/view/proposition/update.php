<?php
// modification d'un question
$intituleHTML = htmlspecialchars($proposition->getIntituleProposition());
$idHTML = htmlspecialchars($proposition->getIdProposition());
$idQuestionHTML = htmlspecialchars($proposition->getIdQuestion());
?>

<script src="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.js"></script>
<form method="post" action="frontController.php" ...>
    <fieldset class="bg-white d-flex flex-column p-5 rounded">
        <div class="mb-3">
            <label for="intitule_id" class="form-label">Intitulé </label>
            <input type="text" name="intitule" id="intitule_id" value="<?= $intituleHTML; ?>" class="form-control"
                   required/>
            <br>
            <?php
            $id = "_id";
            $i = 1;
            foreach ($sectionsProposition as $section) {
                $idSectionPropositionHTML = htmlspecialchars($section->getIdSectionProposition());
                $texte = $section->getTexteSectionProposition();
                $titreSectionHTML = htmlspecialchars($section->getTitreSectionProposition());
                $idSectionQuestionHTML = htmlspecialchars($section->getIdSectionQuestion());

                echo "<input type=\"hidden\" name=\"idSectionProposition$i\" value=\"$idSectionPropositionHTML\"/>";
                echo "<input type=\"hidden\" name=\"titreSectionProposition$i\" value=\"$titreSectionHTML\"/>";
                echo "<input type=\"hidden\" name=\"idSectionQuestion$i\" value=\"$idSectionQuestionHTML\"/>";

                //ENVOI DE L'ANCIENNE VERSION DE LA SECTION PROPOSITION
                echo "<input type=\"hidden\" name=\"ancienTexte$i\" value=\"$texte\"/>";
                //FIN DE L'ENVOI

                echo '<label for="texteSection' . $i . '_id" class="form-label">' . "Texte section " . $i . '</label>';
                echo "<textarea id=\"texteSection$i$id\" class='form-control'></textarea>";
                echo '<br>';
                echo "<script>
                    var simplemde" . $i . " = new SimpleMDE({ element: document.getElementById(\"texteSection" . $i . "_id\"),
                        toolbar : [\"bold\", \"italic\", \"heading\", \"|\", \"quote\", \"strikethrough\", \"unordered-list\", \"ordered-list\", \"|\", \"link\", \"table\", \"image\", \"|\", \"preview\", \"side-by-side\", \"guide\"]});
        
                    simplemde" . $i . ".value(" . json_encode($texte) . ")
                    var form = document.querySelector('form');
        
                    form.addEventListener('submit', function(event) {
                        var markdownValue = simplemde" . $i . ".value();
                        var hiddenField = document.createElement('input');
                        hiddenField.type = 'hidden';
                        hiddenField.name = \"texteSection" . $i . "\";
                        hiddenField.value = markdownValue;
                        hiddenField.required = true;
                        form.appendChild(hiddenField);
                    });
                </script>";
                $i++;
            }
            echo "<input type=\"hidden\" name=\"nbSections\" value= \"$i\">";
            ?>
        </div>
        <p>
        <div class="d-flex justify-content-center pt-3">
            <input class="btn btn-lg btn-primary" type="submit" value="Envoyer"/>
        </div>

        <input type="hidden" name="action" value="updated">
        <input type="hidden" name="idQuestion" value="<?= $idQuestionHTML; ?>">
        <input type="hidden" name="id" value="<?= $idHTML; ?>">
        <input type="hidden" name="controller" value="proposition">
        </p>
    </fieldset>
</form>
