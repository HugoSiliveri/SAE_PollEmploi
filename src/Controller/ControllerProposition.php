<?php

namespace App\AgoraScript\Controller;

use App\AgoraScript\Config\Conf;
use App\AgoraScript\Lib\ConnexionUtilisateur;
use App\AgoraScript\Lib\MessageFlash;
use App\AgoraScript\Lib\PreferenceController;
use App\AgoraScript\Lib\Role;
use App\AgoraScript\Lib\TypeVote;
use App\AgoraScript\Model\Repository\AffectationsPropositionsRepository;
use App\AgoraScript\Model\Repository\AffectationsRepository;
use App\AgoraScript\Model\Repository\CommentaireRepository;
use App\AgoraScript\Model\Repository\LikeRepository;
use App\AgoraScript\Model\Repository\NombreVoteRepository;
use App\AgoraScript\Model\Repository\PropositionRepository;
use App\AgoraScript\Model\Repository\QuestionRepository;
use App\AgoraScript\Model\Repository\ReponseRepository;
use App\AgoraScript\Model\Repository\SectionPropositionRepository;
use App\AgoraScript\Model\Repository\UtilisateurRepository;
use App\AgoraScript\Model\Repository\VersionSectionPropositionRepository;
use App\AgoraScript\Model\Repository\VoteRepository;

class ControllerProposition extends Controller
{

    public static function read(): void
    {
        $url = Conf::getUrlBase();

        if (!isset($_GET["id"]) || !isset($_GET["idQuestion"])) {
            MessageFlash::ajouter("warning", "Id de la proposition ou de la question manquant !");
            self::redirect($url . "frontController.php?controller=question&action=readAll&etat=0");
        } else {
            $idProposition = $_GET["id"];

            $idQuestion = $_GET["idQuestion"];

            $proposition = (new PropositionRepository())->select($idProposition);


            /*
            if (ConnexionUtilisateur::estConnecte()){
                $acces = (new UtilisateurRepository())->estAffecte(ConnexionUtilisateur::getLoginUtilisateurConnecte(), $idQuestion);
            }else{
                $acces = false;
            }
            */

            $versionExisteParSection = array();

            $sections = (new PropositionRepository())->getSectionsProposition($idProposition);

            $etatQuestion = (new QuestionRepository())->select($idQuestion)->getEtatQuestion();

            $nbLike = array();
            foreach ($sections as $section) {
                $idSection = $section->getIdSectionProposition();
                $versionExisteParSection[$idSection] = (new VersionSectionPropositionRepository())->existeVersion($idSection);
                $nbLike[$idSection] = (new LikeRepository())->nbLike($idSection);
            }

            if ($etatQuestion == 1) {
                if (ConnexionUtilisateur::estConnecte()) {
                    if ( !(ConnexionUtilisateur::estResponsablePsurP($idProposition) || ConnexionUtilisateur::estAuteur($idProposition) )&& (ConnexionUtilisateur::estVotant($idQuestion) || ConnexionUtilisateur::estSansRole())) {
                        MessageFlash::ajouter("info", "Attendez que les propositions soient fini avant de pouvoir les consulter");
                        self::redirect($url . "frontController.php?controller=question&action=read&id=$idQuestion");
                    }
                } else {
                    MessageFlash::ajouter("info", "Attendez que les propositions soient fini avant de pouvoir les consulter");
                    self::redirect($url . "frontController.php?controller=question&action=read&id=$idQuestion");
                }
            }
            $nbCommentaires = (new CommentaireRepository())->getNombreCommentaires($idProposition);


            $reponsesTab = array();
            $commentaires = (new CommentaireRepository())->selectAllCommentairesPourUneProposition($idProposition);

            for ($i = 0; $i < sizeof($commentaires); $i++) {
                $idCommentaire = $commentaires[$i]->getIdCommentaire();
                $reponses = (new ReponseRepository())->getReponsesPourCommentaire($idCommentaire);
                $nbCommentaires += sizeof($reponses);
                if (sizeof($reponses) == 0) {
                    $reponses = null;
                }
                $reponsesTab[] = $reponses;
            }

            self::afficheVue('view.php', Conf::getUrlBase(), ["nbLike" => $nbLike, "commentaires" => $commentaires, "reponses" => $reponsesTab, "nbCommentaires" => $nbCommentaires, "versionExisteParSection" => $versionExisteParSection, "idQuestion" => $idQuestion, "etatQuestion" => $etatQuestion, "proposition" => $proposition, "sections" => $sections, "pagetitle" => "Details de la proposition", "cheminVueBody" => "proposition/detailProposition.php"]);
        }
    }

