<?php

namespace App\AgoraScript\Model\Repository;

use App\AgoraScript\Model\DataObject\AbstractDataObject;
use App\AgoraScript\Model\DataObject\Proposition;

class PropositionRepository extends AbstractRepository
{

    public function getSectionsProposition(int $id): array
    {
        $tabSections = [];
        $sql = "SELECT * FROM SectionProposition WHERE idProposition=:idPropositionTag";
        $pdoStatement = DatabaseConnection::getPdo()->prepare($sql);

        $values = array(
            "idPropositionTag" => $id,
        );

        $pdoStatement->execute($values);

        foreach ($pdoStatement as $sectionsFormatTableau) {
            $tabSections[] = (new SectionPropositionRepository())->construire($sectionsFormatTableau);
        }

        return $tabSections;
    }

    public function construire(array $objetFormatTableau): AbstractDataObject
    {
        return new Proposition(
            $objetFormatTableau['idProposition'],
            $objetFormatTableau['idQuestion'],
            $objetFormatTableau['intituleProposition'],
        );
    }

    public function getIdProposition(string $intitule, int $idQuestion): int
    {
        $sql = "SELECT idProposition FROM Proposition WHERE intituleProposition=:intituleTag AND idQuestion=:idQuestionTag";
        $pdoStatement = DatabaseConnection::getPdo()->prepare($sql);

        $values = array(
            "intituleTag" => $intitule,
            "idQuestionTag" => $idQuestion
        );

        $pdoStatement->execute($values);
        $id = intval($pdoStatement->fetch()[0]);

        return $id;
    }

    public function attribuerPoints(int $id, int $nbPoint): void
    {
        $sql = "UPDATE Proposition SET nbPoints=nbPoints+:nbPointsTag WHERE idProposition=:idPropositionTag";
        $pdoStatement = DatabaseConnection::getPdo()->prepare($sql);

        $values = array(
            "nbPointsTag" => $nbPoint,
            "idPropositionTag" => $id,
        );

        $pdoStatement->execute($values);
    }

    public function voterParValeur(int $id, string $typeMention): void
    {

        $sql = "UPDATE NombreVote SET $typeMention=$typeMention+1 WHERE idProposition=$id";

        $pdoStatement = DatabaseConnection::getPdo();
        $pdoStatement->query($sql);
    }

    public function getNbPoints(int $id): int
    {
        $sql = "SELECT nbPoints FROM Proposition WHERE idProposition=:idPropositionTag";
        $pdoStatement = DatabaseConnection::getPdo()->prepare($sql);

        $values = array(
            "idPropositionTag" => $id,
        );

        $pdoStatement->execute($values);
        return intval($pdoStatement->fetch()[0]);
    }

    public function getGagnante(int $id): int
    {
        $sql = "SELECT gagnante FROM Proposition WHERE idProposition=:idPropositionTag";
        $pdoStatement = DatabaseConnection::getPdo()->prepare($sql);

        $values = array(
            "idPropositionTag" => $id,
        );

        $pdoStatement->execute($values);
        return intval($pdoStatement->fetch()[0]);
    }

    protected function getNomTable(): string
    {
        return "Proposition";
    }

    protected function getNomClePrimaire(): string
    {
        return "idProposition";
    }

    protected function getNomsColonnes(): array
    {
        return [
            "idQuestion" => "idQuestion",
            "intituleProposition" => "intituleProposition",
            "nbPoints" => "nbPoints",
            "gagnante" => "gagnante"
        ];
    }

}