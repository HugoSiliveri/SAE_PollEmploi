<?php

namespace App\AgoraScript\Model\DataObject;

class Chat extends AbstractDataObject
{
    private ?int $idMessage;
    private string $login;
    private int $idProposition;
    private string $message;
    private string $dateMessage;

    /**
     * @param int|null $idMessage
     * @param string $login
     * @param string $message
     * @param string $dateMessage
     */
    public function __construct(?int $idMessage, int $idProposition, string $login, string $dateMessage, string $message)
    {
        $this->idMessage = $idMessage;
        $this->idProposition = $idProposition;
        $this->login = $login;
        $this->dateMessage = $dateMessage;
        $this->message = $message;
    }

    public function formatTableau(): array
    {
        // TODO: Implement formatTableau() method.
        return array(
            "clesPrimaireTag" => $this->idMessage,
            "idPropositionTag" => $this->idProposition,
            "loginTag" => $this->login,
            "dateMessageTag" => $this->dateMessage,
            "messageTag" => $this->message
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
    public function getDateMessage(): string
    {
        return $this->dateMessage;
    }

    /**
     * @return int|null
     */
    public function getIdMessage(): ?int
    {
        return $this->idMessage;
    }


}