    //affichage de la page avec toutes les informations pour les propositions

    public static function vote(): void
    {
        //reception des informations
        $idQuestion = $_GET['id'];
        if (is_null($idQuestion)) (new ControllerQuestion())->error("echec du vote");
        $questionSelect = (new QuestionRepository())->select($idQuestion);
        $question = $questionSelect->getIntituleQuestion();
        $tabPropositions = (new QuestionRepository())->getPropositionsTab($idQuestion);
        $nbProp = sizeof($tabPropositions);
        $login = $_GET['login'];

        //redirection
        if (!(new VoteRepository())->aVote($login, $idQuestion)) {

            $pref = $questionSelect->getTypeVote();

            if (strcmp($pref, TypeVote::SCRUTIN_MAJORITAIRE) == 0) {
                self::afficheVue('view.php', Conf::getUrlBase(), ["idQuestion" => $idQuestion, "login" => $login, "tabPropositions" => $tabPropositions, "question" => $question, "pagetitle" => "Vote pour 1 proposition", "cheminVueBody" => "proposition/voteOne.php"]);
            } else if (strcmp($pref, TypeVote::VOTE_CUMULATIF) == 0) {
                self::afficheVue('view.php', Conf::getUrlBase(), ["idQuestion" => $idQuestion, "login" => $login, "nbProp" => $nbProp, "pagetitle" => "Choix du nombre de proposition à voter", "cheminVueBody" => "proposition/choixNombreProp.php"]);
            } else {
                self::afficheVue('view.php', Conf::getUrlBase(), ["idQuestion" => $idQuestion, "login" => $login, "nbProp" => $nbProp, "tabPropositions" => $tabPropositions, "question" => $question, "pagetitle" => "Vote par valeur", "cheminVueBody" => "proposition/voteParValeur.php"]);
            }
        }
    }

    //affichage de la page de vote en fonction de son type de vote

    public static function voteCumulatif(): void
    {
        $idQuestion = $_POST['idQuestion'];
        $nbProp = $_POST['nbProp'];
        $questionSelect = (new QuestionRepository())->select($idQuestion);
        $question = $questionSelect->getIntituleQuestion();
        $tabPropositions = (new QuestionRepository())->getPropositionsTab($idQuestion);

        self::afficheVue('view.php', Conf::getUrlBase(), ["idQuestion" => $idQuestion, "tabPropositions" => $tabPropositions, "question" => $question, "nbProp" => $nbProp, "pagetitle" => "Vote cumulatif", "cheminVueBody" => "proposition/voteCumulatif.php"]);
    }

    public static function votedMajoritaire(): void
    {
        $idQuestionURL = urlencode($_POST['id']);
        $url = Conf::getUrlBase();
        $prop = $_POST['proposition1'];
        $login = $_POST['login'];
        $idQuestion = $_POST['id'];

        if (!(new VoteRepository())->aVote($login, $idQuestion)) {
            if (empty($prop)) {
                MessageFlash::ajouter("danger", "Vous devez remplir tous les champs !");
                self::redirect($url . "frontController.php?controller=proposition&action=vote&id=$idQuestionURL");
            } else {
                (new PropositionRepository())->attribuerPoints($prop, 1);
                $vote = (new VoteRepository())->construire(["login" => $login, "idQuestion" => $_POST['id']]);
                (new VoteRepository())->save($vote);
            }
            MessageFlash::ajouter("success", "Votre vote a bien été enregistré !");
            self::redirect($url . "frontController.php?controller=question&action=readAll&etat=7");
        }
    }

    public static function votedCumulatif(): void
    {

        $nbProposition = $_POST['nbProp'];
        $url = Conf::getUrlBase();
        $tab = [];
        $idQuestionURL = urlencode($_POST['id']);
        $login = $_POST['login'];
        $idQuestion = $_POST['id'];

        if (!(new VoteRepository())->aVote($login, $idQuestion)) {
            for ($i = 0; $i < $nbProposition; $i++) {
                $tab[] = $_POST["proposition$i"];
            }

            $j = 0;

            foreach ($tab as $prop) {
                if (empty($prop)) {
                    MessageFlash::ajouter("danger", "Vous devez remplir tous les champs !");
                    self::redirect($url . "frontController.php?controller=proposition&action=vote&id=$idQuestionURL");
                } else if (count(array_keys($tab, $prop)) > 1) {
                    MessageFlash::ajouter("warning", "Vous ne pouvez pas voter plus d'une fois pour la même proposition !");
                    self::redirect($url . "frontController.php?controller=proposition&action=vote&id=$idQuestionURL");
                } else {
                    if ($j >= 5) (new PropositionRepository())->attribuerPoints($prop, 1);
                    else (new PropositionRepository())->attribuerPoints($prop, 5 - $j);
                }
                $j++;
            }

            $vote = (new VoteRepository())->construire(["login" => $login, "idQuestion" => $_POST['id']]);
            (new VoteRepository())->save($vote);
            MessageFlash::ajouter("success", "Votre vote a bien été enregistré !");
            self::redirect($url . "frontController.php?controller=question&action=readAll&etat=7");
        }
    }

