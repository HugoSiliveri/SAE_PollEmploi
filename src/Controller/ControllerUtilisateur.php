<?php

namespace App\AgoraScript\Controller;

use App\AgoraScript\Config\Conf;
use App\AgoraScript\Lib\ConnexionUtilisateur;
use App\AgoraScript\Lib\MessageFlash;
use App\AgoraScript\Lib\MotDePasse;
use App\AgoraScript\Lib\Role;
use App\AgoraScript\Lib\VerificationEmail;
use App\AgoraScript\Model\DataObject\Affectations;
use App\AgoraScript\Model\DataObject\AffectationsPropositions;
use App\AgoraScript\Model\DataObject\DemandeRole;
use App\AgoraScript\Model\DataObject\Utilisateur;
use App\AgoraScript\Model\Repository\AffectationsPropositionsRepository;
use App\AgoraScript\Model\Repository\AffectationsRepository;
use App\AgoraScript\Model\Repository\DemandeRoleRepository;
use App\AgoraScript\Model\Repository\QuestionRepository;
use App\AgoraScript\Model\Repository\UtilisateurRepository;

class ControllerUtilisateur extends Controller
{

    public static function readAllPourUserProps(int $etat): void
    {
        $url = Conf::getUrlBase();
        if (ConnexionUtilisateur::estAdministrateur() || ConnexionUtilisateur::estOrganisateur()) {
            $utilisateurs = (new UtilisateurRepository())->selectAll(null);
            if (isset($_POST['idQuestion'])) {
                $idQuestion = $_POST['idQuestion'];
                $votant = $_POST['votant'];
                self::afficheVue('view.php', Conf::getUrlBase(), ["utilisateurs" => $utilisateurs, "votant" => $votant, "idQuestion" => $idQuestion, "pagetitle" => "Gestion des utilisateurs", "cheminVueBody" => "account/listUsersPourProps.php"]);

            }/*else{
                $votant = "1";
                self::afficheVue('view.php', Conf::getUrlBase(), ["utilisateurs" => $utilisateurs, "votant" => $votant,"pagetitle" => "Gestion des utilisateurs", "cheminVueBody" => "account/listUsersPourProps.php"]);
            }*/
        } else {
            MessageFlash::ajouter("danger", "Vous n'avez pas les permissions pour accéder à cette page !");
            self::redirect($url . "frontController.php?controller=question&action=readAll&etat=0");
        }
    }

    public static function readAllResponsable(): void
    {
        $url = Conf::getUrlBase();
        if (isset($_POST['idProposition'])) {
            if (ConnexionUtilisateur::estAdministrateur() || ConnexionUtilisateur::estResponsablePsurP($_POST['idProposition'])) {
                $utilisateurs = (new UtilisateurRepository())->selectAll(null);
                $idProposition = $_POST['idProposition'];
                $idQuestion = $_POST['idQuestion'];
                self::afficheVue('view.php', Conf::getUrlBase(), ["idProposition" => $idProposition, "utilisateurs" => $utilisateurs, "idQuestion" => $idQuestion, "pagetitle" => "Gestion des utilisateurs", "cheminVueBody" => "account/listUsersPourProps.php"]);
            } else {
                MessageFlash::ajouter("danger", "Vous n'avez pas les permissions pour accéder à cette page !");
                self::redirect($url . "frontController.php?controller=question&action=readAll&etat=0");
            }
        }
    }

    public static function read(): void
    {
        $utilisateur = (new UtilisateurRepository())->select($_GET['login']);
        if (is_null($utilisateur)) {
            MessageFlash::ajouter("warning", "Il semblerait que cet utilisateur n'existe pas !");
        } else {
            self::afficheVue('view.php', Conf::getUrlBase(), ["utilisateur" => $utilisateur, "pagetitle" => "Informations du compte", "cheminVueBody" => "account/detail.php"]);
        }
    }

    public static function create(): void
    {
        self::afficheVue('view.php', Conf::getUrlBase(), ["pagetitle" => "Formulaire création compte", "cheminVueBody" => "account/createAccount.php"]);
    }

