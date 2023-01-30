<!DOCTYPE html>
<?php
//recolte des données
use App\AgoraScript\Lib\ConnexionUtilisateur;

$loginHTML = htmlspecialchars($utilisateur->getLogin());
$nomHTML = htmlspecialchars($utilisateur->getNomUtilisateur());
$prenomHTML = htmlspecialchars($utilisateur->getPrenomUtilisateur());
if (isset($idQuestion)) {
    $idQuestionHTML = htmlspecialchars($idQuestion);
}
if (isset($idProposition)) {
    $idPropositionHTML = htmlspecialchars($idProposition);
}
?>
<!-- Selection de la personne voulue-->
<form method="post" action="frontController.php" ...>
    <fieldset class="bg-white d-flex flex-column p-5 rounded">

        <div class="mb-3">
            <label for="login_id" class="form-label">Login</label>
            <input readonly type="text" value="<?= $loginHTML ?>" name="login" id="login_id" class="form-control"
                   required/>
        </div>

        <div class="mb-3">
            <label for="nom_id" class="form-label">Nom</label>
            <input readonly type="text" value=<?= $nomHTML ?> placeholder="bord" name="nom" id="nom_id"
                   class="form-control" required/>
        </div>

        <div class="mb-3">
            <label for="prenom_id" class="form-label">Prénom</label>
            <input readonly type="text" value=<?= $prenomHTML ?> placeholder="loris" name="prenom" id="prenom_id"
                   class="form-control" required/>
        </div>

        <div class="mb-3">
            <input type="hidden" name="action" value="atributed">
            <input type="hidden" name="controller" value="utilisateur">
            <?php
            if (isset($idQuestion)) {
                echo "<input type='hidden' name='idQuestion' value=$idQuestionHTML >";
            }
            if (isset($idProposition)) {
                echo "<input type='hidden' name='idProposition' value=$idPropositionHTML >";
            }

            ?>
        </div>

        <div>
            <?php
            if (isset($listeProposition) && ConnexionUtilisateur::estAdministrateur()) {
                echo "<div>";
                echo "<label for='role-select2' class='form-label'>Choix de la proposition</label>
                        <select name='idProposition' class='form-select mb-3' id='role-select2'>";

                foreach ($listeProposition as $proposition) {
                    $intitulePropositionHTML = htmlspecialchars($proposition->getIntituleProposition());
                    $idPropositionHTML = htmlspecialchars($proposition->getIdProposition());
                    echo " <option value=\"$idPropositionHTML\">$intitulePropositionHTML</option>";
                }
                echo "</select>";
                echo "</div>";
                echo "<input type='hidden' name='role' value='Auteur'>";
            } else if (ConnexionUtilisateur::estOrganisateur() && isset($lien) && !isset($idProposition) || (ConnexionUtilisateur::estAdministrateur() && isset($lien) && !isset($idProposition))) {
                //choix disponible en fonction du role de l'utilisateur
                if (strcmp($votant, "0") == 0) {
                    echo "<label for='role-select' class='form-label'>Choix du rôle</label>
                        <select name='role' class='form-select mb-3' id='role-select'>
                          <option value='ResponsableP'>Responsable de proposition</option>
                        </select>";
                } else {
                    echo "<label for='role-select' class='form-label'>Choix du rôle</label>
                        <select name='role' class='form-select mb-3' id='role-select'>
                          <option value='Votant'>Votant</option>
                        </select>";
                }
            } else if (ConnexionUtilisateur::estAdministrateur() && !isset($lien)) {
                echo "<label for='role-select' class='form-label'>Choix du rôle</label>
                        <select name='role' class='form-select mb-3' id='role-select' onchange='selectOrganisateur()'>
                          <option value='ResponsableP'>Responsable de proposition</option>
                          <option value='Organisateur'>Organisateur</option>       
                          <option value='Votant'>Votant</option>
                          <option value='Auteur'>Auteur</option>
                        </select>";
                echo "</div>";

                echo "<div id='question'>";
                echo "<label for='question-select' class='form-label'>Choix de la question</label>
                        <select name='idQuestion' class='form-select mb-3' id='question-select'>";

                foreach ($questions as $question) {
                    $intituleQuestionHTML = htmlspecialchars($question->getIntituleQuestion());
                    $idQuestionHTML = htmlspecialchars($question->getIdQuestion());
                    echo " <option value=\"$idQuestionHTML\">$intituleQuestionHTML</option>";
                }
                echo "</select>";
                echo "</div>";

                echo "<div id='proposition'>";

                echo "</div>";
            } else if (ConnexionUtilisateur::estResponsablePsurP($idProposition) || (ConnexionUtilisateur::estAdministrateur() && isset($lien))) {
                echo "<label for='role-select' class='form-label'>Choix du rôle</label>
                        <select name='role' class='form-select mb-3' id='role-select'>
                          <option value='Auteur'>co-auteur</option>
                        </select>";
            }
            ?>

            <script>
                function selectOrganisateur() {
                    const selectRoleValue = document.getElementById("role-select").value;
                    let compareStrings = compare(selectRoleValue, "Organisateur");
                    const selectQuestion = document.getElementById("question");
                    if (compareStrings === 0) {
                        selectQuestion.style.display = "none";
                    } else {
                        selectQuestion.style.display = "block";
                    }
                }

                function compare(x, y) {
                    return (x < y ? -1 : (x > y ? 1 : 0))
                }
            </script>
            <div class="d-flex justify-content-center pt-3">
                <input class="btn btn-lg btn-primary" type="submit" value="Envoyer"/>
            </div>
    </fieldset>
</form>
