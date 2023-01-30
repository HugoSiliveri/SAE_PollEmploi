<?php

namespace App\AgoraScript\Model\Repository;

use App\AgoraScript\Model\DataObject\AbstractDataObject;
use App\AgoraScript\Model\DataObject\Vote;

class VoteRepository extends AbstractRepository
{

    public function construire(array $objetFormatTableau): AbstractDataObject
    {
        return new Vote(
            $objetFormatTableau['login'],
            $objetFormatTableau['idQuestion'],
        );
    }

    public function aVote(string $login, int $idQuestion): bool
    {
        $sql = "SELECT * FROM Vote WHERE login=:loginTag AND idQuestion=:idQuestionTag";
        $pdoStatement = DatabaseConnection::getPdo()->prepare($sql);

        $values = array(
            "loginTag" => $login,
            "idQuestionTag" => $idQuestion,
        );

        $pdoStatement->execute($values);

        return !empty($pdoStatement->fetch());
    }

    protected function getNomTable(): string
    {
        return "Vote";
    }

    protected function getNomClePrimaire(): string
    {
        return "login";
    }

    protected function getNomsColonnes(): array
    {
        return array(
            "idQuestion" => "idQuestion",
        );
    }
}