<?php

namespace App\AgoraScript\Controller;

use App\AgoraScript\Config\Conf;
use App\AgoraScript\Lib\ConnexionUtilisateur;
use App\AgoraScript\Lib\MessageFlash;
use App\AgoraScript\Lib\Role;
use App\AgoraScript\Lib\TypeVote;
use App\AgoraScript\Model\DataObject\Affectations;
use App\AgoraScript\Model\Repository\AffectationsPropositionsRepository;
use App\AgoraScript\Model\Repository\AffectationsRepository;
use App\AgoraScript\Model\Repository\NombreVoteRepository;
use App\AgoraScript\Model\Repository\PropositionRepository;
use App\AgoraScript\Model\Repository\QuestionRepository;
use App\AgoraScript\Model\Repository\SectionQuestionRepository;
use App\AgoraScript\Model\Repository\VoteRepository;

class ControllerQuestion extends Controller
{
    public static function read(): void
    {
        $idQuestion = $_GET["id"];
        $propositions = (new QuestionRepository)->getPropositionsTab($idQuestion);
        $question = (new QuestionRepository())->select($idQuestion);
        $sections = (new QuestionRepository())->getSectionsQuestion($idQuestion);
        $typeVote = $question->getTypeVote();

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

        //gestion affichage des scores pour les propositions
        $progressionVote = array();
        if (strcmp($typeVote, TypeVote::VOTE_PAR_VALEUR) == 0) {
            foreach ($propositions as $proposition) {
                //Pour chaque proposition on associe l'objet avec son tableau de nombre de vote
                $nombreVote = (new NombreVoteRepository())->select($proposition->getIdProposition());
                $progressionVote[$proposition->getIdProposition()] = $nombreVote->getNombreVoteFormatTableau();
            }
        } else {
            foreach ($propositions as $proposition) {
                $progressionVote[$proposition->getIdProposition()] = $proposition->getNbPoints();
            }
        }

        self::afficheVue('view.php', Conf::getUrlBase(), ["aVote" => $aVote, "aDejaEcritUneProp" => $aDejaEcritUneProp, "typeVote" => $typeVote, "progressionVote" => $progressionVote, "question" => $question, "sections" => $sections, "propositions" => $propositions, "pagetitle" => "Details de la question", "cheminVueBody" => "question/detailQuestion.php"]);
    }

    public static function create(): void
    {
        self::afficheVue('view.php', Conf::getUrlBase(), ["pagetitle" => "Formulaire création d'une question", "cheminVueBody" => "question/createQuestion.php"]);
    }