    public static function votedValeur(): void
    {
        $nbProposition = $_POST['nbProp'];
        $url = Conf::getUrlBase();
        $tab = [];
        $idQuestionURL = urlencode($_POST['id']);
        $login = $_POST['login'];
        $idQuestion = $_POST['id'];


        if (!(new VoteRepository())->aVote($login, $idQuestion)) {
            for ($i = 0; $i < $nbProposition; $i++) {
                $tab[] = $_POST["proposition$i"];
            }

            foreach ($tab as $prop) {
                if (empty($prop)) {
                    MessageFlash::ajouter("danger", "Vous devez remplir tous les champs !");
                    self::redirect($url . "frontController.php?controller=proposition&action=vote&id=$idQuestionURL");
                } else {
                    if (strcmp(substr($prop, -2), "TB") == 0) (new PropositionRepository())->voterParValeur(substr($prop, 0, -2), "nbTB");
                    else if (strcmp(substr($prop, -2), "BB") == 0) (new PropositionRepository())->voterParValeur(substr($prop, 0, -2), "nbBB");
                    else if (strcmp(substr($prop, -2), "AB") == 0) (new PropositionRepository())->voterParValeur(substr($prop, 0, -2), "nbAB");
                    else if (strcmp(substr($prop, -2), "PP") == 0) (new PropositionRepository())->voterParValeur(substr($prop, 0, -2), "nbPP");
                    else if (strcmp(substr($prop, -2), "II") == 0) (new PropositionRepository())->voterParValeur(substr($prop, 0, -2), "nbII");
                    else if (strcmp(substr($prop, -2), "AR") == 0) (new PropositionRepository())->voterParValeur(substr($prop, 0, -2), "nbAR");
                }
            }
            $vote = (new VoteRepository())->construire(["login" => $login, "idQuestion" => $idQuestion]);
            (new VoteRepository())->save($vote);
            MessageFlash::ajouter("success", "Votre vote a bien été enregistré !");

            self::redirect($url . "frontController.php?controller=question&action=readAll&etat=7");
        }
    }

    public static function updated(): void
    {
        $url = Conf::getUrlBase();
        $idQuestion = intval($_POST['idQuestion']);
        $idProposition = intval($_POST['id']);
        $proposition = (new PropositionRepository())->construire(["idProposition" => $idProposition, "idQuestion" => $idQuestion, "intituleProposition" => $_POST["intitule"]]);
        (new PropositionRepository())->update($proposition);

        $nbSections = intval($_POST['nbSections']);
        $question = (new QuestionRepository())->select($idQuestion);
        $propositions = (new QuestionRepository)->getPropositionsTab($idQuestion);

        //si l'utilisateur est responsable de proposition tester si il en a deja une
        $aDejaEcritUneProp = false;
        if (ConnexionUtilisateur::estResponsableP($question->getIdQuestion())) {
            foreach ($propositions as $proposition) {
                if ((new AffectationsPropositionsRepository())->estDejaAffecter($question->getIdQuestion(), Role::ResponsableP, ConnexionUtilisateur::getLoginUtilisateurConnecte(), $proposition->getIdProposition())) {
                    $aDejaEcritUneProp = true;
                    break;
                }

            }
        }

        //savoir si l'utilisateur a deja voté
        $aVote = false;
        if (ConnexionUtilisateur::estVotant($idQuestion)) {
            if ((new VoteRepository())->aVote(ConnexionUtilisateur::getLoginUtilisateurConnecte(), $idQuestion)) {
                $aVote = true;
            }
        }

        (new PropositionRepository())->update($proposition);

        $propositions = (new QuestionRepository)->getPropositionsTab($idQuestion);
        $question = (new QuestionRepository())->select($idQuestion);
        $sections = (new QuestionRepository())->getSectionsQuestion($idQuestion);

        for ($i = 1; $i < $nbSections; $i++) {
            $idSectionProposition = intval($_POST["idSectionProposition$i"]);
            $titreSectionProposition = $_POST["titreSectionProposition$i"];
            $sectionProposition = (new SectionPropositionRepository())->construire([
                "idSectionProposition" => $idSectionProposition,
                "titreSectionProposition" => $titreSectionProposition,
                "idProposition" => $idProposition,
                "idSectionQuestion" => intval($_POST["idSectionQuestion$i"]),
                "texteSectionProposition" => $_POST["texteSection$i"]
            ]);
            $version = (new VersionSectionPropositionRepository())->derniereVersion($idSectionProposition);
            if (strcmp($_POST["ancienTexte$i"], $_POST["texteSection$i"]) != 0) {
                $versionSection = (new VersionSectionPropositionRepository())->construire([
                    "version" => $version,
                    "idSectionProposition" => $idSectionProposition,
                    "titreSectionProposition" => $titreSectionProposition,
                    "texteSectionProposition" => $_POST["ancienTexte$i"]
                ]);

                (new VersionSectionPropositionRepository())->save($versionSection);
            }
            (new SectionPropositionRepository())->update($sectionProposition);
        }

        MessageFlash::ajouter("success", "Proposition mise à jour !");
        self::redirect($url . "frontController.php?controller=proposition&action=read&&id=$idProposition&idQuestion=$idQuestion");
    }

