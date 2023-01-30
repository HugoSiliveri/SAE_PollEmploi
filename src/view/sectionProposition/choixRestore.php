<script src="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.js"></script>
<div class="d-flex flex-column align-items-center bg-white p-5 rounded">
    <h1>Choix de la restauration</h1>

<?php

use App\AgoraScript\Lib\ConnexionUtilisateur;

foreach ($versions as $version) {
    $numeroVersion = $version->getVersion();
    echo "<div class='d-flex flex-column align-items-start w-100 mb-3 text-justify'>
                      <h4> Version numéro : $numeroVersion " . "</h4>
                      <textarea id='version$numeroVersion' class='form-control'></textarea>
                  </div>
                  <script> 
                            var simplemde" . $numeroVersion . " = new SimpleMDE({ element: document.getElementById( \"version" . $numeroVersion . "\"), toolbar: false, status: false});
                            simplemde" . $numeroVersion . ".value(" . json_encode($version->getTexteSectionProposition()) . ")
                            simplemde" . $numeroVersion . ".togglePreview();                  
                  </script>";
    if (ConnexionUtilisateur::estAdministrateur() || ConnexionUtilisateur::estResponsableP($idQuestion)) {
        $idSectionPropositionHTML = htmlspecialchars($version->getIdSectionProposition());
        $numeroVersionHTML = htmlspecialchars($numeroVersion);
        $idQuestionHTML = htmlspecialchars($idQuestion);
        echo "<form method=\"post\" action=\"frontController.php\">";
        echo "<input class='btn mb-5' type=\"submit\" value=\"Restaurer\">";
        echo "<input type=\"hidden\" name=\"action\" value=\"restored\">";
        echo "<input type=\"hidden\" name=\"controller\" value=\"sectionProposition\">";
        echo "<input type=\"hidden\" name=\"idSectionProposition\" value=\"$idSectionPropositionHTML\">";
        echo "<input type=\"hidden\" name=\"version\" value=\"$numeroVersionHTML\">";
        echo "<input type=\"hidden\" name=\"idQuestion\" value=\"$idQuestionHTML\">";
        echo "</form>";
    }
}