    public static function created(): void
    {
        $url = Conf::getUrlBase();
        if (!ConnexionUtilisateur::estAdministrateur() && !ConnexionUtilisateur::estOrganisateur()) {
            MessageFlash::ajouter("danger", "Vous n'avez pas la permission de déposer une question !");
            self::redirect(Conf::getUrlBase() . "frontController.php?controller=question&action=readAll&etat=0");
        } else if (!isset($_POST["descriptionMd"]) ||
            !isset($_POST["debutProposition"]) ||
            !isset($_POST["finProposition"]) ||
            !isset($_POST["debutCommentaire"]) ||
            !isset($_POST["finCommentaire"]) ||
            !isset($_POST["debutProposition2"]) ||
            !isset($_POST["finProposition2"]) ||
            !isset($_POST["debutVote"]) ||
            !isset($_POST["finVote"]) ||
            !isset($_POST["intitule"]) ||
            !isset($_POST["typeVote"])) {
            MessageFlash::ajouter("warning", "Erreur lors de l'accès à la page désirée, un ou plusieurs des éléments sont manquants...");
            self::redirect(Conf::getUrlBase() . "frontController.php?controller=question&action=create");
        } else {
            $debutProposition = $_POST["debutProposition"];
            $finProposition = $_POST["finProposition"];
            $debutCommentaire = $_POST["debutCommentaire"];
            $finCommentaire = $_POST["finCommentaire"];
            $debutProposition2 = $_POST["debutProposition2"];
            $finProposition2 = $_POST["finProposition2"];
            $debutVote = $_POST["debutVote"];
            $finVote = $_POST["finVote"];

            if ($debutProposition > $finProposition
                || $debutVote < $finProposition2
                || $finVote < $debutVote
                || $debutProposition < date("Y-m-d")
                || $debutCommentaire > $finCommentaire
                || $debutProposition2 > $finProposition2
                || $debutCommentaire > $debutProposition2
                || $debutCommentaire < $finProposition) {

                MessageFlash::ajouter("warning", "Échéances pour les dates non valides !");
                self::redirect(Conf::getUrlBase() . "frontController.php?controller=question&action=create");

            } else {
                $questionsForm = (new QuestionRepository())->construire(["idQuestion" => NULL,
                    "intituleQuestion" => $_POST["intitule"],
                    "descriptionQuestion" => $_POST["descriptionMd"],
                    "dateDebutProposition" => $debutProposition,
                    "dateFinProposition" => $finProposition,
                    "dateDebutCommentaire" => $debutCommentaire,
                    "dateFinCommentaire" => $finCommentaire,
                    "dateDebutProposition2" => $debutProposition2,
                    "dateFinProposition2" => $finProposition2,
                    "dateDebutVote" => $debutVote,
                    "dateFinVote" => $finVote,
                    "etatQuestion" => 0,
                    "typeVote" => $_POST['typeVote']]); //Construction
                (new QuestionRepository())->save($questionsForm);


                $nbSections = (count($_POST) - 13) / 2;

                $idQuestion = (new QuestionRepository())->getIdQuestion($_POST["intitule"], $_POST["descriptionMd"], $debutProposition, $finProposition, $debutCommentaire, $finCommentaire, $debutProposition2, $finProposition2, $debutVote, $finVote, 0, $_POST['typeVote']);

                for ($i = 1; $i <= $nbSections; $i++) {
                    $sectionsQuestionForm = (new SectionQuestionRepository())->construire(["idSectionQuestion" => NULL, "titreSectionQuestion" => $_POST["titreSection" . $i], "descriptionSectionQuestion" => $_POST["descriptionSection" . $i . "Md"], "idQuestion" => $idQuestion]);
                    (new SectionQuestionRepository())->save($sectionsQuestionForm);
                }

                $affectation = new Affectations(ConnexionUtilisateur::getLoginUtilisateurConnecte(), $idQuestion, 'Organisateur');

                (new AffectationsRepository())->save($affectation);


                MessageFlash::ajouter("success", "Question créée !");
                self::redirect(Conf::getUrlBase() . "frontController.php?controller=question&action=readAll&etat=0");
            }
        }
    }

