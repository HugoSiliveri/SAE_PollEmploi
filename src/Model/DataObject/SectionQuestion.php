<?php

namespace App\AgoraScript\Model\DataObject;

class SectionQuestion extends AbstractDataObject
{
    private ?string $idSectionQuestion;
    private string $titreSectionQuestion;
    private string $descriptionSectionQuestion;
    private int $idQuestion;

    public function __construct(?string $idSectionQuestion, string $titreSectionQuestion, string $descriptionSectionQuestion, int $idQuestion)
    {
        $this->idSectionQuestion = $idSectionQuestion;
        $this->titreSectionQuestion = $titreSectionQuestion;
        $this->descriptionSectionQuestion = $descriptionSectionQuestion;
        $this->idQuestion = $idQuestion;
    }

    public function formatTableau(): array
    {
        // TODO: Implement formatTableau() method.
        return array(
            "clesPrimaireTag" => $this->idSectionQuestion,
            "titreSectionQuestionTag" => $this->titreSectionQuestion,
            "descriptionSectionQuestionTag" => $this->descriptionSectionQuestion,
            "idQuestionTag" => $this->idQuestion
        );
    }

    /**
     * @return int
     */
    public function getIdSectionQuestion(): int
    {
        return $this->idSectionQuestion;
    }

    /**
     * @return string
     */
    public function getTitreSectionQuestion(): string
    {
        return $this->titreSectionQuestion;
    }

    /**
     * @return string
     */
    public function getDescriptionSectionQuestion(): string
    {
        return $this->descriptionSectionQuestion;
    }

    /**
     * @return int
     */
    public function getIdQuestion(): int
    {
        return $this->idQuestion;
    }


}