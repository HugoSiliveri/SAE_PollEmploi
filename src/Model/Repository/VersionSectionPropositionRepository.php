<?php

namespace App\AgoraScript\Model\Repository;

use App\AgoraScript\Model\DataObject\VersionSectionProposition;

class VersionSectionPropositionRepository extends AbstractRepository
{

    public function selectAllVersions(string $idSectionProposition): array
    {

        $sql = "SELECT * FROM VersionSectionProposition WHERE idSectionProposition=:idSectionPropositionTag";
        $pdoStatement = DatabaseConnection::getPdo()->prepare($sql);

        $res = array();

        $values = array(
            "idSectionPropositionTag" => $idSectionProposition
        );

        $pdoStatement->execute($values);


        foreach ($pdoStatement as $version) {
            $res[] = new VersionSectionProposition(
                $version['version'],
                $version['idSectionProposition'],
                $version['titreSectionProposition'],
                $version['texteSectionProposition']
            );
        }

        return $res;
    }

    public function existeVersion(string $idSectionProposition): bool
    {

        $sql = "SELECT * FROM VersionSectionProposition WHERE idSectionProposition=:idSectionPropositionTag";
        $pdoStatement = DatabaseConnection::getPdo()->prepare($sql);

        $values = array(
            "idSectionPropositionTag" => $idSectionProposition
        );

        $pdoStatement->execute($values);

        return sizeof($pdoStatement->fetchAll()) != 0;
    }

    public function derniereVersion(string $idSectionProposition): int
    {

        $sql = "SELECT * FROM VersionSectionProposition WHERE idSectionProposition=:idSectionPropositionTag";
        $pdoStatement = DatabaseConnection::getPdo()->prepare($sql);

        $version = 1;
        $versionPlusHaute = 0;

        $values = array(
            "idSectionPropositionTag" => $idSectionProposition
        );

        $pdoStatement->execute($values);

        foreach ($pdoStatement as $versionSection) {
            if ($versionSection['version'] > $versionPlusHaute) $versionPlusHaute = $versionSection['version'];
        }
        if ($version < $versionPlusHaute + 1) $version = $versionPlusHaute + 1;

        return $version;
    }

    public function deleteParVersion(string $idSectionProposition, int $version): ?string
    {

        $sql = "DELETE FROM VersionSectionProposition WHERE idSectionProposition=:idSectionPropositionTag AND version=:versionTag";

        if (is_null(self::selectParVersion($idSectionProposition, $version))) return null;

        $pdoStatement = DatabaseConnection::getPdo()->prepare($sql);

        $values = array(
            "idSectionPropositionTag" => $idSectionProposition,
            "versionTag" => $version
        );

        $pdoStatement->execute($values);

        return "VersionSectionProposition de clé primaire : $idSectionProposition à bien été supprimée !";
    }

    public function selectParVersion(string $idSectionProposition, int $numeroVersion): ?VersionSectionProposition
    {
        $sql = "SELECT * from VersionSectionProposition WHERE idSectionProposition=:idSectionPropositionTag AND version=:versionTag";

        $pdoStatement = DatabaseConnection::getPdo()->prepare($sql);

        $values = array(
            "idSectionPropositionTag" => $idSectionProposition,
            "versionTag" => $numeroVersion
        );

        $pdoStatement->execute($values);


        $objet = $pdoStatement->fetch();

        if (!$objet) return null;

        return $this->construire($objet);
    }

    public function construire(array $objetFormatTableau): VersionSectionProposition
    {
        return new VersionSectionProposition(
            $objetFormatTableau['version'],
            $objetFormatTableau['idSectionProposition'],
            $objetFormatTableau['titreSectionProposition'],
            $objetFormatTableau['texteSectionProposition']
        );
    }

    protected function getNomTable(): string
    {
        return "VersionSectionProposition";
    }

    protected function getNomClePrimaire(): string
    {
        return "idSectionProposition";
    }

    protected function getNomsColonnes(): array
    {
        return array(
            "version" => "version",
            "titreSectionProposition" => "titreSectionProposition",
            "texteSectionProposition" => "texteSectionProposition"
        );
    }
}