    public static function created(): void
    {
        $url = Conf::getUrlBase();
        $login = $_POST["login"];
        $loginURL = urlencode($login);

        if (!isset($_POST["login"]) || !isset($_POST["nomUtilisateur"]) || !isset($_POST["prenomUtilisateur"])) {

            MessageFlash::ajouter("danger", "Login, nom ou prénom manquant.");
            self::redirect("$url" . "frontController.php?controller=utilisateur&action=create");

        } else if (strcmp($_POST['mdp'], $_POST['mdp2']) != 0) {

            MessageFlash::ajouter("warning", "Mots de passe distincts");
            self::redirect("$url" . "frontController.php?controller=utilisateur&action=create");

        } else if (!is_null((new UtilisateurRepository())->select($_POST['login']))) {

            MessageFlash::ajouter("warning", "Login existant !");
            self::redirect("$url" . "frontController.php?controller=utilisateur&action=create");

        } else {
            //MessageFlash::ajouter("success", "L'utilisateur a été crée avec succèss");
            $utilisateursForm = Utilisateur::construireDepuisFormulaire($_POST);
            (new UtilisateurRepository())->save($utilisateursForm);
            VerificationEmail::envoiEmailValidation($utilisateursForm);
            self::redirect("$url" . "frontController.php?controller=utilisateur&action=read&login=$loginURL");
        }
    }

    public static function validerEmail(): void
    {
        $url = Conf::getUrlBase();
        if (!isset($_GET['login']) || !isset($_GET['nonce'])) {
            MessageFlash::ajouter("warning", "Login ou nom inexistant !");
            self::redirect($url . "frontController.php?action=readAll&controller=question&etat=0");
        } else {
            $login = $_GET['login'];
            $nonce = $_GET['nonce'];
            $verif = VerificationEmail::traiterEmailValidation($login, $nonce);
            if ($verif) {
                MessageFlash::ajouter("success", "Adresse mail vérifiée !");
                $loginURL = urlencode($login);
                self::redirect($url . "frontController.php?action=read&controller=utilisateur&login=$loginURL");
            } else {
                MessageFlash::ajouter("danger", "Erreur lors de la vérification de l'adresse mail !");
                self::redirect($url . "frontController.php?action=readAll&controller=question&etat=0");
            }
        }
    }

    public static function connecter(): void
    {

        $login = $_POST['login'];
        $utilisateurTemp = (new UtilisateurRepository)->select($login);
        $urlBase = Conf::getUrlBase();

        //verifier qu les info sont fournies
        if (!isset($login) || !isset($_POST['mdp'])) {

            MessageFlash::ajouter("danger", "login ou mot de passe manquant !");
            self::redirect($urlBase . "frontController.php?controller=utilisateur&action=connect");

        } else if (is_null($utilisateurTemp)) {

            MessageFlash::ajouter("warning", "login incorrect");
            self::redirect($urlBase . "frontController.php?controller=utilisateur&action=connect");

        } else if (!MotDePasse::verifier($_POST['mdp'], $utilisateurTemp->getMdpHache())) {

            MessageFlash::ajouter("warning", "Mot de passe incorrect");
            self::redirect($urlBase . "frontController.php?controller=utilisateur&action=connect");

        } else if (!VerificationEmail::aValideEmail($utilisateurTemp)) {
            MessageFlash::ajouter("warning", "Vous devez d'abord valider cette adresse mail !");
            self::connect();
        } else {
            ConnexionUtilisateur::connecter($login);
            $loginURL = urlencode($login);
            MessageFlash::ajouter("success", "Vous vous êtes connecté avec succès !");
            $url = Conf::getUrlBase() . "frontController.php?controller=utilisateur&action=read&login=$loginURL";
            self::redirect($url);
        }
    }

    public static function connect(): void
    {
        self::afficheVue('view.php', Conf::getUrlBase(), ["pagetitle" => "Formulaire de connexion", "cheminVueBody" => "account/connectAccount.php"]);
    }

    //page de connection

