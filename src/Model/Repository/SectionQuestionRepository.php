<?php

namespace App\AgoraScript\Model\Repository;

use App\AgoraScript\Model\DataObject\AbstractDataObject;
use App\AgoraScript\Model\DataObject\SectionQuestion;

class SectionQuestionRepository extends AbstractRepository
{

    public function getNomTable(): string
    {
        return "SectionQuestion";
    }

    public function construire(array $sectionQuestionTableau): AbstractDataObject
    {
        return new SectionQuestion(
            $sectionQuestionTableau["idSectionQuestion"],
            $sectionQuestionTableau["titreSectionQuestion"],
            $sectionQuestionTableau["descriptionSectionQuestion"],
            $sectionQuestionTableau["idQuestion"]
        );
    }

    public function getNomClePrimaire(): string
    {
        // TODO: Implement getNomClePrimaire() method.
        return "idSectionQuestion";
    }

    public function getNomsColonnes(): array
    {
        // TODO: Implement getNomsColonnes() method.
        return array(
            "titreSectionQuestion" => "titreSectionQuestion",
            "descriptionSectionQuestion" => "descriptionSectionQuestion",
            "idQuestion" => "idQuestion"
        );
    }

    public function getIntituleSectionQuestion(int $id): string
    {
        $sql = "SELECT titreSectionQuestion FROM SectionQuestion WHERE idSectionQuestion =:idSectionQuestionTag";
        $pdoStatement = DatabaseConnection::getPdo()->prepare($sql);

        $values = array(
            "idSectionQuestionTag" => $id,
        );

        $pdoStatement->execute($values);
        $intitule = $pdoStatement->fetch()[0];
        return $intitule;
    }

}