<?php
$loginHTML = htmlspecialchars($loginU);
$intituleHTML = htmlspecialchars($intitule);
$roleHTML = htmlspecialchars($role);
$loginURL = rawurlencode($loginU);
?>
    <div class="bg-white d-flex flex-column p-5 rounded">
    <div>
        Demande de l'utilisateur : <?= $loginHTML ?>
    </div>
    <div>
        Pour la question : <?= $intituleHTML ?>
    </div>
    <div>
        Pour le rôle : <?= $roleHTML ?>
    </div>
<?php
if (!is_null($idProposition)) {
    $idPropositionHTML = htmlspecialchars($idProposition);
    echo "<div>
        Pour la proposition : $idPropositionHTML
        </div>";
}


echo "<div class=\"d-flex flex-row justify-content-evenly\">";

echo '<form method="post" action="frontController.php" class= ...>
          <input type="hidden" name="action" value="choix">
          <input type="hidden" name="controller" value="utilisateur">
          <input type="hidden" name="login"' . "value=\"$loginHTML\"> 
          <input type=\"hidden\" name=\"choix\" value=\"1\">
          <input class=\"btn btn-sm btn-primary\" type=\"submit\" value=\"Accepter la demande\"/> </form>";

echo '<form method="post" action="frontController.php" ...>
          <input type="hidden" name="action" value="choix">
          <input type="hidden" name="controller" value="utilisateur">
          <input type="hidden" name="login"' . "value=\"$loginHTML\"> 
          <input type=\"hidden\" name=\"choix\" value=\"0\">
          <input class=\"btn btn-sm btn-primary\" type=\"submit\" value=\"Refuser la demande\"/> </form>";

echo "</div>";
echo "</div>";