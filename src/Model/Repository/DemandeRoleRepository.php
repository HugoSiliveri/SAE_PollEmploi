<?php

namespace App\AgoraScript\Model\Repository;

use App\AgoraScript\Model\DataObject\AbstractDataObject;
use App\AgoraScript\Model\DataObject\DemandeRole;

class DemandeRoleRepository extends AbstractRepository
{

    public function construire(array $objetFormatTableau): AbstractDataObject
    {
        return new DemandeRole(
            $objetFormatTableau['login'],
            $objetFormatTableau['idQuestion'],
            $objetFormatTableau['role'],
            $objetFormatTableau['idProposition']
        );
    }

    protected function getNomTable(): string
    {
        return "DemandeRole";
    }

    protected function getNomClePrimaire(): string
    {
        return "login";
    }

    protected function getNomsColonnes(): array
    {
        return array(
            "idQuestion" => "idQuestion",
            "role" => "role",
            "idProposition" => "idProposition"
        );
    }


}