<?php

namespace App\AgoraScript\Model\HTTP;

use App\AgoraScript\Config\Conf;
use Exception;

class Session
{
    private static ?Session $instance = null;

    /**
     * @throws Exception
     */
    private function __construct()
    {
        if (session_start() === false) {
            throw new Exception("La session n'a pas réussi à démarrer.");
        }
    }

    public static function getInstance(): Session
    {
        if (is_null(static::$instance)) {
            static::$instance = new Session();
            static::verifierDerniereActivite();
        }
        return static::$instance;
    }

    public static function verifierDerniereActivite()
    {
        if (isset($_SESSION['derniereActivite']) && time() - $_SESSION['derniereActivite'] > (Conf::getDureeExpiration())) {
            session_unset();
        }
        $_SESSION['derniereActivite'] = time();
    }

    public function contient($name): bool
    {
        return isset($_SESSION[$name]);
    }

    public function enregistrer(string $name, $value): void
    {
        $_SESSION[$name][] = $value;
    }

    public function lire(string $name)
    {
        return $_SESSION[$name];
    }

    public function detruire(): void
    {
        session_unset();     // unset $_SESSION variable for the run-time
        session_destroy();   // destroy session data in storage
        Cookie::supprimer(session_name()); // deletes the session cookie
        static::$instance = null;
    }

    public function supprimer($name): void
    {
        unset($_SESSION[$name]);
    }
}
