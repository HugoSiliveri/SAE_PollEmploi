<!--page avec les informations autour d'une proposition-->
<script src="https://code.jquery.com/jquery-3.6.3.min.js"
        integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.js"></script>
<div class="d-flex flex-column align-items-center bg-white p-4 rounded">
    <h1><?php echo $proposition->getIntituleProposition() ?></h1>
    <h5 class="d-flex flex-column align-items-center w-100 mb-3 white-space text-justify">
        <?php

        use App\AgoraScript\Config\Conf;
        use App\AgoraScript\Lib\ConnexionUtilisateur;
        use App\AgoraScript\Model\Repository\AffectationsPropositionsRepository;
        use App\AgoraScript\Model\Repository\LikeRepository;
        use App\AgoraScript\Model\Repository\QuestionRepository;

        $auteurs = (new AffectationsPropositionsRepository())->getAuteurs($proposition->getIdProposition());
        if (sizeof($auteurs) > 1) {
            echo "Auteurs : " . implode(", ", $auteurs);
        } else {
            echo "Auteur : " . $auteurs[0];
        }
        ?>
    </h5>
    <?php

    $url = Conf::getUrlBase();
    $idPropositionHTML = htmlspecialchars($proposition->getIdProposition());
    $idQuestionHTML = htmlspecialchars($idQuestion);

    $i = 1;
    foreach ($sections as $section) {
        echo "<div class='d-flex flex-column w-100 mb-3 text-justify'>
                      <h4>" . $i . ") " . htmlspecialchars($section->getTitreSectionProposition()) . "</h4>
                      <textarea id='section$i' class='form-control'></textarea>
                  </div>
                  <script> 
                            var simplemde" . $i . " = new SimpleMDE({ element: document.getElementById( \"section" . $i . "\"), toolbar: false, status: false});
                            simplemde" . $i . ".value(" . json_encode($section->getTexteSectionProposition()) . ")
                            simplemde" . $i . ".togglePreview();                  
                  </script>";
        if (ConnexionUtilisateur::estAdministrateur() || ConnexionUtilisateur::estResponsableP($idQuestion)) {
            if ($versionExisteParSection[$section->getIdSectionProposition()]) {
                $idSectionPropositionHTML = htmlspecialchars($section->getIdSectionProposition());
                $idQuestionHTML = htmlspecialchars($idQuestion);
                echo "<form method=\"post\" action=\"frontController.php\">";
                echo "<input class='btn mb-5' type=\"submit\" value=\"Restaurer une version\">";
                echo "<input type=\"hidden\" name=\"action\" value=\"restore\">";
                echo "<input type=\"hidden\" name=\"controller\" value=\"sectionProposition\">";
                echo "<input type=\"hidden\" name=\"idSectionProposition\" value=\"$idSectionPropositionHTML\">";
                echo "<input type=\"hidden\" name=\"idQuestion\" value=\"$idQuestionHTML\">";
                echo "</form>";
            }
        }

        $idSectionPropositionHTML = htmlspecialchars($section->getIdSectionProposition());


        if (ConnexionUtilisateur::estConnecte()) {

            $nbLikeSection = $nbLike[$section->getIdSectionProposition()];
            echo "<div id='likeDiv' class='d-flex flex-column w-100 mb-3 text-justify'>";
            echo "<a href=\"$url" . "frontController.php?controller=sectionProposition&action=like&id=$idPropositionHTML&idQuestion=$idQuestionHTML&idSection=$idSectionPropositionHTML\" class=\"nav-link active\" id='likeLink'><img id='pouce$i' src=\"./img/like.png\" alt=\"informations\" title=\"enter\"></a>";
            echo "<p>$nbLikeSection</p>";
            echo "</div>";

            if ((new LikeRepository())->aLike($section->getIdSectionProposition(), ConnexionUtilisateur::getLoginUtilisateurConnecte())) {
                echo "
                <script>
                    document.getElementById('pouce$i').src = './img/pouce-bleu.png';
                </script>
                ";
            } else {
                echo "
                <script>
                    document.getElementById('pouce$i').src = './img/like.png';
                </script>
                ";
            }
        }
        $i++;
    }


    $accesResponsable = false;
    $accesAuteur = false;
    if (ConnexionUtilisateur::estResponsablePsurP($proposition->getIdProposition())) {
        $accesResponsable = true;
    }
    if (ConnexionUtilisateur::estAuteur($proposition->getIdProposition())) {
        $accesAuteur = true;
    }

    if ($etatQuestion == 1 || $etatQuestion == 5) {

        $idPropositionHTML = htmlspecialchars($proposition->getIdProposition());
        $idQuestionHTML = htmlspecialchars($idQuestion);
        $nbSectionsQuestion = sizeof((new QuestionRepository())->getSectionsQuestion($idQuestion));


        if (ConnexionUtilisateur::estAdministrateur() || $accesResponsable || $accesAuteur) {

            if (sizeof($sections) < $nbSectionsQuestion) {
                echo "<form method=\"post\" action=\"frontController.php\">";
                echo "<input class='btn mb-5' type=\"submit\" value=\"Rajouter une section\">";
                echo "<input type=\"hidden\" name=\"action\" value=\"create\">";
                echo "<input type=\"hidden\" name=\"controller\" value=\"sectionProposition\">";
                echo "<input type=\"hidden\" name=\"idProposition\" value=\"$idPropositionHTML\">";
                echo "<input type=\"hidden\" name=\"idQuestion\" value=\"$idQuestionHTML\">";
                echo "</form>";
            }


            echo "<div class='d-flex flex-row justify-content-evenly w-100'>
                <form method=\"post\" action=\"frontController.php\">
                <input class=\"btn btn-lg btn-primary\" type=\"submit\" value=\"Modifier\">
                <input type=\"hidden\" name=\"action\" value=\"update\">
                <input type=\"hidden\" name=\"controller\" value=\"proposition\">
                <input type=\"hidden\" name=\"id\" value=\"$idPropositionHTML\">
                <input type=\"hidden\" name=\"idQuestion\" value=\"$idQuestionHTML\">
                </form>";
            if (ConnexionUtilisateur::estAdministrateur()) {

                echo "<form method=\"post\" action=\"frontController.php\">
                    <input class=\"btn btn-lg btn-primary\" type=\"submit\" value=\"Supprimer\">
                    <input type=\"hidden\" name=\"action\" value=\"delete\">
                    <input type=\"hidden\" name=\"controller\" value=\"proposition\">
                    <input type=\"hidden\" name=\"id\" value=\"$idPropositionHTML\">
                    </form></div>";
            } else {
                echo "</div>";
            }
        }

        if (ConnexionUtilisateur::estAdministrateur() || $accesResponsable) {
            echo "<form method=\"post\" action=\"frontController.php\">
                    <input class='btn mb-5' type='submit' value='Rajouter un co-auteur'>
                    <input type=\"hidden\" name=\"action\" value=\"readAllResponsable\">
                    <input type=\"hidden\" name=\"controller\" value=\"utilisateur\">
                    <input type=\"hidden\" name=\"idQuestion\" value=\"$idQuestionHTML\">
                    <input type=\"hidden\" name=\"idProposition\" value=\"$idPropositionHTML\">
                    </form>";
        }
    }
    ?>

    <!--Bouton vers chat auteur-->
    <form method="get" action="frontController.php" ...>
        <?php
        $idPropositionHTML = htmlspecialchars($proposition->getIdProposition());
        echo "<input type=\"hidden\" name=\"idProposition\" value=\"$idPropositionHTML\">";
        ?>
        <input type="hidden" name="action" value="read">
        <input type="hidden" name="controller" value="chat">
        <input type="hidden" name="login" value="<?php echo ConnexionUtilisateur::getLoginUtilisateurConnecte() ?>">
        <?php
        if ($accesAuteur || $accesResponsable) echo "<input class=\"btn-primary btn\" type=\"submit\" value=\"Chat\"/>";
        ?>
    </form>


    <!--SYSTEME D'AJOUT DE COMMENTAIRE -->
    <?php
    if (ConnexionUtilisateur::estAdministrateur() && $etatQuestion == 3
        || ConnexionUtilisateur::estExpert() && $etatQuestion == 3
        || ConnexionUtilisateur::estExpert() && $etatQuestion == 2
        || ConnexionUtilisateur::EstAdministrateur() && $etatQuestion == 2
        || ConnexionUtilisateur::estAuteur($proposition->getIdProposition()) && $etatQuestion == 3
        || ConnexionUtilisateur::estResponsablePsurP($proposition->getIdProposition()) && $etatQuestion == 3) {

        if (ConnexionUtilisateur::estAdministrateur() && $etatQuestion == 3
            || ConnexionUtilisateur::estExpert() && $etatQuestion == 3
            || ConnexionUtilisateur::estExpert() && $etatQuestion == 2
            || ConnexionUtilisateur::EstAdministrateur() && $etatQuestion == 2){
            echo "
    <div class=\"container\">
        <div class=\"row\" style=\"margin-bottom: 20px\">
            <h2>Commentaires</h2>
        </div>
        <div class=\"row\">
            <div class=\"col-md-12\">
                <form method=\"post\" action=\"frontController.php\" ...>
                    <fieldset class=\"bg-white d-flex flex-column p-5 rounded\">
                        <textarea name=\"message\" class=\"form-control\" id=\"mainComment\" placeholder=\"Ecriver un commentaire...\" cols=\"30\" rows=\"10\"></textarea>
                        <div class=\"d-flex justify-content-end p-1\">
                            <input class=\"btn-primary btn\" type=\"submit\" value=\"Ajouter un commentaire\"/>
                        </div>";

            $idPropositionHTML = htmlspecialchars($proposition->getIdProposition());
            $idQuestionHTML = htmlspecialchars($idQuestion);
            echo "<input type=\"hidden\" name=\"idQuestion\" value=\"$idQuestionHTML\">
                              <input type=\"hidden\" name=\"idProposition\" value=\"$idPropositionHTML\">";

            echo "<input type=\"hidden\" name=\"action\" value=\"created\">
                        <input type=\"hidden\" name=\"controller\" value=\"commentaire\">
                    </fieldset>
                </form>
            </div>
        </div>
        <div class=\"row\">
                        
            <div class=\"col-md-12\">";

        }


        $nbCommentairesHTML = htmlspecialchars($nbCommentaires);
        echo "<h4>$nbCommentairesHTML Commentaires</h4>";

        echo "<div class=\"userComments\">";
        $i = 0;

        if (ConnexionUtilisateur::estAdministrateur() && $etatQuestion == 3
            || ConnexionUtilisateur::estExpert() && $etatQuestion == 3
            || ConnexionUtilisateur::estExpert() && $etatQuestion == 2
            || ConnexionUtilisateur::EstAdministrateur() && $etatQuestion == 2
            || ConnexionUtilisateur::estAuteur($proposition->getIdProposition()) && $etatQuestion == 3
            || ConnexionUtilisateur::estResponsablePsurP($proposition->getIdProposition()) && $etatQuestion == 3){
            foreach ($commentaires as $commentaire) {
                $loginHTML = htmlspecialchars($commentaire->getLogin());
                $messageHTML = htmlspecialchars($commentaire->getMessage());
                $dateHTML = htmlspecialchars($commentaire->getDatePoste());
                $idCommentaire = $commentaire->getIdCommentaire();
                $idCommentaireHTML = htmlspecialchars($idCommentaire);

                if ($commentaire->getModifier()) {
                    echo "<div class='comment'>
                                    <div class='user'>$loginHTML <span class='time'>$dateHTML (modifié)</span></div>
                                    <div class='userComment'>$messageHTML</div>";
                } else {
                    echo "<div class='comment'>
                                    <div class='user'>$loginHTML <span class='time'>$dateHTML</span></div>
                                    <div class='userComment'>$messageHTML</div>";
                }
        }




            if (ConnexionUtilisateur::estAuteurCommentaire($idCommentaire)) {
                echo "<form method=\"post\" action='frontController.php' ...>
                                     <input class=\"btn-primary btn\" type=\"submit\" value=\"Modifier le commentaire\"/>
                                     <input type=\"hidden\" name=\"idQuestion\" value=\"$idQuestionHTML\">
                                    <input type=\"hidden\" name=\"idProposition\" value=\"$idPropositionHTML\">
                                    <input type=\"hidden\" name=\"idCommentaire\" value=\"$idCommentaireHTML\">
                                    <input type=\"hidden\" name=\"action\" value=\"update\">
                                    <input type=\"hidden\" name=\"controller\" value=\"commentaire\">
                                    </fieldset>
                                    </form>
                                    </div>";


                echo "<form method=\"post\" action='frontController.php' ...>
                                     <input class=\"btn-primary btn\" type=\"submit\" value=\"Supprimer le commentaire\"/>
                                     <input type=\"hidden\" name=\"idQuestion\" value=\"$idQuestionHTML\">
                                    <input type=\"hidden\" name=\"idProposition\" value=\"$idPropositionHTML\">
                                    <input type=\"hidden\" name=\"idCommentaire\" value=\"$idCommentaireHTML\">
                                    <input type=\"hidden\" name=\"action\" value=\"delete\">
                                    <input type=\"hidden\" name=\"controller\" value=\"commentaire\">
                                    </fieldset>
                                    </form>
                                    </div>";
            }


            if (!is_null($reponses[$i])) {
                echo "<div class='replies'>";
                foreach ($reponses[$i] as $repons) {
                    $loginReponseHTML = htmlspecialchars($repons->getLogin());
                    $messageReponseHTML = htmlspecialchars($repons->getMessage());
                    $dateReponseHTML = htmlspecialchars($repons->getDatePoste());
                    echo "<div class='comment'>
                                    <div class='user'>$loginReponseHTML <span class='time'>$dateReponseHTML</span></div>
                                    <div class='userComment'>$messageReponseHTML</div>";
                    echo "</div>";
                }
                echo "</div>";
            }

            echo "</div>";

            if (ConnexionUtilisateur::estAuteur($proposition->getIdProposition()) && $etatQuestion == 3
                || ConnexionUtilisateur::estResponsablePsurP($proposition->getIdProposition()) && $etatQuestion == 3) {
                echo "<form method=\"post\" action='frontController.php' ...>
                                  <fieldset class='bg-white d-flex flex-column p-5 rounded'>
                                    <textarea name='message' class='form-control' id='mainReponse' placeholder='Ecriver une réponse...'></textarea>
                                    <div class=\"d-flex justify-content-end p-1\">
                            <input class=\"btn-primary btn\" type=\"submit\" value=\"Répondre au commentaire\"/>
                        <input type=\"hidden\" name=\"idQuestion\" value=\"$idQuestionHTML\">
                       <input type=\"hidden\" name=\"idProposition\" value=\"$idPropositionHTML\">
                       <input type=\"hidden\" name=\"idCommentaire\" value=\"$idCommentaireHTML\">
                    <input type=\"hidden\" name=\"action\" value=\"created\">
                    <input type=\"hidden\" name=\"controller\" value=\"reponse\">
                    </fieldset>
                    </form>";
            }

            ++$i;
        }
        echo "</div>
            </div>
        </div>
    </div>";
    }
    ?>
</div>