    public static function deconnecter()
    {
        ConnexionUtilisateur::deconnecter();
        MessageFlash::ajouter("success", "Vous avez été déconnecté !");
        $url = Conf::getUrlBase();
        self::redirect($url . "frontController.php?controller=question&action=readAll&etat=0");
    }

    //etre connecté

    public static function updated(): void
    {
        $login = $_POST['login'];
        $temp = (new UtilisateurRepository())->select($login);
        print_r($temp);
        $mdpHache = $temp->getMdpHache();
        $loginURL = urlencode($login);
        $url = Conf::getUrlBase();

        if (strcmp($_POST['mdp'], $_POST['mdp2']) != 0) {

            MessageFlash::ajouter("warning", "Mots de passe distincts !");
            self::redirect($url . "frontController.php?action=update&controller=utilisateur&login=$loginURL");

        } else if (!MotDePasse::verifier($_POST['ancienMdp'], $mdpHache)) {

            MessageFlash::ajouter("warning", "Ancien mot de passe erroné !");
            self::redirect($url . "frontController.php?action=update&controller=utilisateur&login=$loginURL");

        } else {

            $temp->setMdpHache($_POST['mdp']);
            $temp->setPrenomUtilisateur($_POST['prenom']);
            $temp->setNomUtilisateur($_POST['nom']);
            $temp->setAdresseMail($_POST['adresseMail']);
            (new UtilisateurRepository())->update($temp);
            //$utilisateurs = (new UtilisateurRepository())->selectAll();
            MessageFlash::ajouter("success", "L'utilisateur a bien été modifié !");
            self::redirect($url . "frontController.php?controller=question&action=readAll&etat=0");
        }
    }

    public static function update(): void
    {
        if (!isset($_POST['login'])) $login = $_GET["login"];
        else $login = $_POST['login'];
        $utilisateur = (new UtilisateurRepository())->select($login);
        self::afficheVue('view.php', Conf::getUrlBase(), ["utilisateur" => $utilisateur, "pagetitle" => "Formulaire changement profil ", "cheminVueBody" => "account/update.php"]);
    }

    public static function attribuerRole(): void
    {
        $login = $_GET['login'];
        if (isset($_GET['votant'])) {
            $votant = $_GET['votant'];
        } else {
            $votant = "";
        }
        if (isset($_GET['lien'])) $lien = $_GET['lien'];
        else $lien = null;
        $url = Conf::getUrlBase();
        $utilisateur = (new UtilisateurRepository())->select($login);
        if (ConnexionUtilisateur::estAdministrateur() || ConnexionUtilisateur::estOrganisateur()) {

            $questions = (new QuestionRepository())->selectAll(null);

            if (isset($_GET['idQuestion'])) { // Sert pour attribuer un responsable de proposition à une question
                $question = $_GET['idQuestion'];
                self::afficheVue('view.php', Conf::getUrlBase(), ["lien" => $lien, "utilisateur" => $utilisateur, "votant" => $votant, "idQuestion" => $question, "pagetitle" => "Formulaire changement profil ", "cheminVueBody" => "account/attributionRole.php"]);
            } else {
                self::afficheVue('view.php', Conf::getUrlBase(), ["lien" => $lien, "questions" => $questions, "utilisateur" => $utilisateur, "votant" => $votant, "pagetitle" => "Formulaire changement profil ", "cheminVueBody" => "account/attributionRole.php"]);
            }
        } else {
            MessageFlash::ajouter("danger", "Vous n'avez pas les permissions pour accèder à cette page !");
            self::redirect($url . "frontController.php?controller=question&action=readAll&etat=0");
        }
    }

    public static function attribuerUnCoAuteur(): void
    {
        $login = $_GET['login'];
        $idQuestion = $_GET['idQuestion'];
        $idProposititon = $_GET['idProposition'];
        $lien = $_GET['lien'];
        $url = Conf::getUrlBase();
        $utilisateur = (new UtilisateurRepository())->select($login);
        if (ConnexionUtilisateur::estAdministrateur() || ConnexionUtilisateur::estResponsablePsurP($_GET['idProposition'])) {
            self::afficheVue('view.php', Conf::getUrlBase(), ["lien" => $lien, "idProposition" => $idProposititon, "utilisateur" => $utilisateur, "idQuestion" => $idQuestion, "pagetitle" => "Formulaire changement profil ", "cheminVueBody" => "account/attributionRole.php"]);
        } else {
            MessageFlash::ajouter("danger", "Vous n'avez pas les permissions pour accèder à cette page !");
            self::redirect($url . "frontController.php?controller=question&action=readAll&etat=0");
        }
    }

