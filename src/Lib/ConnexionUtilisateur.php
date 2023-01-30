<?php

namespace App\AgoraScript\Lib;

use App\AgoraScript\Model\HTTP\Session;
use App\AgoraScript\Model\Repository\AffectationsPropositionsRepository;
use App\AgoraScript\Model\Repository\AffectationsRepository;
use App\AgoraScript\Model\Repository\CommentaireRepository;
use App\AgoraScript\Model\Repository\DemandeRoleRepository;
use App\AgoraScript\Model\Repository\UtilisateurRepository;

class ConnexionUtilisateur
{
// L'utilisateur connecté sera enregistré en session associé à la clé suivante
    private static string $cleConnexion = "_utilisateurConnecte";

    public static function connecter(string $loginUtilisateur): void
    {
        $session = Session::getInstance();
        $session->enregistrer(static::$cleConnexion, $loginUtilisateur);
    }

    public static function deconnecter(): void
    {
        $session = Session::getInstance();
        $session->supprimer(static::$cleConnexion);
    }

    public static function getRolesUtilisateurConnecte(): ?array
    {
        if (ConnexionUtilisateur::estConnecte()) {
            $tabAffectation = (new AffectationsRepository())->getAffectations(self::getLoginUtilisateurConnecte());
            $tabRoles = array();
            foreach ($tabAffectation as $affectation) {
                $tabRoles[] = array($affectation->getIdQuestion() => $affectation->getRole());
            }
            return $tabRoles;
        } else {
            return null;
        }
    }

    public static function estConnecte(): bool
    {
        $session = Session::getInstance();
        return $session->contient(static::$cleConnexion);
    }

    public static function getLoginUtilisateurConnecte(): ?string
    {
        $session = Session::getInstance();
        if (!self::estConnecte()) return null;
        return $session->lire(static::$cleConnexion)[0];
    }

    public static function estUtilisateur($login): bool
    {
        return strcmp(self::getLoginUtilisateurConnecte(), $login) == 0;
    }

    public static function estAdministrateur(): bool
    {
        $userConnecte = self::getLoginUtilisateurConnecte();
        if (isset($userConnecte)) {
            $utilisateur = (new UtilisateurRepository())->select($userConnecte);
            $admin = $utilisateur->getEstAdministrateur();
            return $admin == 1;
        } else {
            return false;
        }
    }

    public static function estOrganisateur(): bool
    {
        $userConnecte = self::getLoginUtilisateurConnecte();
        if (isset($userConnecte)) {
            $utilisateur = (new UtilisateurRepository())->select($userConnecte);
            $orga = $utilisateur->getEstOrganisateur();
            return $orga == 1;
        }
        return false;
    }

    public static function estExpert(): bool
    {
        $userConnecte = self::getLoginUtilisateurConnecte();
        if (isset($userConnecte)) {
            $utilisateur = (new UtilisateurRepository())->select($userConnecte);
            $expert = $utilisateur->getEstExpert();
            return $expert == 1;
        }
        return false;
    }

    public static function demandeEnCours(): bool
    {
        $userConnecte = self::getLoginUtilisateurConnecte();
        if (isset($userConnecte)) {
            $demande = (new DemandeRoleRepository())->select($userConnecte);
            return isset($demande);
        }
        return false;
    }

    public static function estOrganisateurSurQuestion($idQuestion): bool
    {
        $userConnecte = self::getLoginUtilisateurConnecte();
        if (isset($userConnecte)) {
            $roles = (new AffectationsRepository())->getAffectations($userConnecte);
            foreach ($roles as $affectation) {
                if ($affectation->getIdQuestion() == $idQuestion && strcmp(Role::Organisateur, $affectation->getRole()) == 0) {
                    return true;
                }
            }
        }
        return false;
    }


    public static function estResponsableP($idQuestion): bool
    {
        $userConnecte = self::getLoginUtilisateurConnecte();
        if (isset($userConnecte)) {
            $roles = (new AffectationsRepository())->getAffectations($userConnecte);
            foreach ($roles as $affectation) {
                if ($affectation->getIdQuestion() == $idQuestion && strcmp(Role::ResponsableP, $affectation->getRole()) == 0) {
                    return true;
                }
            }
        }
        return false;
    }

    public static function estResponsablePsurP($idProposition): bool
    {
        $userConnecte = self::getLoginUtilisateurConnecte();
        if (isset($userConnecte)) {
            $roles = (new AffectationsPropositionsRepository())->getAffectationsPropositions($userConnecte);
            foreach ($roles as $affectation) {
                if ($affectation->getIdProposition() == $idProposition && strcmp(Role::ResponsableP, $affectation->getRole()) == 0) {
                    return true;
                }
            }
        }
        return false;
    }

    public static function estVotant($idQuestion): bool
    {
        $userConnecte = self::getLoginUtilisateurConnecte();
        if (isset($userConnecte)) {
            $roles = (new AffectationsRepository())->getAffectations($userConnecte);
            foreach ($roles as $affectation) {
                if ($affectation->getIdQuestion() == $idQuestion && strcmp(Role::Votant, $affectation->getRole()) == 0) {
                    return true;
                }
            }
        }
        return false;
    }

    public static function estAuteur($idProposition): bool
    {
        $userConnecte = self::getLoginUtilisateurConnecte();
        if (isset($userConnecte)) {
            $roles = (new AffectationsPropositionsRepository())->getAffectationsPropositions($userConnecte);
            foreach ($roles as $affectation) {
                if ($affectation->getIdProposition() == $idProposition && strcmp(Role::Auteur, $affectation->getRole()) == 0) {
                    return true;
                }
            }
        }
        return false;
    }

    public static function estSansRole(): bool
    {
        $userConnecte = self::getLoginUtilisateurConnecte();
        if (isset($userConnecte)) {
            $roles = (new AffectationsRepository())->getAffectations($userConnecte);
            return sizeof($roles) == 0;
        } else {
            return false;
        }
    }

    public static function estSansRoleparLogin($login): bool
    {
        $utilisateur = (new UtilisateurRepository())->select($login);
        if (isset($utilisateur)) {
            $roles = (new AffectationsRepository())->getAffectations($login);
            return empty($roles);
        } else {
            return false;
        }
    }

    public static function estAuteurCommentaire($idCommentaire): bool
    {
        $utilisateur = self::getLoginUtilisateurConnecte();
        if (isset($utilisateur)) {
            return (new CommentaireRepository())->estLieAuCommentaire($utilisateur, $idCommentaire);
        } else {
            return false;
        }
    }


}