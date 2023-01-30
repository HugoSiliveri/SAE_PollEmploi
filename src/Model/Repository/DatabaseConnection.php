<?php

namespace App\AgoraScript\Model\Repository;

use App\AgoraScript\Config\Conf;
use PDO;

class DatabaseConnection
{
    private static ?DatabaseConnection $instance = null;
    private PDO $pdo;

    /**
     * @param $pdo
     */
    public function __construct()
    {
        $hostname = Conf::getHostname();
        $databaseName = Conf::getDatabase();
        $login = Conf::getLogin();
        $password = Conf::getPassword();
        $this->pdo = new PDO("mysql:host=$hostname;dbname=$databaseName", $login, $password,
            array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
        // On active le mode d'affichage des erreurs, et le lancement d'exception en cas d'erreur
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    /**
     * @return mixed
     */
    public static function getPdo(): PDO
    {
        return static::getInstance()->pdo;
    }

    private static function getInstance(): DatabaseConnection
    {
        // L'attribut statique $pdo s'obtient avec la syntaxe static::$pdo
        // au lieu de $this->pdo pour un attribut non statique
        if (is_null(static::$instance))
            // Appel du constructeur
            static::$instance = new DatabaseConnection();
        return static::$instance;
    }

}