<?php

namespace App\AgoraScript\Model\Repository;

use App\AgoraScript\Model\DataObject\AbstractDataObject;
use App\AgoraScript\Model\DataObject\NombreVote;

class NombreVoteRepository extends AbstractRepository
{

    public function getNomTable(): string
    {
        // TODO: Implement getNomTable() method.
        return "NombreVote";
    }

    public function construire(array $objetFormatTableau): AbstractDataObject
    {
        // TODO: Implement construire() method.
        return new NombreVote(
            $objetFormatTableau["idProposition"],
            $objetFormatTableau["nbTB"],
            $objetFormatTableau["nbBB"],
            $objetFormatTableau["nbAB"],
            $objetFormatTableau["nbPP"],
            $objetFormatTableau["nbII"],
            $objetFormatTableau["nbAR"]
        );
    }

    public function getNomClePrimaire(): string
    {
        // TODO: Implement getNomClePrimaire() method.
        return "idProposition";
    }

    public function getNomsColonnes(): array
    {
        // TODO: Implement getNomsColonnes() method.
        return [
            "nbTB" => "nbTB",
            "nbBB" => "nbBB",
            "nbAB" => "nbAB",
            "nbPP" => "nbPP",
            "nbII" => "nbII",
            "nbAR" => "nbAR"
        ];
    }
}