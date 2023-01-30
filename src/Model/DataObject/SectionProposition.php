<?php

namespace App\AgoraScript\Model\DataObject;

class SectionProposition extends AbstractDataObject
{

    private ?int $idSectionProposition;
    private string $titreSectionProposition;
    private int $idProposition;
    private int $idSectionQuestion;
    private string $texteSectionProposition;

    /**
     * @param int|null $idSectionProposition
     * @param string $titreSectionProposition
     * @param int $idProposition
     * @param int $idSectionQuestion
     * @param string $texteSectionProposition
     */
    public function __construct(?int $idSectionProposition, string $titreSectionProposition, int $idProposition, int $idSectionQuestion, string $texteSectionProposition)
    {
        $this->idSectionProposition = $idSectionProposition;
        $this->titreSectionProposition = $titreSectionProposition;
        $this->idProposition = $idProposition;
        $this->idSectionQuestion = $idSectionQuestion;
        $this->texteSectionProposition = $texteSectionProposition;
    }


    public function formatTableau(): array
    {
        // TODO: Implement formatTableau() method.
        return array(
            "clesPrimaireTag" => $this->idSectionProposition,
            "titreSectionPropositionTag" => $this->titreSectionProposition,
            "idPropositionTag" => $this->idProposition,
            "idSectionQuestionTag" => $this->idSectionQuestion,
            "texteSectionPropositionTag" => $this->texteSectionProposition
        );
    }

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

    public function getIdSectionProposition(): int
    {
        return $this->idSectionProposition;
    }

    /**
     * @param int|null $idSectionProposition
     */
    public function setIdSectionProposition(?int $idSectionProposition): void
    {
        $this->idSectionProposition = $idSectionProposition;
    }

    public function getIdProposition(): int
    {
        return $this->idProposition;
    }

    /**
     * @param int $idProposition
     */
    public function setIdProposition(int $idProposition): void
    {
        $this->idProposition = $idProposition;
    }

    public function getIdSectionQuestion(): int
    {
        return $this->idSectionQuestion;
    }

    /**
     * @param int $idSectionQuestion
     */
    public function setIdSectionQuestion(int $idSectionQuestion): void
    {
        $this->idSectionQuestion = $idSectionQuestion;
    }

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