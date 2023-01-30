<?php

namespace App\AgoraScript\Lib;

use App\AgoraScript\Config\Conf;
use App\AgoraScript\Model\DataObject\Utilisateur;
use App\AgoraScript\Model\Repository\UtilisateurRepository;

class VerificationEmail
{
    public static function envoiEmailValidation(Utilisateur $utilisateur): void
    {
        $loginURL = rawurlencode($utilisateur->getLogin());
        $nonceURL = rawurlencode($utilisateur->getNonce());
        $absoluteURL = Conf::getUrlBase();
        $lienValidationEmail = "$absoluteURL" . "frontController.php?action=validerEmail&controller=utilisateur&login=$loginURL&nonce=$nonceURL";
        $corpsEmail = "<a href=\"$lienValidationEmail\">Validation</a>";

        mail($utilisateur->getAdresseMail(), "Validation", $corpsEmail);
        // Temporairement avant d'envoyer un vrai mail
        MessageFlash::ajouter("success", $corpsEmail);

    }

    public static function traiterEmailValidation($login, $nonce): bool
    {
        $utilisateur = (new UtilisateurRepository())->select($login);
        if (is_null($utilisateur)) {
            return false;
        } else {
            if (strcmp($nonce, $utilisateur->getNonce()) != 0) {
                return false;
            } else {
                $utilisateur->setNonce("");
                $utilisateur->setAdresseMail($utilisateur->getEmailAValider());
                (new UtilisateurRepository())->update($utilisateur);
            }
        }
        return true;
    }

    public static function aValideEmail(Utilisateur $utilisateur): bool
    {
        return strcmp("", $utilisateur->getAdresseMail()) != 0;
    }

}