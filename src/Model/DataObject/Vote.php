<?php

namespace App\AgoraScript\Model\DataObject;

class Vote extends AbstractDataObject
{

    private string $login;
    private int $idQuestion;

    /**
     * @param string|null $login
     * @param int|null $idQuestion
     */
    public function __construct(string $login, int $idQuestion)
    {
        $this->login = $login;
        $this->idQuestion = $idQuestion;
    }


    /**
     * @return int|null
     */
    public function getLogin(): ?string
    {
        return $this->login;
    }

    /**
     * @param int|null $login
     */
    public function setLogin(?string $login): void
    {
        $this->login = $login;
    }

    /**
     * @return int|null
     */
    public function getIdQuestion(): ?int
    {
        return $this->idQuestion;
    }

    /**
     * @param int|null $idQuestion
     */
    public function setIdQuestion(?int $idQuestion): void
    {
        $this->idQuestion = $idQuestion;
    }


    public function formatTableau(): array
    {
        // TODO: Implement formatTableau() method.
        return array(
            "clesPrimaireTag" => $this->login,
            "idQuestionTag" => $this->idQuestion,
        );
    }
}