<?php

namespace App\AgoraScript\Model\Repository;

use App\AgoraScript\Model\DataObject\AbstractDataObject;
use App\AgoraScript\Model\DataObject\Question;

class QuestionRepository extends AbstractRepository
{
    public function getNomTable(): string
    {
        return "Question";
    }

    public function getNomClePrimaire(): string
    {
        return "idQuestion";
    }

    public function getNomsColonnes(): array
    {
        return array(
            "intituleQuestion" => "intituleQuestion",
            "descriptionQuestion" => "descriptionQuestion",
            "dateDebutProposition" => "dateDebutProposition",
            "dateFinProposition" => "dateFinProposition",
            "dateDebutCommentaire" => "dateDebutCommentaire",
            "dateFinCommentaire" => "dateFinCommentaire",
            "dateDebutProposition2" => "dateDebutProposition2",
            "dateFinProposition2" => "dateFinProposition2",
            "dateDebutVote" => "dateDebutVote",
            "dateFinVote" => "dateFinVote",
            "etatQuestion" => "etatQuestion",
            "typeVote" => "typeVote"
        );
    }

    public function getSectionsQuestion(string $id): array
    {
        $tabSections = [];
        $sql = "SELECT * FROM SectionQuestion WHERE idQuestion=:idQuestionTag";
        $pdoStatement = DatabaseConnection::getPdo()->prepare($sql);

        $values = array(
            "idQuestionTag" => $id,
        );

        $pdoStatement->execute($values);

        foreach ($pdoStatement as $sectionsFormatTableau) {
            $tabSections[] = (new SectionQuestionRepository())->construire($sectionsFormatTableau);
        }

        return $tabSections;
    }

    public function construire(array $questionTableau): AbstractDataObject
    {
        return new Question(
            $questionTableau["idQuestion"],
            $questionTableau["intituleQuestion"],
            $questionTableau["descriptionQuestion"],
            $questionTableau["dateDebutProposition"],
            $questionTableau["dateFinProposition"],
            $questionTableau["dateDebutCommentaire"],
            $questionTableau["dateFinCommentaire"],
            $questionTableau["dateDebutProposition2"],
            $questionTableau["dateFinProposition2"],
            $questionTableau["dateDebutVote"],
            $questionTableau["dateFinVote"],
            $questionTableau["etatQuestion"],
            $questionTableau["typeVote"]
        );
    }

    public function getIdQuestion(string $intituleQuestion, string $descriptionQuestion, string $dateDebutProposition, string $dateFinProposition, string $dateDebutCommentaire, string $dateFinCommentaire, string $dateDebutProposition2, string $dateFinProposition2, string $dateDebutVote, string $dateFinVote, int $etatQuestion, string $typeVote): int
    {
        $sql = "SELECT idQuestion FROM Question WHERE 
                                  intituleQuestion =:intituleQuestionTag 
                                  AND descriptionQuestion =:descriptionQuestionTag 
                                  AND dateDebutProposition =:dateDebutPropositionTag 
                                  AND dateFinProposition =:dateFinPropositionTag 
                                  AND dateDebutVote =:dateDebutVoteTag 
                                  AND dateFinVote =:dateFinVoteTag 
                                  AND etatQuestion =:etatQuestionTag 
                                  AND typeVote =:typeVoteTag 
                                  AND dateDebutCommentaire=:dateDebutCommentaireTag
                                  AND dateFinCommentaire=:dateFinCommentaireTag
                                  AND dateDebutProposition2=:dateDebutProposition2Tag
                                  AND dateFinProposition2=:dateFinProposition2Tag";

        $pdoStatement = DatabaseConnection::getPdo()->prepare($sql);

        $values = array(
            "intituleQuestionTag" => $intituleQuestion,
            "descriptionQuestionTag" => $descriptionQuestion,
            "dateDebutPropositionTag" => $dateDebutProposition,
            "dateFinPropositionTag" => $dateFinProposition,
            "dateDebutCommentaireTag" => $dateDebutCommentaire,
            "dateFinCommentaireTag" => $dateFinCommentaire,
            "dateDebutProposition2Tag" => $dateDebutProposition2,
            "dateFinProposition2Tag" => $dateFinProposition2,
            "dateDebutVoteTag" => $dateDebutVote,
            "dateFinVoteTag" => $dateFinVote,
            "etatQuestionTag" => $etatQuestion,
            "typeVoteTag" => $typeVote
        );

        $pdoStatement->execute($values);
        $valeur = intval($pdoStatement->fetch()[0]);
        return $valeur;
    }

    public function getIdQuestionTemp(string $intituleQuestion): int
    {
        $sql = "SELECT idQuestion FROM Question WHERE intituleQuestion =:intituleQuestionTag";
        $pdoStatement = DatabaseConnection::getPdo()->prepare($sql);

        $values = array(
            "intituleQuestionTag" => $intituleQuestion
        );

        $pdoStatement->execute($values);
        $valeur = intval($pdoStatement->fetch()[0]);
        return $valeur;
    }

    public function getPropositionsTab($id): array
    {
        $tabPropositions = [];
        $sql = "SELECT * FROM Proposition WHERE idQuestion=:idQuestionTag";
        $pdoStatement = DatabaseConnection::getPdo()->prepare($sql);

        $values = array(
            "idQuestionTag" => $id,
        );

        $pdoStatement->execute($values);

        foreach ($pdoStatement as $PropositionFormatTableau) {
            $tabPropositions[] = (new PropositionRepository())->construire($PropositionFormatTableau);
        }
        return $tabPropositions;
    }
}

?>