    public static function updated(): void
    {
        $idQuestion = $_POST["id"];
        if (!isset($_POST["descriptionMd"]) ||
            !isset($_POST["debutProposition"]) ||
            !isset($_POST["finProposition"]) ||
            !isset($_POST["debutCommentaire"]) ||
            !isset($_POST["finCommentaire"]) ||
            !isset($_POST["debutProposition2"]) ||
            !isset($_POST["finProposition2"]) ||
            !isset($_POST["debutVote"]) ||
            !isset($_POST["finVote"]) ||
            !isset($_POST["intitule"]) ||
            !isset($_POST["typeVote"])) {
            MessageFlash::ajouter("warning", "Erreur lors de l'accès à la page désirée, un ou plusieurs des éléments sont manquants...");
            self::redirect(Conf::getUrlBase() . "frontController.php?controller=question&action=update&id=" . $idQuestion);
        } else {
            $debutProposition = $_POST["debutProposition"];
            $finProposition = $_POST["finProposition"];
            $debutCommentaire = $_POST["debutCommentaire"];
            $finCommentaire = $_POST["finCommentaire"];
            $debutProposition2 = $_POST["debutProposition2"];
            $finProposition2 = $_POST["finProposition2"];
            $debutVote = $_POST["debutVote"];
            $finVote = $_POST["finVote"];

            if ($debutProposition > $finProposition
                || $debutVote < $finProposition2
                || $finVote < $debutVote
                || $debutProposition < date("Y-m-d")
                || $debutCommentaire > $finCommentaire
                || $debutProposition2 > $finProposition2
                || $debutCommentaire > $debutProposition2
                || $debutCommentaire < $finProposition) {

                MessageFlash::ajouter("warning", "Échéances pour les dates non valides !");
                self::redirect(Conf::getUrlBase() . "frontController.php?controller=question&action=create");

            } else {
                $questionForm = (new QuestionRepository())->construire(["idQuestion" => $idQuestion,
                    "intituleQuestion" => $_POST["intitule"],
                    "descriptionQuestion" => $_POST["descriptionMd"],
                    "dateDebutProposition" => $debutProposition,
                    "dateFinProposition" => $finProposition,
                    "dateDebutCommentaire" => $debutCommentaire,
                    "dateFinCommentaire" => $finCommentaire,
                    "dateDebutProposition2" => $debutProposition2,
                    "dateFinProposition2" => $finProposition2,
                    "dateDebutVote" => $debutVote,
                    "dateFinVote" => $finVote,
                    "etatQuestion" => $_POST["etatQuestion"],
                    "typeVote" => $_POST['typeVote']]); //Construction
                (new QuestionRepository())->update($questionForm);

                $nbSectionsDepart = $_POST["nbSectionsDepart"];
                $nbSectionsApres = (sizeof($_POST) - 16) / 2;

                $sections = (new QuestionRepository())->getSectionsQuestion($_POST['id']);
                if ($nbSectionsDepart > $nbSectionsApres) { // On a enlevé des sections
                    for ($i = 1; $i <= $nbSectionsDepart; $i++) {
                        if ($i <= $nbSectionsApres) {
                            $sectionsQuestionForm = (new SectionQuestionRepository())->construire(["idSectionQuestion" => strval($sections[$i - 1]->getIdSectionQuestion()), "titreSectionQuestion" => $_POST["titreSection" . $i], "descriptionSectionQuestion" => $_POST["descriptionSection" . $i . "MD"], "idQuestion" => $idQuestion]);
                            (new SectionQuestionRepository())->update($sectionsQuestionForm);
                        } else {
                            (new SectionQuestionRepository())->delete(strval($sections[$i - 1]->getIdSectionQuestion()));
                        }
                    }
                } else { // On n'a pas rajouté des sections
                    for ($i = 1; $i <= $nbSectionsApres; $i++) {
                        if ($i <= $nbSectionsDepart) {
                            $sectionsQuestionForm = (new SectionQuestionRepository())->construire(["idSectionQuestion" => strval($sections[$i - 1]->getIdSectionQuestion()), "titreSectionQuestion" => $_POST["titreSection" . $i], "descriptionSectionQuestion" => $_POST["descriptionSection" . $i . "MD"], "idQuestion" => $idQuestion]);
                            (new SectionQuestionRepository())->update($sectionsQuestionForm);
                        } else {
                            $sectionsQuestionForm = (new SectionQuestionRepository())->construire(["idSectionQuestion" => NULL, "titreSectionQuestion" => $_POST["titreSection" . $i], "descriptionSectionQuestion" => $_POST["descriptionSection" . $i . "Md"], "idQuestion" => $idQuestion]);
                            (new SectionQuestionRepository())->save($sectionsQuestionForm);
                        }

                    }
                }
                MessageFlash::ajouter("success", "Question mise à jour !");
                self::redirect(Conf::getUrlBase() . "frontController.php?controller=question&action=readAll&etat=0");
            }
        }
    }

    public static function update(): void
    {
        $idQuestion = $_GET['id'];
        if (is_null($idQuestion)) (new ControllerQuestion)->error("Échec de la mise à jour");
        $question = (new QuestionRepository())->select($idQuestion);
        $tabSections = (new QuestionRepository())->getSectionsQuestion($idQuestion);
        self::afficheVue('view.php', Conf::getUrlBase(), ["question" => $question, "tabSections" => $tabSections, "pagetitle" => "Mise à jour de la question", "cheminVueBody" => "question/update.php"]);
    }

    //tests et ajout d'une question dans la base de donnée

