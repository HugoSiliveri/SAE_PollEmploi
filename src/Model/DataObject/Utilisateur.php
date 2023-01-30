<?php

namespace App\AgoraScript\Model\DataObject;

use App\AgoraScript\Lib\MotDePasse;
use App\AgoraScript\Lib\Role;

class Utilisateur extends AbstractDataObject
{

    private string $login;
    private string $nomUtilisateur;
    private string $prenomUtilisateur;
    private string $mdpHache;
    private int $estAdministrateur;
    private string $email;
    private string $emailAValider;
    private string $nonce;
    private int $estOrganisateur;
    private int $estExpert;


    public function __construct(string $login, string $nom, string $prenom, string $adresseMail, string $mdp, int $estAdministrateur, string $emailAValider, string $nonce, int $estOrganisateur, int $estExpert)
    {
        $this->login = $login;
        $this->nomUtilisateur = $nom;
        $this->prenomUtilisateur = $prenom;
        $this->email = $adresseMail;
        $this->mdpHache = $mdp;
        $this->estAdministrateur = $estAdministrateur;
        $this->emailAValider = $emailAValider;
        $this->nonce = $nonce;
        $this->estOrganisateur = $estOrganisateur;
        $this->estExpert = $estExpert;
    }

    public static function construireDepuisFormulaire(array $tableauFormulaire): Utilisateur
    {
        $mdpHache = MotDePasse::hacher($tableauFormulaire['mdp']);
        $nonce = MotDePasse::genererChaineAleatoire();

        if (!isset($tableauFormulaire['estAdministrateur'])) {
            $estAdministrateur = 0;
        } else {
            $estAdministrateur = $tableauFormulaire['estAdministrateur'];
        }
        if (!isset($tableauFormulaire['estOrganisateur'])) {
            $estOrganisateur = 0;
        } else {
            $estOrganisateur = $tableauFormulaire['estOrganisateur'];
        }
        if (!isset($tableauFormulaire['estExpert'])) {
            $estExpert = 0;
        } else {
            $estExpert = $tableauFormulaire['estExpert'];
        }
        return new Utilisateur($tableauFormulaire['login'], $tableauFormulaire['nomUtilisateur'], $tableauFormulaire['prenomUtilisateur'], "", $mdpHache, $estAdministrateur, $tableauFormulaire['adresseMail'], $nonce, $estOrganisateur, $estExpert);
    }

    public function formatTableau(): array
    {
        // TODO: Implement formatTableau() method.
        return array(
            "clesPrimaireTag" => $this->login,
            "nomUtilisateurTag" => $this->nomUtilisateur,
            "prenomUtilisateurTag" => $this->prenomUtilisateur,
            "adresseMailTag" => $this->email,
            "motDePasseTag" => $this->mdpHache,
            "estAdministrateurTag" => $this->estAdministrateur,
            "estOrganisateurTag" => $this->estOrganisateur,
            "estExpertTag" => $this->estExpert,
            "emailAValiderTag" => $this->emailAValider,
            "nonceTag" => $this->nonce
        );
    }

    /**
     * @return int
     */
    public function getEstExpert(): int
    {
        return $this->estExpert;
    }

    /**
     * @param int $estExpert
     */
    public function setEstExpert(int $estExpert): void
    {
        $this->estExpert = $estExpert;
    }

    /**
     * @return string
     */
    public function getMdpHache(): string
    {
        return $this->mdpHache;
    }

    /**
     * @param string $mdpHache
     */
    public function setMdpHache(string $mdpClair): void
    {
        $this->mdpHache = MotDePasse::hacher($mdpClair);
    }

    /**
     * @return string
     */
    public function getNomUtilisateur(): string
    {
        return $this->nomUtilisateur;
    }

    /**
     * @param string $nomUtilisateur
     */
    public function setNomUtilisateur(string $nomUtilisateur): void
    {
        $this->nomUtilisateur = $nomUtilisateur;
    }

    /**
     * @return string
     */
    public function getPrenomUtilisateur(): string
    {
        return $this->prenomUtilisateur;
    }

    /**
     * @param string $prenomUtilisateur
     */
    public function setPrenomUtilisateur(string $prenomUtilisateur): void
    {
        $this->prenomUtilisateur = $prenomUtilisateur;
    }

    /**
     * @return string
     */
    public function getAdresseMail(): string
    {
        return $this->email;
    }

    /**
     * @param string $adresseMail
     */
    public function setAdresseMail(string $adresseMail): void
    {
        $this->email = $adresseMail;
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
     * @return Role
     */
    public function getEstAdministrateur(): string
    {
        return $this->estAdministrateur;
    }

    /**
     * @param Role $estAdministrateur
     */
    public function setEstAdministrateur(int $estAdministrateur): void
    {
        $this->estAdministrateur = $estAdministrateur;
    }

    /**
     * @return string
     */
    public function getEmailAValider(): string
    {
        return $this->emailAValider;
    }

    /**
     * @param string $emailAValider
     */
    public function setEmailAValider(string $emailAValider): void
    {
        $this->emailAValider = $emailAValider;
    }

    /**
     * @return string
     */
    public function getNonce(): string
    {
        return $this->nonce;
    }

    /**
     * @param string $nonce
     */
    public function setNonce(string $nonce): void
    {
        $this->nonce = $nonce;
    }

    /**
     * @return int
     */
    public function getEstOrganisateur(): int
    {
        return $this->estOrganisateur;
    }

    /**
     * @param int $estOrganisateur
     */
    public function setEstOrganisateur(int $estOrganisateur): void
    {
        $this->estOrganisateur = $estOrganisateur;
    }


}