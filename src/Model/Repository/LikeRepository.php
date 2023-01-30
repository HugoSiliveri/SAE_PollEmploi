<?php

namespace App\AgoraScript\Model\Repository;

use App\AgoraScript\Model\DataObject\AbstractDataObject;
use App\AgoraScript\Model\DataObject\Like;

class LikeRepository extends AbstractRepository
{

    public function aLike(int $idSectionProposition, string $login): bool
    {
        $sql = "SELECT * FROM `Like` WHERE login=:loginTag AND idSectionProposition=:idSectionPropositionTag";
        $pdoStatement = DatabaseConnection::getPdo()->prepare($sql);

        $values = array(
            "loginTag" => $login,
            "idSectionPropositionTag" => $idSectionProposition,
        );

        $pdoStatement->execute($values);

        return !empty($pdoStatement->fetch());
    }

    public function nbLike(int $idSectionProposition): int
    {
        $sql = "SELECT COUNT(*) FROM `Like` WHERE idSectionProposition=:idSectionPropositionTag";
        $pdoStatement = DatabaseConnection::getPdo()->prepare($sql);

        $values = array(
            "idSectionPropositionTag" => $idSectionProposition,
        );

        $pdoStatement->execute($values);


        return intval($pdoStatement->fetchColumn());
    }

    public function getLike(int $idSectionProposition, string $login): AbstractDataObject
    {
        return (new LikeRepository())->construire([
            "idSectionProposition" => $idSectionProposition,
            "login" => $login
        ]);
    }

    public function construire(array $objetFormatTableau): AbstractDataObject
    {
        return new Like(
            $objetFormatTableau['idSectionProposition'],
            $objetFormatTableau['login']
        );
    }

    public function deleteLike($like): void
    {
        $idSectionProposition = $like->getIdSectionProposition();
        $login = $like->getLogin();

        $sql = "DELETE FROM `Like` WHERE idSectionProposition=:idSectionPropositionTag AND login=:loginTag";
        $pdoStatement = DatabaseConnection::getPdo()->prepare($sql);

        $values = array(
            "idSectionPropositionTag" => $idSectionProposition,
            "loginTag" => $login
        );

        $pdoStatement->execute($values);
    }

    protected function getNomTable(): string
    {
        return "`Like`";
    }

    protected function getNomClePrimaire(): string
    {
        return "idSectionProposition";
    }

    protected function getNomsColonnes(): array
    {
        return array(
            "login" => "login"
        );
    }

}