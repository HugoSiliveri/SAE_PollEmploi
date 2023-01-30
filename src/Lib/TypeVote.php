<?php

namespace App\AgoraScript\Lib;

abstract class TypeVote
{
    const SCRUTIN_MAJORITAIRE = "scrutinMajoritaire";
    const VOTE_CUMULATIF = "voteCumulatif";
    const VOTE_PAR_VALEUR = "voteParValeur";

    public static function getVote(string $vote)
    {
        switch ($vote) {
            case "scrutinMajoritaire" :
                $type = TypeVote::SCRUTIN_MAJORITAIRE;
                break;
            case "voteCumulatif":
                $type = TypeVote::VOTE_CUMULATIF;
                break;
            case "voteParValeur":
                $type = TypeVote::VOTE_PAR_VALEUR;
                break;
            default:
                $type = "";
        }
        return $type;
    }


}