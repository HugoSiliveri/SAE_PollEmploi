<?php

namespace App\AgoraScript\Controller;

use App\AgoraScript\Config\Conf;
use App\AgoraScript\Lib\ConnexionUtilisateur;
use App\AgoraScript\Lib\MessageFlash;
use App\AgoraScript\Model\Repository\ChatRepository;

class ControllerChat extends Controller
{

    public static function readAll(int $etat): void
    {
        // TODO: Implement readAll() method.
    }

    public static function add()
    {
        $url = Conf::getUrlBase();
        if (!isset($_POST["idProposition"])) {
            MessageFlash::ajouter("warning", "Id de la proposition manquant !");
            self::redirect($url . "frontController.php?controller=question&action=readAll&etat=0");
        } else {
            $login = ConnexionUtilisateur::getLoginUtilisateurConnecte();
            $date = date("d-m-Y H:i:s");
            $msg = (isset($_POST["message"])) ? $_POST["message"] : " ";
            $msgChat = (new ChatRepository())->construire(["idMessage" => NULL, "idProposition" => $_POST["idProposition"], "login" => $login, "dateMessage" => $date, "message" => $msg]);
            var_dump($msgChat);
            (new ChatRepository())->save($msgChat);
            MessageFlash::ajouter("success", "Message envoyé !");
            $_GET["idProposition"] = $_POST["idProposition"];
            self::read();
        }
    }

    //affichage de la page du chat

    public static function read()
    {
        $url = Conf::getUrlBase();
        if (!isset($_GET["idProposition"])) {
            MessageFlash::ajouter("warning", "Id de la proposition manquant !");
            self::redirect($url . "frontController.php?controller=question&action=readAll&etat=0");
        } else {
            $idProposition = $_GET["idProposition"];

            $messages = (new ChatRepository())->selectAllMessagesPourUneProposition($idProposition);

            self::afficheVue("view.php", $url, ["idProposition" => $idProposition, "msgProposition" => $messages, "pagetitle" => "Chat", "cheminVueBody" => "proposition/chatProposition.php"]);
        }

    }

    //ajout d'un message sur le chat actif

    protected function getNomVueError(): string
    {
        return "chat";
    }
}