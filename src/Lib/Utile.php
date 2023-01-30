<?php

namespace App\AgoraScript\Lib;

use App\AgoraScript\Model\Repository\NombreVoteRepository;
use App\AgoraScript\Model\Repository\PropositionRepository;
use App\AgoraScript\Model\Repository\QuestionRepository;

abstract class Utile
{
    public static function getMentionsSuperieurs($tabPourcentage): array
    {
        $mentionsAvecPourcentage = array();
        $tabNomVote = array("AR", "II", "PP", "AB", "BB", "TB");
        $pourcentageInf = 0;
        $i = sizeof($tabNomVote) - 1;
        while ($pourcentageInf < 50) {
            $pourcentageInf += $tabPourcentage[$i];
            if ($pourcentageInf < 50) {
                $mentionsAvecPourcentage[$tabNomVote[$i]] = $tabPourcentage[$i];
            }
            $i--;
        }
        return $mentionsAvecPourcentage;
    }

    public static function resultat($idQuestion): void
    {
        //Fonction appeler une seule fois lorsque l'état de la question passe en 4 (Fin du vote)
        //if(isset($_GET['id'])){
        //$idQuestion = $_GET['id'];
        $question = (new QuestionRepository())->select($idQuestion);
        $idPropositionGagnante = array();
        if ($question->getEtatQuestion() == 3) {
            $propositions = (new QuestionRepository())->getPropositionsTab($idQuestion);
            $tabMentionMajoritaire = array();
            $tabPourcentage = array();
            $tabNomVote = array("AR", "II", "PP", "AB", "BB", "TB");
            foreach ($propositions as $proposition) {

                $idProposition = $proposition->getIdProposition();
                $nbVoteSelect = (new NombreVoteRepository())->select($proposition->getIdProposition());

                if (is_null($nbVoteSelect)) {
                    break;
                }

                $nbVote = $nbVoteSelect->nbTotal();

                if ($nbVote == 0) {
                    break;
                }

                $nbTB = round((floatval($nbVoteSelect->getNbTB() * 100) / $nbVote));
                $nbBB = round((floatval($nbVoteSelect->getNbBB() * 100) / $nbVote));
                $nbAB = round((floatval($nbVoteSelect->getNbAB() * 100) / $nbVote));
                $nbPP = round((floatval($nbVoteSelect->getNbPP() * 100) / $nbVote));
                $nbII = round((floatval($nbVoteSelect->getNbII() * 100) / $nbVote));
                $nbAR = round((floatval($nbVoteSelect->getNbAR() * 100) / $nbVote));
                $tabVote = array($nbAR, $nbII, $nbPP, $nbAB, $nbBB, $nbTB);
                $voteMajoritaire = 0;
                $i = 0;

                while ($voteMajoritaire < 50) {
                    $voteMajoritaire += $tabVote[$i];
                    ++$i;
                }

                $tabMentionMajoritaire[$idProposition] = $tabNomVote[$i - 1];
                $tabPourcentage[$idProposition] = $tabVote;
            }

            if (count($tabMentionMajoritaire) == 0) {
                return;
            }

            $tabCountValues = array_count_values($tabMentionMajoritaire);

            $i = 5;
            $condition = false;
            while (!$condition) {

                //Tant que l'on a pas trouvé la question gagnante...
                $valeur = $tabNomVote[$i]; //On récupère la valeur de la mention ex : "TB" ou "AB"
                if (in_array($valeur, $tabMentionMajoritaire)) { //S'il y a des propositions qui ont obtenus la mention majoritaire "$valeur" alors...
                    if ($tabCountValues[$valeur] > 1) { //Si on à plusieurs propositions qui ont obtenus cette mention majoritaire alors...
                        $idPropositionsDoublon = Utile::getKeysParRapportALaValeur($tabMentionMajoritaire, $valeur, false); //On récupère tous les id de propositions qui ont obtenus la mention majoritaire "$valeur"

                        $sommeInferieurs = array();

                        foreach ($idPropositionsDoublon as $idProposition) {
                            //On boucle pour chaque idProposition qui est en doublon pour la mention majoritaire "$valeur"
                            $mentionsInferieurs = Utile::getMentionsInferieurs($tabPourcentage[$idProposition]); //Tableau de la forme "AR" => 21, "II" => 18 etc...
                            $sommeInferieurs[$idProposition] = array_sum($mentionsInferieurs); // Pour chaque proposition, on fait la somme des pourcentages des mentions infèrieures
                        }
                        $minimum = min($sommeInferieurs); // On vérifie quelle est la valeur minimale

                        $nbEgalite = count(array_keys($sommeInferieurs, $minimum)); // On vérifie si il y a des égalités aussi dans les mentions inférieurs (rare mais peut arriver)
                        if ($nbEgalite > 1) {
                            //Si nous avons encore des doublons de propositions qui ont la même proportion de personnes en dessous de la mention inférieure alors nous allons tester
                            $idPropositionDoublonSomme = Utile::getKeysParRapportALaValeur($sommeInferieurs, $minimum, true); //On récupère alors les id de propositions qui sont bien en doublon par rapport à leur somme inférieure

                            $mentionsInferieuresParRapportAProposition = array();
                            foreach ($idPropositionDoublonSomme as $idProposition) {
                                $mentionsInferieurs = Utile::getMentionsInferieurs($tabPourcentage[$idProposition]); //On récupère le tableau des mentions inférieures de la propotition
                                $mentionsInferieuresParRapportAProposition[$idProposition] = $mentionsInferieurs; //Pour chaque proposition on lui affecte son tableau de mention inférieur
                            }


                            $idPropositionGagnante = Utile::getPropositionGagnantes($mentionsInferieuresParRapportAProposition, $valeur, true);
                            $condition = true;
                        } else {
                            $condition = true;
                            $idPropositionGagnante[] = array_search($minimum, $sommeInferieurs);
                        }
                    } else {
                        $condition = true;
                        $idPropositionGagnante[] = array_search($valeur, $tabMentionMajoritaire);
                    }
                }
                --$i;
                if ($i == -1) {
                    $condition = true;
                }
            }
        }
        $nbGagnant = count($idPropositionGagnante);
        if ($nbGagnant > 0) {
            foreach ($idPropositionGagnante as $idProposition) {
                $proposition = (new PropositionRepository())->select($idProposition);
                $proposition->setGagnante(1);
                (new PropositionRepository())->update($proposition);
            }
        }
    }

