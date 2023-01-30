<?php

namespace App\AgoraScript\Model\DataObject;

class Like extends AbstractDataObject
{

    private int $idSectionProposition;
    private string $login;

    /**
     * @param int $idSectionProposition
     * @param string $login
     */
    public function __construct(int $idSectionProposition, string $login)
    {
        $this->idSectionProposition = $idSectionProposition;
        $this->login = $login;
    }


    public function formatTableau(): array
    {
        // TODO: Implement formatTableau() method.
        return array(
            "clesPrimaireTag" => $this->idSectionProposition,
            "loginTag" => $this->login
        );
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


}