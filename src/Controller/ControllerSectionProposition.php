<?php

namespace App\AgoraScript\Controller;

use App\AgoraScript\Config\Conf;
use App\AgoraScript\Lib\ConnexionUtilisateur;
use App\AgoraScript\Lib\MessageFlash;
use App\AgoraScript\Model\Repository\LikeRepository;
use App\AgoraScript\Model\Repository\PropositionRepository;
use App\AgoraScript\Model\Repository\QuestionRepository;
use App\AgoraScript\Model\Repository\SectionPropositionRepository;
use App\AgoraScript\Model\Repository\SectionQuestionRepository;
use App\AgoraScript\Model\Repository\VersionSectionPropositionRepository;

class ControllerSectionProposition extends Controller
{
    public static function readAll(int $etat): void
    {
        // TODO: Implement readAll() method.
    }

    public static function create(): void
    {
        $idQuestion = $_POST['idQuestion'];
        $tabSections = (new QuestionRepository())->getSectionsQuestion($idQuestion);
        self::afficheVue('view.php', Conf::getUrlBase(), ["idQuestion" => $idQuestion, "tabSections" => $tabSections, "idProposition" => $_POST['idProposition'], "pagetitle" => "Formulaire création d'une question", "cheminVueBody" => "sectionProposition/createSection.php"]);
    }

    public static function created(): void
    {
        $idProposition = intval($_POST['proposition']);
        $idQuestion = intval($_POST['idQuestion']);

        $titreSectionQuestion = (new SectionQuestionRepository())->getIntituleSectionQuestion(intval($_POST['section']));
        $sectionPropositionForm = (new SectionPropositionRepository())->construire(['idSectionProposition' => NULL, 'titreSectionProposition' => $titreSectionQuestion, 'idProposition' => $idProposition, 'idSectionQuestion' => intval($_POST['section']), 'texteSectionProposition' => $_POST['texte']]);

        (new SectionPropositionRepository())->save($sectionPropositionForm);

        $proposition = (new PropositionRepository())->select($idProposition);

        $sections = (new PropositionRepository())->getSectionsProposition($idProposition);

        MessageFlash::ajouter("success", "Section de la proposition créée !");
        self::redirect(Conf::getUrlBase() . "frontController.php?controller=question&action=readAll&etat=0");
        self::afficheVue('view.php', Conf::getUrlBase(), ["idQuestion" => $idQuestion, "proposition" => $proposition, "sections" => $sections, "pagetitle" => "Details de la proposition", "cheminVueBody" => "proposition/detailProposition.php"]);
    }

    public static function restore(): void
    {
        $url = Conf::getUrlBase();
        if (!isset($_POST['idQuestion']) || !isset($_POST['idSectionProposition'])) {
            MessageFlash::ajouter("warning", "Une erreur est survenue avec la question ou la section proposition");
            self::redirect($url . "frontController.php?controller=question&action=readAll&etat=0");
        } else {
            $idQuestion = $_POST['idQuestion'];
            $idSectionProposition = $_POST['idSectionProposition'];
            if (!ConnexionUtilisateur::estAdministrateur() && !ConnexionUtilisateur::estResponsableP($idQuestion)) {
                MessageFlash::ajouter("danger", "Vous n'avez pas la permission de restaurer des anciennes versions !");
                self::redirect($url . "frontController.php?controller=question&action=readAll&etat=0");
            } else {
                $versions = (new VersionSectionPropositionRepository())->selectAllVersions($idSectionProposition);
                self::afficheVue('view.php', $url, ["versions" => $versions, "idQuestion" => $idQuestion, "pagetitle" => "Choix de la version à restaurer", "cheminVueBody" => "sectionProposition/choixRestore.php"]);
            }
        }
    }

    //bouton pour revenir sur une version precedente du texte

    public static function restored(): void
    {
        $url = Conf::getUrlBase();
        if (!isset($_POST['idQuestion']) || !isset($_POST['idSectionProposition']) || !isset($_POST['version'])) {
            MessageFlash::ajouter("warning", "Une erreur est survenue avec la question ou la section proposition ou la version");
            self::redirect($url . "frontController.php?controller=question&action=readAll&etat=0");
        } else {
            $idQuestion = $_POST['idQuestion'];
            $idSectionProposition = $_POST['idSectionProposition'];
            $version = $_POST['version'];
            if (!ConnexionUtilisateur::estAdministrateur() && !ConnexionUtilisateur::estResponsableP($idQuestion)) {
                MessageFlash::ajouter("danger", "Vous n'avez pas la permission de restaurer des anciennes versions !");
                self::redirect($url . "frontController.php?controller=question&action=readAll&etat=0");
            } else {
                $section = (new SectionPropositionRepository())->select($idSectionProposition);
                $versionObjet = (new VersionSectionPropositionRepository())->selectParVersion($idSectionProposition, $version);

                //On supprime la version que l'on vient de restaurer car elle est devenue la nouvelle
                (new VersionSectionPropositionRepository())->deleteParVersion($idSectionProposition, $version);

                //Ensuite on ajoute la version qui va être changée par l'ancienne dans les anciennes versions
                $versionAEnregistrer = (new VersionSectionPropositionRepository())->construire([
                    "version" => (new VersionSectionPropositionRepository())->derniereVersion($idSectionProposition),
                    "idSectionProposition" => $idSectionProposition,
                    "titreSectionProposition" => $section->getTitreSectionProposition(),
                    "texteSectionProposition" => $section->getTexteSectionProposition()
                ]);
                (new VersionSectionPropositionRepository())->save($versionAEnregistrer);

                //Ensuite on modifie la section actuelle par celle restaurée
                $section->setTexteSectionProposition($versionObjet->getTexteSectionProposition());
                (new SectionPropositionRepository())->update($section);

                $idProposition = $section->getIdProposition();

                MessageFlash::ajouter("success", "La restauration a bien été effectuée !");
                self::redirect($url . "frontController.php?controller=proposition&action=read&id=$idProposition&idQuestion=$idQuestion");
            }
        }
    }

    //restoration

    public static function like(): void
    {
        $url = Conf::getUrlBase();
        if (isset($_GET["id"]) && isset($_GET['idQuestion']) && isset($_GET['idSection'])) {
            $idSection = $_GET['idSection'];
            $idProposition = $_GET['id'];
            $idQuestion = $_GET['idQuestion'];
            $login = ConnexionUtilisateur::getLoginUtilisateurConnecte();
            if (!(new LikeRepository())->aLike($idSection, $login)) {
                $like = (new LikeRepository())->construire([
                    "idSectionProposition" => $idSection,
                    "login" => $login
                ]);
                (new LikeRepository())->save($like);
                self::redirect($url . "frontController.php?controller=proposition&action=read&id=$idProposition&idQuestion=$idQuestion");
            } else {
                $like = (new LikeRepository())->getLike($idSection, $login);
                (new LikeRepository())->deleteLike($like);
                self::redirect($url . "frontController.php?controller=proposition&action=read&id=$idProposition&idQuestion=$idQuestion");
            }

        } else {
            MessageFlash::ajouter("warning", "une erreur est survenue lors du like de la section !");
            self::redirect($url . "frontController.php?controller=question&action=readAll");
        }
    }

    //bouton pouce bleu en dessous des sections

    protected function getNomVueError(): string
    {
        return "sectionProposition";
    }


}