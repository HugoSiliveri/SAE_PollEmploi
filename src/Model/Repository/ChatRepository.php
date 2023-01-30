<?php

namespace App\AgoraScript\Model\Repository;

use App\AgoraScript\Model\DataObject\AbstractDataObject;
use App\AgoraScript\Model\DataObject\Chat;

class ChatRepository extends AbstractRepository
{

    public function selectAllMessagesPourUneProposition($idProposition): array
    {
        $messages = array();

        $sql = "SELECT * FROM PropositionChat WHERE idProposition=:idPropositionTag";

        $pdoStatement = DatabaseConnection::getPdo()->prepare($sql);

        $values = array(
            "idPropositionTag" => $idProposition,
        );

        $pdoStatement->execute($values);

        foreach ($pdoStatement as $messagesFormatTableau) {
            $messages[] = (new ChatRepository())->construire($messagesFormatTableau);
        }

        return $messages;
    }

    public function construire(array $objetFormatTableau): AbstractDataObject
    {
        return new Chat(
            $objetFormatTableau['idMessage'],
            $objetFormatTableau['idProposition'],
            $objetFormatTableau['login'],
            $objetFormatTableau['dateMessage'],
            $objetFormatTableau['message']
        );
    }

    protected function getNomTable(): string
    {
        return "PropositionChat";
    }

    protected function getNomClePrimaire(): string
    {
        return "idMessage";
    }

    protected function getNomsColonnes(): array
    {
        return [
            "idProposition" => "idProposition",
            "login" => "login",
            "dateMessage" => "dateMessage",
            "message" => "message",
        ];
    }
}