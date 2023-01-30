<?php

namespace App\AgoraScript\Model\DataObject;

use App\AgoraScript\Lib\TypeVote;
use App\AgoraScript\Lib\Utile;
use App\AgoraScript\Model\Repository\PropositionRepository;
use App\AgoraScript\Model\Repository\QuestionRepository;

class Question extends AbstractDataObject
{
    private ?string $idQuestion;
    private string $intituleQuestion;
    private string $descriptionQuestion;
    private string $dateDebutProposition;
    private string $dateFinProposition;
    private string $dateDebutCommentaire;
    private string $dateFinCommentaire;
    private string $dateDebutProposition2;
    private string $dateFinProposition2;
    private string $dateDebutVote;
    private string $dateFinVote;
    private int $etatQuestion; // 0 = Création, 1 = Phase des Proposition, 2 = Phase entre Propostion et vote, 3 = Phase des Votes, 4 = Phase des résultats
    private string $typeVote;

    public function __construct(?string $idQuestion, string $intituleQuestion, string $descriptionQuestion, string $dateDebutProposition, string $dateFinProposition, string $dateDebutCommentaire, string $dateFinCommentaire, string $dateDebutProposition2, string $dateFinProposition2, string $dateDebutVote, string $dateFinVote, int $etatQuestion, ?string $typeVote)
    {
        if (is_null($typeVote)) {
            $this->typeVote = "";
        } else {
            $this->typeVote = $typeVote;
        }
        $this->idQuestion = $idQuestion;
        $this->intituleQuestion = $intituleQuestion;
        $this->descriptionQuestion = $descriptionQuestion;
        $this->dateDebutProposition = $dateDebutProposition;
        $this->dateFinProposition = $dateFinProposition;
        $this->dateDebutVote = $dateDebutVote;
        $this->dateFinVote = $dateFinVote;
        $this->etatQuestion = $etatQuestion;
        $this->dateDebutCommentaire = $dateDebutCommentaire;
        $this->dateFinCommentaire = $dateFinCommentaire;
        $this->dateDebutProposition2 = $dateDebutProposition2;
        $this->dateFinProposition2 = $dateFinProposition2;
    }


    public function formatTableau(): array
    {
        return array(
            "clesPrimaireTag" => $this->idQuestion,
            "intituleQuestionTag" => $this->intituleQuestion,
            "descriptionQuestionTag" => $this->descriptionQuestion,
            "dateDebutPropositionTag" => $this->dateDebutProposition,
            "dateFinPropositionTag" => $this->dateFinProposition,
            "dateDebutCommentaireTag" => $this->dateDebutCommentaire,
            "dateFinCommentaireTag" => $this->dateFinCommentaire,
            "dateDebutProposition2Tag" => $this->dateDebutProposition2,
            "dateFinProposition2Tag" => $this->dateFinProposition2,
            "dateDebutVoteTag" => $this->dateDebutVote,
            "dateFinVoteTag" => $this->dateFinVote,
            "etatQuestionTag" => $this->etatQuestion,
            "typeVoteTag" => $this->typeVote
        );
    }