    //apres le bouton de modification sur la page de la proposition

    public static function update(): void
    {
        $url = Conf::getUrlBase();
        if (!isset($_POST['id']) || !isset($_POST['idQuestion'])) {
            MessageFlash::ajouter("warning", "Il manque des informations !");
            self::redirect($url . "frontController.php?controller=question&action=readAll&etat=0");
        } else {
            $idProp = $_POST['id'];
            $idQuestion = $_POST['idQuestion'];
            if (!ConnexionUtilisateur::estResponsableP($idQuestion) && !ConnexionUtilisateur::estAdministrateur()) {
                MessageFlash::ajouter("danger", "Vous n'avez pas le droit de modifier cette proposition !");
                $idQuestionHTML = htmlspecialchars($idQuestion);
                $idPropositionHTML = htmlspecialchars($idProp);
                self::redirect($url . "frontController.php?controller=proposition&action=read&id=$idPropositionHTML&idQuestion=$idQuestionHTML");
            } else {
                $proposition = (new PropositionRepository())->select($idProp);
                $sectionsProposition = (new PropositionRepository())->getSectionsProposition($idProp);
                self::afficheVue('view.php', Conf::getUrlBase(), ["sectionsProposition" => $sectionsProposition, "proposition" => $proposition, "pagetitle" => "Mise à jour de la proposition", "cheminVueBody" => "proposition/update.php"]);
            }
        }
    }

    //apres la modification

    public static function create(): void
    {
        $login = $_REQUEST['login'];
        $idQuestion = $_REQUEST['idQuestion'];
        $utilisateurs = (new UtilisateurRepository())->selectAll(null);
        $logins = [];
        $noms = [];
        foreach ($utilisateurs as $utilisateur) {
            if (!(new AffectationsRepository())->estDejaAffecter($idQuestion, Role::ResponsableP, $utilisateur->getLogin())) {
                $logins[] = $utilisateur->getLogin();
                $noms[] = $utilisateur->getNomUtilisateur();
            }
        }
        self::afficheVue('view.php', Conf::getUrlBase(), ["noms" => $noms, "logins" => $logins, "login" => $login, "idQuestion" => $idQuestion, "pagetitle" => "Formulaire création d'une proposition", "cheminVueBody" => "proposition/createProposition.php"]);
    }

