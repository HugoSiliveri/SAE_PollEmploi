<?php

namespace App\AgoraScript\Model\DataObject;

class Commentaire extends AbstractDataObject
{
    private ?int $idCommentaire;
    private string $login;
    private int $idProposition;
    private string $message;
    private string $datePoste;
    private int $modifier;

    /**
     * @param int|null $idCommentaire
     * @param string $login
     * @param string $message
     * @param string $datePoste
     */
    public function __construct(?int $idCommentaire, string $login, int $idProposition, string $message, string $datePoste, int $modifier)
    {
        $this->idCommentaire = $idCommentaire;
        $this->login = $login;
        $this->idProposition = $idProposition;
        $this->message = $message;
        $this->datePoste = $datePoste;
        $this->modifier = $modifier;
    }

    public function formatTableau(): array
    {
        // TODO: Implement formatTableau() method.
        return array(
            "clesPrimaireTag" => $this->idCommentaire,
            "loginTag" => $this->login,
            "idPropositionTag" => $this->idProposition,
            "messageTag" => $this->message,
            "datePosteTag" => $this->datePoste,
            "modifierTag" => $this->modifier
        );
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

    /**
     * @return int
     */
    public function getModifier(): int
    {
        return $this->modifier;
    }

    /**
     * @param int $modifier
     */
    public function setModifier(int $modifier): void
    {
        $this->modifier = $modifier;
    }

    /**
     * @return string
     */
    public function getLogin(): string
    {
        return $this->login;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @param string $message
     */
    public function setMessage(string $message): void
    {
        $this->message = $message;
    }

    /**
     * @return string
     */
    public function getDatePoste(): string
    {
        return $this->datePoste;
    }

    /**
     * @return int|null
     */
    public function getIdCommentaire(): ?int
    {
        return $this->idCommentaire;
    }


}