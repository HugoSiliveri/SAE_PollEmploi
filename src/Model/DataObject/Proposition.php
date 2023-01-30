<?php

namespace App\AgoraScript\Model\DataObject;

use App\AgoraScript\Model\Repository\PropositionRepository;

class Proposition extends AbstractDataObject
{

    private ?int $idProposition;
    private int $idQuestion;
    private string $intituleProposition;
    private int $nbPoints;
    private int $gagnante; //1 pour vrai, 0 pour faux

    public function __construct(?int $idProposition, int $Question, string $intituleProposition)
    {
        $this->idProposition = $idProposition;
        $this->idQuestion = $Question;
        $this->intituleProposition = $intituleProposition;
        $this->nbPoints = 0;
        $this->gagnante = 0;
    }

    public function formatTableau(): array
    {
        // TODO: Implement formatTableau() method.
        return array(
            "clesPrimaireTag" => $this->idProposition,
            "idQuestionTag" => $this->idQuestion,
            "intitulePropositionTag" => $this->intituleProposition,
            "nbPointsTag" => $this->nbPoints,
            "gagnanteTag" => $this->gagnante
        );
    }

    public function getIdProposition(): int
    {
        return $this->idProposition;
    }

    public function getIdQuestion(): int
    {
        return $this->idQuestion;
    }

    public function getIntituleProposition(): string
    {
        return $this->intituleProposition;
    }

    public function getNbPoints(): int
    {
        return (new PropositionRepository())->getNbPoints($this->idProposition);
    }

    /**
     * @return int
     */
    public function getGagnante(): int
    {
        return $this->gagnante;
    }

    /**
     * @param int $gagnante
     */
    public function setGagnante(int $gagnante): void
    {
        $this->gagnante = $gagnante;
    }


}