    public static function atributed(): void
    {
        $url = Conf::getUrlBase();
        if (isset($_POST['role']) && isset($_POST['login'])) {
            $role = $_POST['role'];
            $login = $_POST['login'];
            if (isset($_POST['idQuestion']) && !strcmp($role, Role::Organisateur) == 0) {
                $question = $_POST['idQuestion'];
                if (!isset($_POST['idProposition'])) {
                    if (ConnexionUtilisateur::estAdministrateur() || ConnexionUtilisateur::estOrganisateur()) {

                        if (strcmp($role, Role::Auteur) == 0) {
                            //Si l'administrateur veut ajouter un coAuteur
                            $listeProposition = (new QuestionRepository())->getPropositionsTab($question);
                            if (sizeof($listeProposition) != 0){
                                $utilisateur = (new UtilisateurRepository())->select($login);
                                self::afficheVue('view.php', Conf::getUrlBase(), ["idQuestion" => $question,"utilisateur" => $utilisateur, "lien" => null, "listeProposition" => $listeProposition, "pagetitle" => "Formulaire changement profil ", "cheminVueBody" => "account/attributionRole.php"]);
                            }else{
                                $loginURL = rawurlencode($login);
                                MessageFlash::ajouter("warning", "La question choisit ne possède pas de proposition !");
                                self::redirect($url . "frontController.php?controller=utilisateur&action=attribuerRole&login=$loginURL");
                            }
                        } else {
                            $affectation = new Affectations($login, $question, $role);
                            (new AffectationsRepository())->save($affectation);
                            MessageFlash::ajouter("success", "Cet utilisateur à bien été affecté à la question !");
                            self::redirect($url . "frontController.php?controller=question&action=readAll&etat=0");
                        }
                    } else {
                        MessageFlash::ajouter("danger", "Vous n'avez pas l'autorisation de changer les droits d'un utilisateur !");
                        self::redirect($url . "frontController.php?controller=question&action=readAll&etat=0");
                    }
                } else {
                    if (ConnexionUtilisateur::estResponsablePsurP($_POST['idProposition']) || ConnexionUtilisateur::estAdministrateur()) { //faire le cas pour les co auteurs
                        $idProposition = $_POST['idProposition'];
                        $affectationProposition = new AffectationsPropositions($login, $question, $idProposition, $role);
                        (new AffectationsPropositionsRepository())->save($affectationProposition);
                        MessageFlash::ajouter("success", "Cet utilisateur à bien été affecté en tant que co auteur !");
                        self::redirect($url . "frontController.php?controller=question&action=readAll&etat=0");
                    } else {
                        MessageFlash::ajouter("danger", "Vous n'avez pas l'autorisation de changer les droits d'un utilisateur !");
                        self::redirect($url . "frontController.php?controller=question&action=readAll&etat=0");
                    }
                }
            } else {
                if (strcmp($role, Role::Organisateur) == 0) {
                    $utilisateur = (new UtilisateurRepository())->select($login);
                    $utilisateur->setEstOrganisateur(1);
                    (new UtilisateurRepository())->update($utilisateur);
                    MessageFlash::ajouter("success", "Cet utilisateur à bien été affecté en tant qu'organisateur");
                    self::redirect($url . "frontController.php?controller=question&action=readAll&etat=0");
                }
            }
        } else {
            MessageFlash::ajouter("warning", "Une erreur est survenu lors de l'accès à la page, peut être essayer vous d'accéder à une page sans utiliser les boutons de navigations usuels...");
            self::redirect($url . "frontController.php?controller=question&action=readAll&etat=0");
        }
    }

    //Atribution des roles admin : tout, organisateur : seulement sa question

