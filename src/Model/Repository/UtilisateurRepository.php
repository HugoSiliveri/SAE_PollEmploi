<?php

namespace App\AgoraScript\Model\Repository;

use App\AgoraScript\Model\DataObject\AbstractDataObject;
use App\AgoraScript\Model\DataObject\Utilisateur;

class UtilisateurRepository extends AbstractRepository
{

    public function getNomTable(): string
    {
        // TODO: Implement getNomTable() method.
        return "Utilisateur";
    }


    public function construire(array $utilisateurTableau): AbstractDataObject
    {
        // TODO: Implement construire() method.
        return new Utilisateur(
            $utilisateurTableau["login"],
            $utilisateurTableau["nomUtilisateur"],
            $utilisateurTableau["prenomUtilisateur"],
            $utilisateurTableau["adresseMail"],
            $utilisateurTableau["motDePasse"],
            $utilisateurTableau['estAdministrateur'],
            $utilisateurTableau['emailAValider'],
            $utilisateurTableau['nonce'],
            $utilisateurTableau['estOrganisateur'],
            $utilisateurTableau['estExpert']
        );
    }

    public function getNomClePrimaire(): string
    {
        // TODO: Implement getNomClePrimaire() method.
        return "login";
    }

    public function getNomsColonnes(): array
    {
        // TODO: Implement getNomsColonnes() method.
        return array(
            "nomUtilisateur" => "nomUtilisateur",
            "prenomUtilisateur" => "prenomUtilisateur",
            "adresseMail" => "adresseMail",
            "motDePasse" => "motDePasse",
            "estAdministrateur" => "estAdministrateur",
            "estOrganisateur" => "estOrganisateur",
            "estExpert" => "estExpert",
            "emailAValider" => "emailAValider",
            "nonce" => "nonce"
        );
    }


    //Override function existe()
    public function existe(string $adresseMail, string $motDePasse): bool
    {

        $sql = "SELECT idUtilisateur FROM Utilisateur WHERE adresseMail=:adresseMailTag AND motDePasse=:motDePasseTag";
        $pdoStatement = DatabaseConnection::getPdo()->prepare($sql);

        $values = array(
            "adresseMailTag" => $adresseMail,
            "motDePasseTag" => $motDePasse,
        );


        $pdoStatement->execute($values);
        $utilisateur = $pdoStatement->fetch();

        return (bool)$utilisateur;
    }

    /*
    public function estAffecte(string $login, int $idQuestion): bool{
        $sql = "SELECT * FROM Utilisateur WHERE login=:loginTag AND idQuestion=:idQuestionTag";
        $pdoStatement = DatabaseConnection::getPdo()->prepare($sql);

        $values = array(
            "loginTag" => $login,
            "idQuestionTag" => $idQuestion,
        );


        $pdoStatement->execute($values);
        $utilisateur = $pdoStatement->fetch();

        return (bool)$utilisateur;
    }
    */


}