    public function changerEtat(): void
    {
        $dateActuelle = date("Y-m-d");
        switch ($this->getEtatQuestion()) {
            case 0:
                if ($this->getDateDebutProposition() <= $dateActuelle) {
                    $question = (new QuestionRepository())->construire(["idQuestion" => $this->getIdQuestion(), "intituleQuestion" => $this->getIntituleQuestion(), "descriptionQuestion" => $this->getDescriptionQuestion(), "dateDebutProposition" => $this->getDateFinProposition(), "dateFinProposition" => $this->getDateFinProposition(), "dateDebutCommentaire" => $this->getDateDebutCommentaire(), "dateFinCommentaire" => $this->getDateFinCommentaire(), "dateDebutProposition2" => $this->getDateDebutProposition2(), "dateFinProposition2" => $this->getDateFinProposition2(), "dateDebutVote" => $this->getDateDebutVote(), "dateFinVote" => $this->getDateFinVote(), "etatQuestion" => $this->getEtatQuestion() + 1, "typeVote" => $this->getTypeVote()]);
                    (new QuestionRepository())->update($question);
                }
                break;
            case 1:
                if ($this->getDateFinProposition() <= $dateActuelle) {
                    $question = (new QuestionRepository())->construire(["idQuestion" => $this->getIdQuestion(), "intituleQuestion" => $this->getIntituleQuestion(), "descriptionQuestion" => $this->getDescriptionQuestion(), "dateDebutProposition" => $this->getDateFinProposition(), "dateFinProposition" => $this->getDateFinProposition(), "dateDebutCommentaire" => $this->getDateDebutCommentaire(), "dateFinCommentaire" => $this->getDateFinCommentaire(), "dateDebutProposition2" => $this->getDateDebutProposition2(), "dateFinProposition2" => $this->getDateFinProposition2(), "dateDebutVote" => $this->getDateDebutVote(), "dateFinVote" => $this->getDateFinVote(), "etatQuestion" => $this->getEtatQuestion() + 1, "typeVote" => $this->getTypeVote()]);
                    (new QuestionRepository())->update($question);
                    $nbSectionQuestion = count(array_keys((new QuestionRepository())->getSectionsQuestion($this->idQuestion)));
                    $propositions = (new QuestionRepository())->getPropositionsTab($this->idQuestion);
                    foreach ($propositions as $proposition){
                        $nbSectionProposition = count(array_keys((new PropositionRepository())->getSectionsProposition($proposition->getIdProposition())));
                        if($nbSectionProposition != $nbSectionQuestion){
                            (new PropositionRepository())->delete($proposition->getIdProposition());
                        }
                    }
                }
                break;
            case 2:
                if ($this->getDateDebutCommentaire() <= $dateActuelle) {
                    $question = (new QuestionRepository())->construire(["idQuestion" => $this->getIdQuestion(), "intituleQuestion" => $this->getIntituleQuestion(), "descriptionQuestion" => $this->getDescriptionQuestion(), "dateDebutProposition" => $this->getDateFinProposition(), "dateFinProposition" => $this->getDateFinProposition(), "dateDebutCommentaire" => $this->getDateDebutCommentaire(), "dateFinCommentaire" => $this->getDateFinCommentaire(), "dateDebutProposition2" => $this->getDateDebutProposition2(), "dateFinProposition2" => $this->getDateFinProposition2(), "dateDebutVote" => $this->getDateDebutVote(), "dateFinVote" => $this->getDateFinVote(), "etatQuestion" => $this->getEtatQuestion() + 1, "typeVote" => $this->getTypeVote()]);
                    (new QuestionRepository())->update($question);
                }
                break;
            case 3:
                if ($this->getDateFinCommentaire() <= $dateActuelle) {
                    $question = (new QuestionRepository())->construire(["idQuestion" => $this->getIdQuestion(), "intituleQuestion" => $this->getIntituleQuestion(), "descriptionQuestion" => $this->getDescriptionQuestion(), "dateDebutProposition" => $this->getDateFinProposition(), "dateFinProposition" => $this->getDateFinProposition(), "dateDebutCommentaire" => $this->getDateDebutCommentaire(), "dateFinCommentaire" => $this->getDateFinCommentaire(), "dateDebutProposition2" => $this->getDateDebutProposition2(), "dateFinProposition2" => $this->getDateFinProposition2(), "dateDebutVote" => $this->getDateDebutVote(), "dateFinVote" => $this->getDateFinVote(), "etatQuestion" => $this->getEtatQuestion() + 1, "typeVote" => $this->getTypeVote()]);
                    (new QuestionRepository())->update($question);
                }
                break;
            case 4:
                if ($this->getDateDebutProposition2() <= $dateActuelle) {
                    $question = (new QuestionRepository())->construire(["idQuestion" => $this->getIdQuestion(), "intituleQuestion" => $this->getIntituleQuestion(), "descriptionQuestion" => $this->getDescriptionQuestion(), "dateDebutProposition" => $this->getDateFinProposition(), "dateFinProposition" => $this->getDateFinProposition(), "dateDebutCommentaire" => $this->getDateDebutCommentaire(), "dateFinCommentaire" => $this->getDateFinCommentaire(), "dateDebutProposition2" => $this->getDateDebutProposition2(), "dateFinProposition2" => $this->getDateFinProposition2(), "dateDebutVote" => $this->getDateDebutVote(), "dateFinVote" => $this->getDateFinVote(), "etatQuestion" => $this->getEtatQuestion() + 1, "typeVote" => $this->getTypeVote()]);
                    (new QuestionRepository())->update($question);
                }
                break;
            case 5:
                if ($this->getDateFinProposition2() <= $dateActuelle) {
                    $question = (new QuestionRepository())->construire(["idQuestion" => $this->getIdQuestion(), "intituleQuestion" => $this->getIntituleQuestion(), "descriptionQuestion" => $this->getDescriptionQuestion(), "dateDebutProposition" => $this->getDateFinProposition(), "dateFinProposition" => $this->getDateFinProposition(), "dateDebutCommentaire" => $this->getDateDebutCommentaire(), "dateFinCommentaire" => $this->getDateFinCommentaire(), "dateDebutProposition2" => $this->getDateDebutProposition2(), "dateFinProposition2" => $this->getDateFinProposition2(), "dateDebutVote" => $this->getDateDebutVote(), "dateFinVote" => $this->getDateFinVote(), "etatQuestion" => $this->getEtatQuestion() + 1, "typeVote" => $this->getTypeVote()]);
                    (new QuestionRepository())->update($question);
                }
                break;
            case 6:
                if ($this->getDateDebutVote() <= $dateActuelle) {
                    $question = (new QuestionRepository())->construire(["idQuestion" => $this->getIdQuestion(), "intituleQuestion" => $this->getIntituleQuestion(), "descriptionQuestion" => $this->getDescriptionQuestion(), "dateDebutProposition" => $this->getDateFinProposition(), "dateFinProposition" => $this->getDateFinProposition(), "dateDebutCommentaire" => $this->getDateDebutCommentaire(), "dateFinCommentaire" => $this->getDateFinCommentaire(), "dateDebutProposition2" => $this->getDateDebutProposition2(), "dateFinProposition2" => $this->getDateFinProposition2(), "dateDebutVote" => $this->getDateDebutVote(), "dateFinVote" => $this->getDateFinVote(), "etatQuestion" => $this->getEtatQuestion() + 1, "typeVote" => $this->getTypeVote()]);
                    (new QuestionRepository())->update($question);
                }
                break;
            case 7:
                if ($this->getDateFinVote() <= $dateActuelle) {
                    if (strcmp(TypeVote::VOTE_PAR_VALEUR, $this->getTypeVote()) == 0) {
                        Utile::resultat($this->getIdQuestion());
                    }
                    $question = (new QuestionRepository())->construire(["idQuestion" => $this->getIdQuestion(), "intituleQuestion" => $this->getIntituleQuestion(), "descriptionQuestion" => $this->getDescriptionQuestion(), "dateDebutProposition" => $this->getDateFinProposition(), "dateFinProposition" => $this->getDateFinProposition(), "dateDebutCommentaire" => $this->getDateDebutCommentaire(), "dateFinCommentaire" => $this->getDateFinCommentaire(), "dateDebutProposition2" => $this->getDateDebutProposition2(), "dateFinProposition2" => $this->getDateFinProposition2(), "dateDebutVote" => $this->getDateDebutVote(), "dateFinVote" => $this->getDateFinVote(), "etatQuestion" => $this->getEtatQuestion() + 1, "typeVote" => $this->getTypeVote()]);
                    (new QuestionRepository())->update($question);
                }
                break;
            default:
                break;
        }
    }

