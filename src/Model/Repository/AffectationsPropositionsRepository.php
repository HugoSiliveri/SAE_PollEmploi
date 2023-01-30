<?php

namespace App\AgoraScript\Model\Repository;

use App\AgoraScript\Model\DataObject\AbstractDataObject;
use App\AgoraScript\Model\DataObject\AffectationsPropositions;

class AffectationsPropositionsRepository extends AbstractRepository
{

    public function getAffectationsPropositions($login): array
    {
        $tab = [];

        $sql = "SELECT * FROM AffectationsPropositions WHERE login=:loginTag";

        $values = array(
            "loginTag" => $login,
        );

        $pdoStatement = DatabaseConnection::getPdo()->prepare($sql);
        $pdoStatement->execute($values);

        foreach ($pdoStatement as $objectFormatTab) {
            $tab[] = $this->construire($objectFormatTab);
        }
        return $tab;
    }

    public function construire(array $objetFormatTableau): AbstractDataObject
    {
        return new AffectationsPropositions(
            $objetFormatTableau['login'],
            $objetFormatTableau['idQuestion'],
            $objetFormatTableau['idProposition'],
            $objetFormatTableau['role']
        );
    }

    public function getAuteurs(int $idProposition): array
    {
        $tab = [];
        $sql = "SELECT login FROM AffectationsPropositions WHERE idProposition=:idPropositionTag";

        $values = array(
            "idPropositionTag" => $idProposition
        );

        $pdoStatement = DatabaseConnection::getPdo()->prepare($sql);
        $pdoStatement->execute($values);

        foreach ($pdoStatement as $objects) {
            $tab[] = $objects[0];
        }
        return $tab;
    }

    public function estDejaAffecter(int $idQuestion, string $role, string $login, int $idProposition): bool
    {
        $sql = "SELECT login FROM AffectationsPropositions WHERE idQuestion=:idQuestionTag AND role=:roleTag AND login=:loginTag AND idProposition=:idPropositionTag";

        $values = array(
            "idQuestionTag" => $idQuestion,
            "roleTag" => $role,
            "loginTag" => $login,
            "idPropositionTag" => $idProposition
        );

        $pdoStatement = DatabaseConnection::getPdo()->prepare($sql);

        $pdoStatement->execute($values);

        $res = $pdoStatement->fetch();

        return (bool)$res;
    }

    protected function getNomTable(): string
    {
        return "AffectationsPropositions";
    }

    protected function getNomClePrimaire(): string
    {
        return "login";
    }

    protected function getNomsColonnes(): array
    {
        return array(
            "idQuestion" => "idQuestion",
            "idProposition" => "idProposition",
            "role" => "role"
        );
    }


}