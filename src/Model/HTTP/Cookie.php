<?php

namespace App\AgoraScript\Model\HTTP;

class Cookie
{
    public static function enregistrer(string $cle, $valeur, ?int $dureeExpiration = null): void
    {
        $valeurString = serialize($valeur);
        if (is_null($dureeExpiration)) setcookie($cle, $valeurString, 0);
        else setcookie($cle, $valeurString, time() + $dureeExpiration);
    }

    public static function lire(string $cle): string
    {
        return unserialize($_COOKIE[$cle]);
    }

    public static function contient(string $cle): bool
    {
        return isset($_COOKIE[$cle]);
    }

    public static function supprimer(string $cle): void
    {
        unset($_COOKIE[$cle]);
        setcookie($cle, "", 1);
    }
}