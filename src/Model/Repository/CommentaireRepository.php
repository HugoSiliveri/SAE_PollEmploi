<?php

namespace App\AgoraScript\Model\Repository;

use App\AgoraScript\Model\DataObject\AbstractDataObject;
use App\AgoraScript\Model\DataObject\Commentaire;

class CommentaireRepository extends AbstractRepository
{

    public function getNombreCommentaires($idProposition): int
    {
        $sql = "SELECT COUNT(idCommentaire) FROM Commentaire WHERE idProposition=:idPropositionTag";

        $pdoStatement = DatabaseConnection::getPdo()->prepare($sql);

        $values = array(
            "idPropositionTag" => $idProposition
        );


        $pdoStatement->execute($values);

        $nbCommentaires = $pdoStatement->fetch()[0];

        return $nbCommentaires;
    }

    public function selectAllCommentairesPourUneProposition($idProposition): array
    {
        $commentaires = array();

        $sql = "SELECT * FROM Commentaire WHERE idProposition=:idPropositionTag";

        $pdoStatement = DatabaseConnection::getPdo()->prepare($sql);

        $values = array(
            "idPropositionTag" => $idProposition,
        );

        $pdoStatement->execute($values);

        foreach ($pdoStatement as $commentairesFormatTableau) {
            $commentaires[] = (new CommentaireRepository())->construire($commentairesFormatTableau);
        }

        return $commentaires;
    }

    public function construire(array $objetFormatTableau): AbstractDataObject
    {
        return new Commentaire(
            $objetFormatTableau['idCommentaire'],
            $objetFormatTableau['login'],
            $objetFormatTableau['idProposition'],
            $objetFormatTableau['message'],
            $objetFormatTableau['datePoste'],
            $objetFormatTableau['modifier']
        );
    }

    public function estLieAuCommentaire($login, $idCommentaire): bool
    {
        $sql = "SELECT * FROM Commentaire WHERE login=:loginTag AND idCommentaire=:idCommentaireTag";

        $pdoStatement = DatabaseConnection::getPdo()->prepare($sql);

        $values = array(
            "loginTag" => $login,
            "idCommentaireTag" => $idCommentaire
        );

        $pdoStatement->execute($values);

        return isset($pdoStatement->fetch()[0]);
    }

    protected function getNomTable(): string
    {
        return "Commentaire";
    }

    protected function getNomClePrimaire(): string
    {
        return "idCommentaire";
    }

    protected function getNomsColonnes(): array
    {
        return [
            "login" => "login",
            "idProposition" => "idProposition",
            "message" => "message",
            "datePoste" => "datePoste",
            "modifier" => "modifier"
        ];
    }
}