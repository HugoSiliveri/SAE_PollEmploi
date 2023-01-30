<?php

namespace App\AgoraScript\Model\DataObject;

class Reponse extends AbstractDataObject
{

    private ?int $idReponse;
    private int $idCommentaire;
    private string $login;
    private string $message;
    private string $datePoste;

    /**
     * @param int|null $idReponse
     * @param int $idCommentaire
     * @param string $login
     * @param string $message
     */
    public function __construct(?int $idReponse, int $idCommentaire, string $login, string $message, string $datePoste)
    {
        $this->idReponse = $idReponse;
        $this->idCommentaire = $idCommentaire;
        $this->login = $login;
        $this->message = $message;
        $this->datePoste = $datePoste;
    }


    public function formatTableau(): array
    {
        return array(
            "clesPrimaireTag" => $this->idReponse,
            "idCommentaireTag" => $this->idCommentaire,
            "loginTag" => $this->login,
            "messageTag" => $this->message,
            "datePosteTag" => $this->datePoste
        );
    }

    /**
     * @return int|null
     */
    public function getIdReponse(): ?int
    {
        return $this->idReponse;
    }

    /**
     * @param int|null $idReponse
     */
    public function setIdReponse(?int $idReponse): void
    {
        $this->idReponse = $idReponse;
    }

    /**
     * @return string
     */
    public function getDatePoste(): string
    {
        return $this->datePoste;
    }

    /**
     * @param string $datePoste
     */
    public function setDatePoste(string $datePoste): void
    {
        $this->datePoste = $datePoste;
    }


    /**
     * @return int
     */
    public function getIdCommentaire(): int
    {
        return $this->idCommentaire;
    }

    /**
     * @param int $idCommentaire
     */
    public function setIdCommentaire(int $idCommentaire): void
    {
        $this->idCommentaire = $idCommentaire;
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


}