    public static function getKeysParRapportALaValeur($tab, $valeur, $comparaisonEntier): array
    {
        $keys = array_keys($tab); //On récupère toutes les valeurs des clés du tableau
        $keysParValeur = array(); //On prépare le tableau qui va acceuillir
        $i = 0;
        if (!$comparaisonEntier) {
            foreach ($tab as $value) {
                if (strcmp($value, $valeur) == 0) {
                    $keysParValeur[] = $keys[$i];
                }
                ++$i;
            }
        } else {
            foreach ($tab as $value) {
                if ($value == $valeur) {
                    $keysParValeur[] = $keys[$i];
                }
                ++$i;
            }
        }
        return $keysParValeur;
    }

    public static function getMentionsInferieurs($tabPourcentage): array
    {
        $mentionsAvecPourcentage = array();
        $tabNomVote = array("AR", "II", "PP", "AB", "BB", "TB");
        $pourcentageInf = 0;
        $i = 0;
        while ($pourcentageInf < 50) {
            $pourcentageInf += $tabPourcentage[$i];
            if ($pourcentageInf < 50) {
                $mentionsAvecPourcentage[$tabNomVote[$i]] = $tabPourcentage[$i];
            }
            $i++;
        }
        return $mentionsAvecPourcentage;
    }

    public static function getPropositionGagnantes($tableau, $mentionMajoritaire, $pourInferieur): array
    {
        $propositionGagnantes = array();
        $tableauProposition = $tableau;
        $tabNomVote = array("AR", "II", "PP", "AB", "BB", "TB");
        $clesMentionMajoritaire = array_search($mentionMajoritaire, $tabNomVote); //On récupère la clés de la mention majoritaire exemple : Si "AB" alors on aura 3


        if ($pourInferieur) {
            //Si on doit comparer les propositions par rapport à l'infériorité alors...
            /*
             * Le but est ici de comparer les pourcentages des mentions pour chaque proposition
             * Conditions : Comme nous comparons déjà des propositions possèdants la même mention majoritaire alors nous savons que leur tableau sera de la même forme
             *              Nous pouvons donc comparer les tableaux sans trop de soucis et accèder aux mêmes clès du tableau
             *              exemple :
             *                    Si $mentionMajoritaire = "PP" alors nous savons que le tableau sera de la forme : {"AR" => ??, "II" => ??}
             *
             *              De plus, nous savons déjà que la somme des mentions inférieures est égale.
             *
             * Par exemple : Si la proposition 1 possède le tableau suivant => {"AR" => 12, "II" => 21}
             *               et la proposition 2 possède le tableau suivant => {"AR" => 14, "II" => 19}
             *               Alors la proposition 1 gagnera car plus de personnes l'on trouvée "acceptable"
             */

            for ($i = 0; $i < $clesMentionMajoritaire; $i++) {

                $tab = array();
                foreach ($tableauProposition as $idProposition => $tableauMentions) {
                    //Pour toutes les propositions on va associer son id avec la valeur de la mention pour laquelle on regarde
                    $tab[$idProposition] = $tableauMentions[$tabNomVote[$i]];
                }

                //Pour toutes les mentions inférieures nous allons comparer les propositions
                $minimum = min($tab); //On récupère la valeur minimum possible pour la mention que l'on étudie
                $tabKeysEgalesString = array_keys($tab, $minimum);


                $tabKeysEgales = array_map('intval', $tabKeysEgalesString);


                $tabKeysDiff = array_diff($tableauProposition, $tabKeysEgales);
                $nbEgalite = count($tabKeysEgales); //On compte le nombre de proposition qui ont eu la même proportion pour la mention que l'on étudie

                if ($nbEgalite > 1) {
                    //S'il y a plusieurs égalités alors nous allons simplement continuer la boucle en enlevant les propositions qui ne sont pas égales

                    if ($i + 1 == $clesMentionMajoritaire) {
                        //Si au prochain tours de boucle nous finissons le traitement alors...
                        return $tabKeysEgales;
                    }

                    foreach ($tabKeysDiff as $idProposition) {
                        unset($tableauProposition[(int)$idProposition]); //Nous retirons toutes les propositions qui ne sont pas égales et nous continuons la boucle
                    }
                } else {
                    $propositionGagnantes[] = array_search($minimum, $tab);
                    return $propositionGagnantes;
                }
            }
        }
        return $propositionGagnantes;
    }


}