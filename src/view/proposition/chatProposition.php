<!--page du chat-->
<div class="d-flex flex-column bg-white p-5 rounded">
    <?php

    use App\AgoraScript\Lib\ConnexionUtilisateur;

    $loginPrecedent = " "; // on garde le login du message precedent pour rassembler tous les messages du meme utilisateur ensemble
    foreach ($msgProposition as $msgProp) {
        $login = $msgProp->getLogin();
        if ($loginPrecedent != " " && $login != $loginPrecedent) {
            echo "</div>";
        }
        if ($login == ConnexionUtilisateur::getLoginUtilisateurConnecte()) {
            if ($login != $loginPrecedent) {
                echo "<div class=\"text-end d-flex flex-column ms-5 rounded-5 border border-5 border-success\" >";
                echo "<div class=\"rounded-bottom rounded-4 bg-success fs-3 fw-bold pe-3\">" . $msgProp->getLogin() . "</div>";
            }
            echo "<div class=\"text-center fs-4\"> " . $msgProp->getMessage() . "</div>";
            echo "<div class=\"ps-2 text-start fs-6 fst-italic\">" . $msgProp->getDateMessage() . "</div>";
        } else {
            if ($login != $loginPrecedent) {
                echo "<div class=\"d-flex flex-column me-5 rounded-5 border border-5 border-info\" >";
                echo "<div class=\"rounded-bottom rounded-4 bg-info fs-3 fw-bold ps-3\">" . $msgProp->getLogin() . "</div>";
            }
            echo "<div class=\"text-center fs-4\"> " . $msgProp->getMessage() . "</div>";
            echo "<div class=\"pe-2 text-end fs-6 fst-italic\">" . $msgProp->getDateMessage() . "</div>";
        }
        $loginPrecedent = $login;
    }
    echo "</div>";

    ?>
    <!-- form pour envoyer un message-->
    <div class="col-md-12">
        <form method="post" action="frontController.php" ...>
            <fieldset class="bg-white d-flex flex-column p-5 rounded">
                <textarea name="message" required class="form-control" id="mainComment" placeholder="..." cols="20"
                          rows="10"></textarea>
                <div class="d-flex justify-content-end p-1">
                    <input class="btn-primary btn" type="submit" value="envoyer"/>
                </div>
                <?php
                $idPropositionHTML = htmlspecialchars($idProposition);
                echo "<input type=\"hidden\" name=\"idProposition\" value=\"$idPropositionHTML\">";
                ?>
                <input type="hidden" name="action" value="add">
                <input type="hidden" name="controller" value="chat">
            </fieldset>
        </form>
    </div>
</div>