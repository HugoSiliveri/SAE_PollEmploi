<?php

namespace App\AgoraScript\Lib;

use App\AgoraScript\Model\HTTP\Session;

class MessageFlash
{

    // Les messages sont enregistrés en session associée à la clé suivante
    private static string $cleFlash = "_messagesFlash";

    // $type parmi "success", "info", "warning" ou "danger"
    public static function ajouter(string $type, string $message): void
    {
        Session::getInstance()->enregistrer($type . static::$cleFlash, $message);
    }

    public static function lireTousMessages(): array
    {
        $messages = [];
        if (self::contientMessage("success")) {
            $messages[0] = self::lireMessages("success");
        } else {
            $messages[0] = [];
        }
        if (self::contientMessage("info")) {
            $messages[1] = self::lireMessages("info");
        } else {
            $messages[1] = [];
        }
        if (self::contientMessage("warning")) {
            $messages[2] = self::lireMessages("warning");
        } else {
            $messages[2] = [];
        }
        if (self::contientMessage("danger")) {
            $messages[3] = self::lireMessages("danger");
        } else {
            $messages[3] = [];
        }
        return $messages;
    }

    // Attention : la lecture doit détruire le message

    public static function contientMessage(string $type): bool
    {
        return Session::getInstance()->contient($type . static::$cleFlash);
    }

    public static function lireMessages(string $type): array
    {
        $name = $type . static::$cleFlash;
        $messages = Session::getInstance()->lire($name);
        Session::getInstance()->supprimer($name);
        return $messages;
    }

}