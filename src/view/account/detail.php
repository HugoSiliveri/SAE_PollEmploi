<!--info sur le compte-->
<?php

use App\AgoraScript\Lib\ConnexionUtilisateur;

$loginHTML = htmlspecialchars($utilisateur->getLogin());
$prenomHTML = htmlspecialchars($utilisateur->getPrenomUtilisateur());
$nomHTML = htmlspecialchars($utilisateur->getNomUtilisateur());
$adresseMailHTML = htmlspecialchars($utilisateur->getAdresseMail());
$loginURL = rawurlencode($utilisateur->getLogin());
?>
<div class="bg-white d-flex flex-column p-5 rounded">
    <p>
        Login : <?= $loginHTML ?>
    </p>
    <p>
        Nom : <?= $nomHTML ?>
    </p>
    <p>
        Prénom : <?= $prenomHTML ?>
    </p>
    <p>
        Adresse mail : <?= $adresseMailHTML ?>
    </p>
<?php
echo "<div class=\"d-flex flex-row justify-content-evenly\">";

echo '<form method="post" action="frontController.php" class= ...>
          <input type="hidden" name="action" value="update">
          <input type="hidden" name="controller" value="utilisateur">
          <input type="hidden" name="login"' . "value=\"$loginHTML\"> 
          <input class=\"btn btn-sm btn-primary\" type=\"submit\" value=\"Modifier Profil\"/> </form>";

echo '<form method="post" action="frontController.php" ...>
          <input type="hidden" name="action" value="delete">
          <input type="hidden" name="controller" value="utilisateur">
          <input type="hidden" name="login"' . "value=\"$loginHTML\"> 
          <input class=\"btn btn-sm btn-primary\" type=\"submit\" value=\"Supprimer Profil\"/> </form>";
if (ConnexionUtilisateur::demandeEnCours()) {
    echo '<form method="post" action="frontController.php" ...>
          <input type="hidden" name="action" value="voirDemande">
          <input type="hidden" name="controller" value="utilisateur">
          <input type="hidden" name="login"' . "value=\"$loginHTML\"> 
          <input class=\"btn btn-sm btn-primary\" type=\"submit\" value=\"Voir demande en cours\"/> </form>";
}

echo "</div>";
echo "</div>";