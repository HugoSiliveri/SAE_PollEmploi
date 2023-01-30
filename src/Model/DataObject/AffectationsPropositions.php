<?php

namespace App\AgoraScript\Model\DataObject;

class AffectationsPropositions extends AbstractDataObject
{

    private string $login;
    private int $idQuestion;
    private int $idProposition;
    private string $role;

    /**
     * @param string $login
     * @param int $idQuestion
     * @param int $idProposition
     * @param string $role
     */
    public function __construct(string $login, int $idQuestion, int $idProposition, string $role)
    {
        $this->login = $login;
        $this->idQuestion = $idQuestion;
        $this->idProposition = $idProposition;
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

    /**
     * @return int
     */
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


    public function formatTableau(): array
    {
        // TODO: Implement formatTableau() method.
        return array(
            "clesPrimaireTag" => $this->login,
            "idQuestionTag" => $this->idQuestion,
            "idPropositionTag" => $this->idProposition,
            "roleTag" => $this->role
        );
    }

}