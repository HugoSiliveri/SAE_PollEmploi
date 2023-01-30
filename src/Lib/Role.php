<?php

namespace App\AgoraScript\Lib;


abstract class Role
{
    const Administrateur = "Administrateur";
    const Organisateur = "Organisateur";
    const ResponsableP = "ResponsableP";
    const Votant = "Votant";
    const Auteur = "Auteur";
    const Expert = "Expert";

    public static function getRoles(string $role)
    {
        switch ($role) {
            case "Administrateur" :
                $type = Role::Administrateur;
                break;
            case "Organisateur":
                $type = Role::Organisateur;
                break;
            case "ResponsableP":
                $type = Role::ResponsableP;
                break;
            case "Votant":
                $type = Role::Votant;
                break;
            case "Auteur":
                $type = Role::Auteur;
                break;
            case "Expert":
                $type = Role::Expert;
                break;
            default:
                $type = "";
        }
        return $type;
    }

}
