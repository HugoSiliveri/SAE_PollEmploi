<?php

namespace App\AgoraScript\Config;

//https://webinfo.iutmontp.univ-montp2.fr/~royov/sae-s3/web/

class Conf
{
    static private array $databases = array(
        'hostname' => 'webinfo.iutmontp.univ-montp2.fr',
        'database' => 'jalbaudl', // TODO
        'login' => 'jalbaudl', // TODO
        'password' => 'lucas1234e', // TODO
        'dureeExpiration' => 3600 //Les sessions expirent après 3600 secondes
    );

    //systeme d'auto redirection pour pouvoir passer d'une personne a l'autre
    static private array $noms = array(
        "Vincent" => "http://localhost:8080/sae-s3/web/",
        "Hugo" => "https://webinfo.iutmontp.univ-montp2.fr/~siliverih/sae-s3/web/",
        "Sebastien" => "http://localhost/sae/web/",
        "Lucas" => "http://localhost/sae/web/",
        "Loris" => "http://localhost/sae-s3/web/"
    );

    static private string $quiSuisJe = "Hugo";

    public static function getUrlBase(): string
    {
        return static::$noms[static::$quiSuisJe];
    }

    static public function getDureeExpiration(): int
    {
        return static::$databases['dureeExpiration'];
    }

    // static string $url = static::$noms["Vincent"];


    static public function getLogin(): string
    {
        return static::$databases['login'];
    }

    static public function getHostname(): string
    {
        return static::$databases['hostname'];
    }

    static public function getDatabase(): string
    {
        return static::$databases['database'];
    }

    static public function getPassword(): string
    {
        return static::$databases['password'];
    }
}