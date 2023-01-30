<?php

namespace App\AgoraScript\Model\DataObject;

class VersionSectionProposition extends AbstractDataObject
{

    private int $version;
    private int $idSectionProposition;
    private string $titreSectionProposition;
    private string $texteSectionProposition;

    /**
     * @param int $version
     * @param int $idSectionProposition
     * @param string $titreSectionProposition
     * @param string $texteSectionProposition
     */
    public function __construct(int $version, int $idSectionProposition, string $titreSectionProposition, string $texteSectionProposition)
    {
        $this->version = $version;
        $this->idSectionProposition = $idSectionProposition;
        $this->titreSectionProposition = $titreSectionProposition;
        $this->texteSectionProposition = $texteSectionProposition;
    }

    public function formatTableau(): array
    {
        return array(
            "clesPrimaireTag" => $this->idSectionProposition,
            "versionTag" => $this->version,
            "titreSectionPropositionTag" => $this->titreSectionProposition,
            "texteSectionPropositionTag" => $this->texteSectionProposition
        );
    }

    /**
     * @return int
     */
    public function getVersion(): int
    {
        return $this->version;
    }

    /**
     * @param int $version
     */
    public function setVersion(int $version): void
    {
        $this->version = $version;
    }

    /**
     * @return int
     */
    public function getIdSectionProposition(): int
    {
        return $this->idSectionProposition;
    }

    /**
     * @param int $idSectionProposition
     */
    public function setIdSectionProposition(int $idSectionProposition): void
    {
        $this->idSectionProposition = $idSectionProposition;
    }

    /**
     * @return string
     */
    public function getTitreSectionProposition(): string
    {
        return $this->titreSectionProposition;
    }

    /**
     * @param string $titreSectionProposition
     */
    public function setTitreSectionProposition(string $titreSectionProposition): void
    {
        $this->titreSectionProposition = $titreSectionProposition;
    }

    /**
     * @return string
     */
    public function getTexteSectionProposition(): string
    {
        return $this->texteSectionProposition;
    }

    /**
     * @param string $texteSectionProposition
     */
    public function setTexteSectionProposition(string $texteSectionProposition): void
    {
        $this->texteSectionProposition = $texteSectionProposition;
    }


}