    public static function readAll(int $etat): void
    {
        $url = Conf::getUrlBase();
        if (ConnexionUtilisateur::estAdministrateur()) {
            $utilisateurs = (new UtilisateurRepository())->selectAll(null);
            self::afficheVue('view.php', Conf::getUrlBase(), ["utilisateurs" => $utilisateurs, "pagetitle" => "Gestion des utilisateurs", "cheminVueBody" => "account/list.php"]);
        } else {
            MessageFlash::ajouter("danger", "Vous n'avez pas les permissions pour accéder à cette page !");
            self::redirect($url . "frontController.php?controller=question&action=readAll&etat=0");
        }
    }

    //donne le role coauteur : admin, responsable de proposition

    public static function demandeRole()
    {
        $url = Conf::getUrlBase();
        if (!isset($_GET['login'])) {
            MessageFlash::ajouter("warning", "Une erreur s'est produite avec votre login !");
            self::redirect($url . "frontController.php?controller=question&action=readAll&etat=0");
        } else {
            $login = $_GET['login'];
            if (strcmp($login, ConnexionUtilisateur::getLoginUtilisateurConnecte()) != 0) {
                MessageFlash::ajouter("danger", "Vous essayer d'utiliser le login de quelqu'un d'autre !");
                self::redirect($url . "frontController.php?controller=question&action=readAll&etat=0");
            } else {
                $questions = (new QuestionRepository())->selectAll(null);
                self::afficheVue('view.php', Conf::getUrlBase(), ["login" => $login, "questions" => $questions, "pagetitle" => "Formulaire de demande de droits", "cheminVueBody" => "account/demandeRole.php"]);
            }
        }
    }

    public static function demander()
    {
        $url = Conf::getUrlBase();
        if (!isset($_POST['login'])) {
            MessageFlash::ajouter("warning", "Un problème est survenu avec le login !");
            self::redirect($url . "frontController.php?controller=question&action=readAll&etat=0");
        } else {
            $login = $_POST['login'];
            $loginURL = rawurlencode($login);
            if (!isset($_POST['role']) || !isset($_POST['idQuestion'])) {
                MessageFlash::ajouter("warning", "Question ou role manquants !");
                self::redirect($url . "frontController.php?controller=utilisateur&action=demandeRole&login=$loginURL");
            } else {
                $demande = (new DemandeRoleRepository())->select($login);
                if (isset($demande)) {
                    MessageFlash::ajouter("info", "Vous ne pouvez faire qu'une seule demande en même temps");
                    self::redirect($url . "frontController.php?controller=question&action=readAll&etat=0");
                } else {
                    $idQuestion = $_POST['idQuestion'];
                    $role = $_POST['role'];

                    if (strcmp($role, Role::Auteur) == 0) {
                        //afficher une vue qui demande quel proposition de la question le mec est Auteur
                        $propositions = (new QuestionRepository())->getPropositionsTab($idQuestion);
                        if (sizeof($propositions) == 0) {
                            MessageFlash::ajouter("warning", "La question choisie ne contient pas encore de propositions");
                            self::redirect($url . "frontController.php?controller=utilisateur&action=demandeRole&login=$loginURL");
                        } else {
                            self::afficheVue('view.php', Conf::getUrlBase(), ["login" => $login, "propositions" => $propositions, "idQuestion" => $idQuestion, "pagetitle" => "Choix de la proposition", "cheminVueBody" => "account/choixProposition.php"]);
                        }
                    } else {
                        if ((new AffectationsRepository())->estDejaAffecter($idQuestion, $role, $login)) {
                            MessageFlash::ajouter("warning", "Vous êtes déjà affecter à cette question avec ce rôle !");
                            self::redirect($url . "frontController.php?controller=utilisateur&action=demandeRole&login=$loginURL");
                        } else {
                            $demandeVrai = new DemandeRole($login, $idQuestion, $role, null);
                            (new DemandeRoleRepository())->save($demandeVrai);
                            MessageFlash::ajouter("success", "La demande à bien été envoyée !");
                            self::redirect($url . "frontController.php?controller=question&action=readAll&etat=0");
                        }
                    }
                }
            }
        }
    }

