<?php

namespace App\AgoraScript\Model\Repository;

use App\AgoraScript\Model\DataObject\AbstractDataObject;
use App\AgoraScript\Model\DataObject\SectionProposition;

class SectionPropositionRepository extends AbstractRepository
{

    public function construire(array $objetFormatTableau): AbstractDataObject
    {
        return new SectionProposition(
            $objetFormatTableau['idSectionProposition'],
            $objetFormatTableau['titreSectionProposition'],
            $objetFormatTableau['idProposition'],
            $objetFormatTableau['idSectionQuestion'],
            $objetFormatTableau['texteSectionProposition']
        );
    }

    protected function getNomTable(): string
    {
        return "SectionProposition";
    }

    protected function getNomClePrimaire(): string
    {
        return "idSectionProposition";
    }

    protected function getNomsColonnes(): array
    {
        return [
            "titreSectionProposition" => "titreSectionProposition",
            "texteSectionProposition" => "texteSectionProposition",
            "idSectionQuestion" => "idSectionQuestion",
            "idProposition" => "idProposition"
        ];
    }
}