<?php

namespace App\AgoraScript\Model\Repository;

use App\AgoraScript\Model\DataObject\AbstractDataObject;
use App\AgoraScript\Model\DataObject\Reponse;

class ReponseRepository extends AbstractRepository
{

    public function getReponsesPourCommentaire($idCommentaire): array
    {

        $reponses = array();

        $sql = "SELECT * FROM Reponse WHERE idCommentaire=:idCommentaireTag";

        $pdoStatement = DatabaseConnection::getPdo()->prepare($sql);

        $values = array(
            "idCommentaireTag" => $idCommentaire,
        );

        $pdoStatement->execute($values);

        foreach ($pdoStatement as $reponsesFormatTableau) {
            $reponses[] = (new ReponseRepository())->construire($reponsesFormatTableau);
        }

        return $reponses;
    }

    public function construire(array $objetFormatTableau): AbstractDataObject
    {
        return new Reponse(
            $objetFormatTableau['idReponse'],
            $objetFormatTableau['idCommentaire'],
            $objetFormatTableau['login'],
            $objetFormatTableau['message'],
            $objetFormatTableau['datePoste']
        );
    }

    public function getNombreCommentaires($idCommentaire): int
    {
        $sql = "SELECT COUNT(idReponse) FROM Reponse WHERE idProposition=:idCommentaireTag";

        $pdoStatement = DatabaseConnection::getPdo()->prepare($sql);

        $values = array(
            "idCommentaireTag" => $idCommentaire
        );


        $pdoStatement->execute($values);

        $nbReponses = $pdoStatement->fetch()[0];

        return $nbReponses;
    }

    protected function getNomTable(): string
    {
        return "Reponse";
    }

    protected function getNomClePrimaire(): string
    {
        return "idReponse";
    }

    protected function getNomsColonnes(): array
    {
        return [
            "idCommentaire" => "idCommentaire",
            "login" => "login",
            "message" => "message",
            "datePoste" => "datePoste"
        ];
    }
}