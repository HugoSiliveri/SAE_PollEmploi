<?php
namespace App\AgoraScript\Controller;

use App\AgoraScript\Lib\MessageFlash;
use App\AgoraScript\Lib\PreferenceController;

abstract class Controller
{
    public static abstract function readAll(int $etat): void;

    public static function error(string $error = ""): void
    {
        $ancienURL = $_SERVER['HTTP_REFERER'];
        MessageFlash::ajouter("danger", $error);
        self::redirect($ancienURL);
        //$nomVueErreur =$this->getNomVueError();
        //self::afficheVue('view.php', Conf::getUrlBase(),["errorMessage" => $error, "pagetitle" => "Page d'erreur", "cheminVueBody" => "$nomVueErreur/error.php"]);
    }

    public static function redirect($url): void
    {
        header("Location: $url");
        exit();
    }

    protected static function afficheVue(string $cheminVue, string $url, array $parametres = []): void
    {
        extract($parametres);
        require __DIR__ . "/../view/$cheminVue";
    }

    protected abstract function getNomVueError(): string;

}


?>
