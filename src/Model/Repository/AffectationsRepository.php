<?php

namespace App\AgoraScript\Model\Repository;

use App\AgoraScript\Model\DataObject\AbstractDataObject;
use App\AgoraScript\Model\DataObject\Affectations;

class AffectationsRepository extends AbstractRepository
{

    public function existeResponsable(int $idQuestion): bool
    {
        $sql = "SELECT * FROM Affectations WHERE role='ResponsableP' AND idQuestion=:idQuestionTag";
        $pdoStatement = DatabaseConnection::getPdo()->prepare($sql);

        $values = array(
            "idQuestionTag" => $idQuestion,
        );


        $pdoStatement->execute($values);
        $utilisateur = $pdoStatement->fetch();

        return (bool)$utilisateur;
    }

    public function getAffectations($login): array
    {
        $tab = [];

        $sql = "SELECT * FROM Affectations WHERE login=:loginTag";

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
        return new Affectations(
            $objetFormatTableau['login'],
            $objetFormatTableau['idQuestion'],
            $objetFormatTableau['role']
        );
    }

    public function getAuteur(int $idQuestion): string
    {
        $sql = "SELECT login FROM Affectations WHERE idQuestion=:idQuestionTag AND role='Organisateur'";

        $values = array(
            "idQuestionTag" => $idQuestion
        );

        $pdoStatement = DatabaseConnection::getPdo()->prepare($sql);
        $pdoStatement->execute($values);
        return strval($pdoStatement->fetch()[0]);
    }

    public function estDejaAffecter(int $idQuestion, string $role, string $login)
    {
        $sql = "SELECT login FROM Affectations WHERE idQuestion=:idQuestionTag AND role=:roleTag AND login=:loginTag";

        $values = array(
            "idQuestionTag" => $idQuestion,
            "roleTag" => $role,
            "loginTag" => $login
        );

        $pdoStatement = DatabaseConnection::getPdo()->prepare($sql);

        $pdoStatement->execute($values);

        $res = $pdoStatement->fetch();

        return (bool)$res;
    }

    protected function getNomTable(): string
    {
        return "Affectations";
    }

    protected function getNomClePrimaire(): string
    {
        return "login";
    }

    protected function getNomsColonnes(): array
    {
        return array(
            "idQuestion" => "idQuestion",
            "role" => "role"
        );
    }

}