    /**
     * @return int
     */
    public function getEtatQuestion(): int
    {
        return $this->etatQuestion;
    }

    /**
     * @param int $etatQuestion
     */
    public function setEtatQuestion(int $etatQuestion): void
    {
        $this->etatQuestion = $etatQuestion;
    }

    /**
     * @return string
     */
    public function getDateDebutProposition(): string
    {
        return $this->dateDebutProposition;
    }

    /**
     * @return int
     */
    public function getIdQuestion(): int
    {
        return $this->idQuestion;
    }

    /**
     * @return string
     */
    public function getIntituleQuestion(): string
    {
        return $this->intituleQuestion;
    }

    /**
     * @return string
     */
    public function getDescriptionQuestion(): string
    {
        return $this->descriptionQuestion;
    }

    /**
     * @return string
     */
    public function getDateFinProposition(): string
    {
        return $this->dateFinProposition;
    }

    /**
     * @return string
     */
    public function getDateDebutCommentaire(): string
    {
        return $this->dateDebutCommentaire;
    }

    /**
     * @param string $dateDebutCommentaire
     */
    public function setDateDebutCommentaire(string $dateDebutCommentaire): void
    {
        $this->dateDebutCommentaire = $dateDebutCommentaire;
    }

    /**
     * @return string
     */
    public function getDateFinCommentaire(): string
    {
        return $this->dateFinCommentaire;
    }

    /**
     * @param string $dateFinCommentaire
     */
    public function setDateFinCommentaire(string $dateFinCommentaire): void
    {
        $this->dateFinCommentaire = $dateFinCommentaire;
    }

    /**
     * @return string
     */
    public function getDateDebutProposition2(): string
    {
        return $this->dateDebutProposition2;
    }

    /**
     * @param string $dateDebutProposition2
     */
    public function setDateDebutProposition2(string $dateDebutProposition2): void
    {
        $this->dateDebutProposition2 = $dateDebutProposition2;
    }

    /**
     * @return string
     */
    public function getDateFinProposition2(): string
    {
        return $this->dateFinProposition2;
    }

    /**
     * @param string $dateFinProposition2
     */
    public function setDateFinProposition2(string $dateFinProposition2): void
    {
        $this->dateFinProposition2 = $dateFinProposition2;
    }

    /**
     * @return string
     */
    public function getDateDebutVote(): string
    {
        return $this->dateDebutVote;
    }

    /**
     * @return string
     */
    public function getDateFinVote(): string
    {
        return $this->dateFinVote;
    }

    /**
     * @return string
     */
    public function getTypeVote(): string
    {
        return $this->typeVote;
    }

}

?>