    public static function delete(): void
    {

        $idQuestion = $_GET['id'];
        $url = Conf::getUrlBase();

        if (is_null($idQuestion)) {
            MessageFlash::ajouter("warning", "Échec lors de la suppression");
            self::redirect($url . "frontController.php?controller=question&action=readAll&etat=0");
        }

        $question = (new QuestionRepository())->select($idQuestion);

        $etat = $question->getEtatQuestion();

        (new QuestionRepository())->delete($idQuestion);

        MessageFlash::ajouter("success", "Question supprimée !");
        self::redirect(Conf::getUrlBase() . "frontController.php?controller=question&action=readAll&etat=0");
    }

    public static function resultat(): void
    {
        $url = Conf::getUrlBase();
        if (isset($_GET['id'])) {
            $idQuestion = $_GET['id'];
            $question = (new QuestionRepository())->select($idQuestion);
            $typeVote = $question->getTypeVote();
            if ($question->getEtatQuestion() == 8) {
                if (strcmp($typeVote, TypeVote::VOTE_PAR_VALEUR) == 0) {
                    $propositions = (new QuestionRepository())->getPropositionsTab($idQuestion);
                    $propositionsGagnantes = array();
                    foreach ($propositions as $proposition) {
                        if ((new PropositionRepository())->getGagnante($proposition->getIdProposition()) == 1) {
                            $propositionsGagnantes[] = $proposition;
                        }
                    }
                    self::afficheVue('view.php', Conf::getUrlBase(), ["propositions" => $propositionsGagnantes, "question" => $question, "pagetitle" => "Résultat des votes", "cheminVueBody" => "question/resultat.php"]);
                } else {
                    $propositions = (new QuestionRepository())->getPropositionsTab($idQuestion);
                    $propositionsParPoints = array();
                    foreach ($propositions as $proposition) {
                        $propositionsParPoints[$proposition->getNbPoints()] = $proposition;
                    }
                    arsort($propositionsParPoints);
                    self::afficheVue('view.php', Conf::getUrlBase(), ["propositions" => $propositionsParPoints, "question" => $question, "pagetitle" => "Résultat des votes", "cheminVueBody" => "question/resultatPoints.php"]);
                }

            } else {
                MessageFlash::ajouter("warning", "Les votes ne sont pas encore terminés !");
                self::redirect($url . "frontController.php?controller=question&action=read&id=$idQuestion");
            }
        } else {
            MessageFlash::ajouter("warning", "Un problème est survenu lors de la consultation de cette page !");
            self::readAll(0);
        }
    }

    //affichage des resultats des vote seulement si la question a fini sa periode definie

    public static function readAll(int $etat): void
    {

        $questionsTemp = self::getListeQuestion($etat);

        foreach ($questionsTemp as $question) {
            $question->changerEtat($etat);
        }
        $questions = self::getListeQuestion($etat);
        self::afficheVue('view.php', Conf::getUrlBase(), ["questions" => $questions, "pagetitle" => "Liste des questions publiées", "cheminVueBody" => "question/list.php"]);
    }

    /**
     * @param int $etat
     * @return array
     */
    private static function getListeQuestion(int $etat): array
    {
        if ($etat == 0) { // cas ou l'etat vaut 0,1 ou 2
            $questionsTemp0 = (new QuestionRepository())->selectAll($etat);
            $questionsTemp1 = (new QuestionRepository())->selectAll(1);
            $questionsTemp2 = (new QuestionRepository())->selectAll(2);
            $questionsTemp = array_merge($questionsTemp0, $questionsTemp1, $questionsTemp2);
        } else if ($etat == 3) {
            $questionsTemp3 = (new QuestionRepository())->selectAll(3);
            $questionsTemp4 = (new QuestionRepository())->selectAll(4);
            $questionsTemp = array_merge($questionsTemp3, $questionsTemp4);
        } else if ($etat == 5) {
            $questionsTemp5 = (new QuestionRepository())->selectAll(5);
            $questionsTemp6 = (new QuestionRepository())->selectAll(6);
            $questionsTemp = array_merge($questionsTemp5, $questionsTemp6);
        } else {
            $questionsTemp = (new QuestionRepository())->selectAll($etat);
        }
        return $questionsTemp;
    }

    public function getNomVueError(): string
    {
        // TODO: Implement getNomVueError() method.
        return "question";
    }

}