    public static function created(): void
    {
        $url = Conf::getUrlBase();
        if (!isset($_POST['question']) || !isset($_POST['login'])) {
            MessageFlash::ajouter("warning", "Une erreur est survenue lors de la création de la proposition !");
            self::readAll(0);
        } else if (!ConnexionUtilisateur::estResponsableP($_POST['question']) && !ConnexionUtilisateur::estAdministrateur()) {
            MessageFlash::ajouter("danger", "Vous n'avez pas les droits pour créer une proposition !");
            self::readAll(0);
        } else {
            $nbAuteurs = $_POST['nb'];
            $loginResponsable = $_POST['login'];
            $idQuestion = $_POST['question'];
            $intituleProposition = $_POST['intitule'];
            $tousLesCoAuteursRemplis = true;
            $question = (new QuestionRepository())->select($idQuestion);
            $propositions = (new QuestionRepository)->getPropositionsTab($idQuestion);

            //si l'utilisateur est responsable de proposition tester si il en a deja une
            $aDejaEcritUneProp = false;
            if (ConnexionUtilisateur::estResponsableP($question->getIdQuestion())) {
                foreach ($propositions as $proposition) {
                    if ((new AffectationsPropositionsRepository())->estDejaAffecter($question->getIdQuestion(), Role::ResponsableP, ConnexionUtilisateur::getLoginUtilisateurConnecte(), $proposition->getIdProposition())) {
                        $aDejaEcritUneProp = true;
                        break;
                    }

                }
            }

            //savoir si l'utilisateur a deja voté
            $aVote = false;
            if (ConnexionUtilisateur::estVotant($idQuestion)) {
                if ((new VoteRepository())->aVote(ConnexionUtilisateur::getLoginUtilisateurConnecte(), $idQuestion)) {
                    $aVote = true;
                }
            }

            $doublons = array();
            for ($i = 0; $i < $nbAuteurs; $i++) {
                $login = $_POST['coAuteur' . $i];
                $doublons[] = $login;
                if (strcmp($login, "choisissez un co-auteur") == 0) {
                    $tousLesCoAuteursRemplis = false;
                }
            }
            if (count(array_unique($doublons)) != count($doublons)) {
                MessageFlash::ajouter("warning", "Vous ne pouvez pas mettre plusieurs fois le meme co-auteur !");
                self::redirect($url . "frontController.php?controller=proposition&action=create&idQuestion=$idQuestion&login=$loginResponsable");

            } else {

                if ($tousLesCoAuteursRemplis) {
                    $propositionForm = (new PropositionRepository())->construire(["idProposition" => NULL, "idQuestion" => $idQuestion, "intituleProposition" => $intituleProposition]); //Construction
                    (new PropositionRepository())->save($propositionForm);

                    $propositions = (new QuestionRepository)->getPropositionsTab($idQuestion);
                    $question = (new QuestionRepository())->select($idQuestion);
                    $sections = (new QuestionRepository())->getSectionsQuestion($idQuestion);

                    $typeVote = $question->getTypeVote();

                    $idProposition = (new PropositionRepository())->getIdProposition($intituleProposition, $idQuestion);

                    $AffectationProp = (new AffectationsPropositionsRepository())->construire(["login" => $loginResponsable, "idQuestion" => $idQuestion, "idProposition" => $idProposition, "role" => "ResponsableP"]);
                    (new AffectationsPropositionsRepository())->save($AffectationProp);

                    for ($i = 0; $i < $nbAuteurs; $i++) {
                        $login = $_POST['coAuteur' . $i];
                        $coAuteurs = (new UtilisateurRepository())->select($login);
                        $AffectationProp = (new AffectationsPropositionsRepository())->construire(["login" => $login, "idQuestion" => $idQuestion, "idProposition" => $idProposition, "role" => "Auteur"]);
                        (new AffectationsPropositionsRepository())->save($AffectationProp);
                    }

                    if (strcmp($typeVote, TypeVote::VOTE_PAR_VALEUR) == 0) {
                        $nbVotes = (new NombreVoteRepository())->construire(["idProposition" => $idProposition, "nbTB" => 0, "nbBB" => 0, "nbAB" => 0, "nbPP" => 0, "nbII" => 0, "nbAR" => 0]);
                        (new NombreVoteRepository())->save($nbVotes);
                    }

                    MessageFlash::ajouter("success", "Proposition créée !");
                    self::afficheVue('view.php', Conf::getUrlBase(), ["aVote" => $aVote, "aDejaEcritUneProp" => $aDejaEcritUneProp, "question" => $question, "sections" => $sections, "propositions" => $propositions, "pagetitle" => "Details de la question", "cheminVueBody" => "question/detailQuestion.php"]);
                } else {
                    MessageFlash::ajouter("warning", "Vous devez renseigner tous les champs !");
                    self::redirect($url . "frontController.php?controller=proposition&action=create&idQuestion=$idQuestion&login=$loginResponsable");
                }
            }
        }

    }

    public static function readAll(int $etat): void
    {
    }

    public static function delete(): void
    {
        $idProposition = $_POST['id'];
        if (is_null($idProposition)) (new ControllerProposition())->error("Echec de la suppression");
        $proposition = (new PropositionRepository())->select($idProposition);
        (new PropositionRepository())->delete($idProposition);
        $questions = (new QuestionRepository())->selectAll(3);
        MessageFlash::ajouter("success", "Proposition supprimée !");
        $url = Conf::getUrlBase();

        self::redirect($url . "frontController.php?controller=question&action=readAll&etat=1");
    }


    protected function getNomVueError(): string
    {
        // TODO: Implement getNomVueError() method.
        return "proposition";
    }


}