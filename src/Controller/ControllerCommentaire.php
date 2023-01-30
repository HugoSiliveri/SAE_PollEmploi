<?php

namespace App\AgoraScript\Controller;

use App\AgoraScript\Config\Conf;
use App\AgoraScript\Lib\ConnexionUtilisateur;
use App\AgoraScript\Lib\MessageFlash;
use App\AgoraScript\Model\Repository\CommentaireRepository;

class ControllerCommentaire extends Controller
{

    public static function readAll(int $etat): void
    {
        // TODO: Implement readAll() method.
    }

    public static function created(): void
    {
        $url = Conf::getUrlBase();
        if (!isset($_POST['message']) || !isset($_POST['idProposition']) || !isset($_POST['idQuestion'])) {
            MessageFlash::ajouter("warning", "Il manque des informations !");
            self::redirect($url . "frontController.php?controller=question&action=readAll&etat=0");
        } else {
            $idQuestion = $_POST['idQuestion'];
            $idProposition = $_POST['idProposition'];
            $message = $_POST['message'];
            if (!ConnexionUtilisateur::estAuteur($idProposition) && !ConnexionUtilisateur::estExpert() && !ConnexionUtilisateur::estResponsableP($idQuestion) && !ConnexionUtilisateur::estAdministrateur()) {
                MessageFlash::ajouter("danger", "Vous n'avez pas le droit de poster des commentaires ici !");
                self::redirect($url . "frontController.php?controller=proposition&action=read&id=$idProposition&idQuestion=$idQuestion");
            } else {
                $login = ConnexionUtilisateur::getLoginUtilisateurConnecte();
                $date = date("d-m-Y H:i:s");
                $commentaire = (new CommentaireRepository())->construire(["idCommentaire" => NULL, "login" => $login, "idProposition" => $idProposition, "message" => $message, "datePoste" => $date, "modifier" => 0]);
                (new CommentaireRepository())->save($commentaire);
                MessageFlash::ajouter("success", "Commentaire posté !");
                self::redirect($url . "frontController.php?controller=proposition&action=read&id=$idProposition&idQuestion=$idQuestion");
            }
        }
    }

    //ajout d'un commentaire

    public static function updated(): void
    {
        $url = Conf::getUrlBase();
        if (!isset($_POST['idCommentaire']) || !isset($_POST['idProposition']) || !isset($_POST['message']) || !isset($_POST['idQuestion'])) {
            MessageFlash::ajouter("warning", "Il manque des informations !");
            self::redirect($url . "frontController.php?controller=question&action=readAll&etat=0");
        } else {
            $idQuestion = $_POST['idQuestion'];
            $idProposition = $_POST['idProposition'];
            $idCommentaire = $_POST['idCommentaire'];
            $message = $_POST['message'];
            if (!ConnexionUtilisateur::estAuteur($idProposition)&& !ConnexionUtilisateur::estExpert() && !ConnexionUtilisateur::estResponsableP($idQuestion) && !ConnexionUtilisateur::estAdministrateur()) {
                MessageFlash::ajouter("danger", "Vous n'avez pas le droit de modifier des commentaires ici !");
                self::redirect($url . "frontController.php?controller=proposition&action=read&id=$idProposition&idQuestion=$idQuestion");
            } else if (!ConnexionUtilisateur::estAuteurCommentaire($idCommentaire)) {
                MessageFlash::ajouter("danger", "Vous n'avez pas le droit de modifier un commentaire qui n'est pas le votre !");
                self::redirect($url . "frontController.php?controller=proposition&action=read&id=$idProposition&idQuestion=$idQuestion");
            } else {
                $commentaire = (new CommentaireRepository())->select($idCommentaire);
                $commentaire->setMessage($message);
                $commentaire->setModifier(1);
                (new CommentaireRepository())->update($commentaire);
                MessageFlash::ajouter("success", "Commentaire modifié !");
                self::redirect($url . "frontController.php?controller=proposition&action=read&id=$idProposition&idQuestion=$idQuestion");
            }
        }
    }

    //modification d'un commentaire

    public static function update(): void
    {
        $url = Conf::getUrlBase();
        if (!isset($_POST['idQuestion']) || !isset($_POST['idProposition']) || !isset($_POST['idCommentaire'])) {
            MessageFlash::ajouter("warning", "Il manque des informations !");
            self::redirect($url . "frontController.php?controller=question&action=readAll&etat=0");
        } else {
            $idQuestion = $_POST['idQuestion'];
            $idProposition = $_POST['idProposition'];
            $idCommentaire = $_POST['idCommentaire'];
            if (!ConnexionUtilisateur::estAuteur($idProposition) && !ConnexionUtilisateur::estExpert() && !ConnexionUtilisateur::estResponsableP($idQuestion) && !ConnexionUtilisateur::estAdministrateur()) {
                MessageFlash::ajouter("danger", "Vous n'avez pas le droit de modifier des commentaires ici !");
                self::redirect($url . "frontController.php?controller=proposition&action=read&id=$idProposition&idQuestion=$idQuestion");
            } else if (!ConnexionUtilisateur::estAuteurCommentaire($idCommentaire)) {
                MessageFlash::ajouter("danger", "Vous n'avez pas le droit de modifier un commentaire qui n'est pas le votre !");
                self::redirect($url . "frontController.php?controller=proposition&action=read&id=$idProposition&idQuestion=$idQuestion");
            } else {
                $commentaire = (new CommentaireRepository())->select($idCommentaire);
                self::afficheVue('view.php', Conf::getUrlBase(), ["commentaire" => $commentaire, "idQuestion" => $idQuestion, "pagetitle" => "Mise à jour du commentaire", "cheminVueBody" => "commentaire/update.php"]);
            }
        }
    }

    //redirection apres la modification

    public static function delete(): void
    {
        $url = Conf::getUrlBase();
        if (!isset($_POST['idCommentaire']) || !isset($_POST['idProposition']) || !isset($_POST['idQuestion'])) {
            MessageFlash::ajouter("warning", "Il manque des informations !");
            self::redirect($url . "frontController.php?controller=question&action=readAll&etat=0");
        } else {
            $idQuestion = $_POST['idQuestion'];
            $idProposition = $_POST['idProposition'];
            $idCommentaire = $_POST['idCommentaire'];
            $message = $_POST['message'];
            if (!ConnexionUtilisateur::estAuteur($idProposition) && !ConnexionUtilisateur::estExpert() && !ConnexionUtilisateur::estResponsableP($idQuestion) && !ConnexionUtilisateur::estAdministrateur()) {
                MessageFlash::ajouter("danger", "Vous n'avez pas le droit de modifier des commentaires ici !");
                self::redirect($url . "frontController.php?controller=proposition&action=read&id=$idProposition&idQuestion=$idQuestion");
            } else if (!ConnexionUtilisateur::estAuteurCommentaire($idCommentaire)) {
                MessageFlash::ajouter("danger", "Vous n'avez pas le droit de modifier un commentaire qui n'est pas le votre !");
                self::redirect($url . "frontController.php?controller=proposition&action=read&id=$idProposition&idQuestion=$idQuestion");
            } else {
                (new CommentaireRepository())->delete($idCommentaire);
                MessageFlash::ajouter("success", "Commentaire supprimé !");
                self::redirect($url . "frontController.php?controller=proposition&action=read&id=$idProposition&idQuestion=$idQuestion");
            }
        }
    }

    //supression d'un commentaire

    protected function getNomVueError(): string
    {
        return "commentaire";
    }


}