    //gestion des demandes d'obtention de droits

    public static function demandeAuteur(): void
    {
        $url = Conf::getUrlBase();
        if (!isset($_POST['login'])) {
            MessageFlash::ajouter("warning", "Un problème est survenu avec le login !");
            self::redirect($url . "frontController.php?controller=question&action=readAll&etat=0");
        } else {
            $login = $_POST['login'];
            $loginURL = rawurlencode($login);
            if (!isset($_POST['idProposition']) || !isset($_POST['idQuestion'])) {
                MessageFlash::ajouter("warning", "Proposition, question ou role manquants !");
                self::redirect($url . "frontController.php?controller=utilisateur&action=demandeRole&login=$loginURL");
            } else {
                $demande = (new DemandeRoleRepository())->select($login);
                if (isset($demande)) {
                    MessageFlash::ajouter("info", "Vous ne pouvez faire qu'une seule demande en même temps");
                    self::redirect($url . "frontController.php?controller=question&action=readAll&etat=0");
                } else {
                    $idQuestion = $_POST['idQuestion'];
                    $role = Role::Auteur;
                    $idProposition = $_POST['idProposition'];

                    if ((new AffectationsPropositionsRepository())->estDejaAffecter($idQuestion, $role, $login, $idProposition)) {
                        MessageFlash::ajouter("warning", "Vous êtes déjà affecter à cette question avec ce rôle !");
                        self::redirect($url . "frontController.php?controller=utilisateur&action=demandeRole&login=$loginURL");
                    } else {
                        $demandeVrai = new DemandeRole($login, $idQuestion, $role, $idProposition);
                        (new DemandeRoleRepository())->save($demandeVrai);
                        MessageFlash::ajouter("success", "La demande à bien été envoyée !");
                        self::redirect($url . "frontController.php?controller=question&action=readAll&etat=0");
                    }
                }
            }
        }
    }

    public static function voirDemande(): void
    {
        $url = Conf::getUrlBase();
        if (!isset($_POST['login'])) {
            MessageFlash::ajouter("warning", "Un problème est survenu avec le login !");
            self::redirect($url . "frontController.php?controller=question&action=readAll&etat=0");
        } else {
            $login = $_POST['login'];
            $loginURL = rawurlencode($login);
            $demande = (new DemandeRoleRepository())->select($login);
            $question = (new QuestionRepository())->select($demande->getIdQuestion());
            $intituleHTML = htmlspecialchars($question->getIntituleQuestion());
            $loginHTML = htmlspecialchars($login);
            $roleHTML = htmlspecialchars($demande->getRole());
            MessageFlash::ajouter("info", "La demande de l'utilisateur $loginHTML concernant la question $intituleHTML pour le rôle $roleHTML est toujours en cours de traitement par l'administrateur");
            self::redirect($url . "frontController.php?controller=utilisateur&action=read&login=$loginURL");
        }
    }

    public static function voirDemandes(): void
    {
        $url = Conf::getUrlBase();
        if (!ConnexionUtilisateur::estAdministrateur()) {
            MessageFlash::ajouter("danger", "Vous n'avez pas les droits nécéssaires pour accèder à cette page !");
            self::redirect($url . "frontController.php?controller=question&action=readAll&etat=0");
        } else {
            $tabLoginParQuestion = array();
            $demandes = (new DemandeRoleRepository())->selectAll(null);
            foreach ($demandes as $demande) {
                $question = (new QuestionRepository())->select($demande->getIdQuestion());
                $tabLoginParQuestion[$demande->getLogin()] = [$question, $demande->getRole()];
            }
            self::afficheVue('view.php', Conf::getUrlBase(), ["tabLoginParQuestion" => $tabLoginParQuestion, "pagetitle" => "Liste des demandes", "cheminVueBody" => "account/pageDemandes.php"]);
        }
    }

