<?php

namespace App\AgoraScript\Controller;

use App\AgoraScript\Config\Conf;
use App\AgoraScript\Lib\ConnexionUtilisateur;
use App\AgoraScript\Lib\MessageFlash;
use App\AgoraScript\Model\Repository\ReponseRepository;

class ControllerReponse extends Controller
{

    public static function readAll(int $etat): void
    {
        // TODO: Implement readAll() method.
    }

    public static function created(): void
    {
        $url = Conf::getUrlBase();
        if (!isset($_POST['message']) || !isset($_POST['idProposition']) || !isset($_POST['idQuestion']) || !isset($_POST['idCommentaire'])) {
            MessageFlash::ajouter("warning", "Il manque des informations !");
            self::redirect($url . "frontController.php?controller=question&action=readAll&etat=0");
        } else {
            $idQuestion = $_POST['idQuestion'];
            $idProposition = $_POST['idProposition'];
            $message = $_POST['message'];
            $idCommentaire = $_POST['idCommentaire'];
            if (!ConnexionUtilisateur::estAuteur($idProposition) && !ConnexionUtilisateur::estResponsableP($idQuestion) && !ConnexionUtilisateur::estAdministrateur()) {
                MessageFlash::ajouter("danger", "Vous n'avez pas le droit de poster des commentaires ici !");
                self::redirect($url . "frontController.php?controller=proposition&action=read&id=$idProposition&idQuestion=$idQuestion");
            } else {
                $login = ConnexionUtilisateur::getLoginUtilisateurConnecte();
                $date = date("d-m-Y H:i:s");
                $reponse = (new ReponseRepository())->construire(["idReponse" => NULL, "idCommentaire" => $idCommentaire, "login" => $login, "message" => $message, "datePoste" => $date]);
                (new ReponseRepository())->save($reponse);
                MessageFlash::ajouter("success", "Réponse postée !");
                self::redirect($url . "frontController.php?controller=proposition&action=read&id=$idProposition&idQuestion=$idQuestion");
            }
        }
    }

    //creation d'une reponse de la part d'un auteur pour un commentaire

    protected function getNomVueError(): string
    {
        return "reponse";
    }
}