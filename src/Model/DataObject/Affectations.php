<?php

namespace App\AgoraScript\Model\DataObject;

class Affectations extends AbstractDataObject
{


    private string $login;
    private int $idQuestion;
    private string $role;

    /**
     * @param string $login
     * @param int $idQuestion
     * @param string $role
     */
    public function __construct(string $login, int $idQuestion, string $role)
    {
        $this->login = $login;
        $this->idQuestion = $idQuestion;
        $this->role = $role;
    }

    /**
     * @return string
     */
    public function getLogin(): string
    {
        return $this->login;
    }

    /**
     * @param string $login
     */
    public function setLogin(string $login): void
    {
        $this->login = $login;
    }

    /**
     * @return int
     */
    public function getIdQuestion(): int
    {
        return $this->idQuestion;
    }

    /**
     * @param int $idQuestion
     */
    public function setIdQuestion(int $idQuestion): void
    {
        $this->idQuestion = $idQuestion;
    }

    /**
     * @return string
     */
    public function getRole(): string
    {
        return $this->role;
    }

    /**
     * @param string $role
     */
    public function setRole(string $role): void
    {
        $this->role = $role;
    }

    public function estAuteur($idQuestionRecherche)
    {

    }


    public function formatTableau(): array
    {
        // TODO: Implement formatTableau() method.
        return array(
            "clesPrimaireTag" => $this->login,
            "idQuestionTag" => $this->idQuestion,
            "roleTag" => $this->role
        );
    }
}