    public static function readDemande(): void
    {
        $url = Conf::getUrlBase();
        if (!isset($_GET['login']) || !isset($_GET['role']) || !isset($_GET['intitule'])) {
            MessageFlash::ajouter("warning", "Login, role ou idQuestion manquant !");
            self::redirect($url . "frontController.php?controller=utilisateur&action=readAll&etat=0");
        } else if (!ConnexionUtilisateur::estAdministrateur()) {
            MessageFlash::ajouter("danger", "Vous n'avez pas les droits pour accèder à cette page !");
            self::redirect($url . "frontController.php?controller=question&action=readAll&etat=0");
        } else {
            $login = $_GET['login'];
            $intitule = $_GET['intitule'];
            $role = $_GET['role'];

            $demande = (new DemandeRoleRepository())->select($login);
            $idProposition = $demande->getIdProposition();

            self::afficheVue('view.php', $url, ["idProposition" => $idProposition, "loginU" => $login, "intitule" => $intitule, "role" => $role, "pagetitle" => "Detail de la demande", "cheminVueBody" => "account/readDemande.php"]);
        }
    }

    public static function choix(): void
    {
        $url = Conf::getUrlBase();
        if (!ConnexionUtilisateur::estAdministrateur()) {
            MessageFlash::ajouter("danger", "Vous n'avez pas les droits pour accèder à cette page !");
            self::redirect($url . "frontController.php?controller=question&action=readAll&etat=0");
        } else if (!isset($_POST['login']) || !isset($_POST['choix'])) {
            MessageFlash::ajouter("warning", "Erreur lors du choix ! (Login ou choix manquant)");
            self::redirect($url . "frontController.php?controller=utilisateur&action=readAll&etat=0");
        } else {
            $login = $_POST['login'];
            $choix = $_POST['choix'];
            $demande = (new DemandeRoleRepository())->select($login);
            if ($choix == 1) {
                $idProposition = $demande->getIdProposition();
                $idQuestion = $demande->getIdQuestion();
                $role = $demande->getRole();
                if (is_null($idProposition)) {
                    $affectation = (new AffectationsRepository())->construire(["login" => $login, "idQuestion" => $idQuestion, "role" => $role]);
                    (new AffectationsRepository())->save($affectation);
                } else {
                    $affectationProposition = (new AffectationsPropositionsRepository())->construire(["login" => $login, "idQuestion" => $idQuestion, "role" => $role, "idProposition" => $idProposition]);
                    (new AffectationsPropositionsRepository())->save($affectationProposition);
                }
            }
            (new DemandeRoleRepository())->delete($login);
            MessageFlash::ajouter("success", "Le choix à bien été prit en compte !");
            self::redirect($url . "frontController.php?controller=utilisateur&action=readAll&etat=0");
        }
    }

    public static function delete(): void
    {
        $message = (new UtilisateurRepository())->delete($_POST['login']);
        if (is_null($message)) self::error("Le suppression n'a pas fonctionnée");
        ControllerQuestion::readAll(1);
    }

    public static function readMyQuestions(): void
    {
        $url = Conf::getUrlBase();
        if (ConnexionUtilisateur::estConnecte()) {
            $rolesUtilisateur = ConnexionUtilisateur::getRolesUtilisateurConnecte();
            $myQuestions = array();
            $myRoles = array();
            /* On utilise deux tableaux séparés car on ne peut pas mettre un objet en clé en php */
            foreach ($rolesUtilisateur as $array) {
                foreach ($array as $idQuestion => $role) {
                    $myQuestions[] = (new QuestionRepository())->select($idQuestion);
                    $myRoles[] = $role;
                }
            }
            self::afficheVue('view.php', Conf::getUrlBase(), ["myRoles" => $myRoles, "myQuestions" => $myQuestions, "pagetitle" => "Liste de mes questions", "cheminVueBody" => "question/myQuestions.php"]);
        } else {
            MessageFlash::ajouter("danger", "Vous ne pouvez pas voir vos affectations tant que vous n'etes pas connecté !");
            self::redirect($url . "frontController.php?controller=utilisateur&action=readAll&etat=0");
        }
    }

    //affichage des question pour lesquels l'utilisateur a certains droits

    protected function getNomVueError(): string
    {
        // TODO: Implement getNomVueError() method.
        return "account";
    }


}