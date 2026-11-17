<?php

namespace deefy\repository;

use deefy\exception\DatabaseException;

class DeefyRepository {

    private \PDO $pdo;
    private static ?DeefyRepository $instance = null;
    private static array $config = [];

    /**
     * Constructeur privé (Pattern Singleton)
     */
    private function __construct(array $conf) {
        try {
            $dsn = "{$conf['driver']}:host={$conf['host']};dbname={$conf['database']};charset=utf8mb4";
            $this->pdo = new \PDO($dsn, $conf['username'], $conf['password'], [
                \PDO::ATTR_PERSISTENT => true,
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_EMULATE_PREPARES => false,
                \PDO::ATTR_STRINGIFY_FETCHES => false,
            ]);
        } catch (\PDOException $e) {
            throw new DatabaseException("Erreur de connexion à la base de données : " . $e->getMessage());
        }
    }

    /**
     * Retourne l'instance unique du Repository
     */
    public static function getInstance(): self {
        if (is_null(self::$instance)) {
            if (empty(self::$config)) {
                throw new DatabaseException("Configuration de la base de données non initialisée. Appelez setConfig() au préalable.");
            }
            self::$instance = new self(self::$config);
        }
        return self::$instance;
    }

    /**
     * Charge le fichier .ini de configuration
     */
    public static function setConfig(string $file): void {
        if (!file_exists($file)) {
            throw new DatabaseException("Fichier de configuration introuvable : {$file}");
        }

        $conf = parse_ini_file($file);
        if ($conf === false) {
            throw new DatabaseException("Impossible de lire le fichier de configuration : {$file}");
        }

        self::$config = [
            'driver'   => $conf['driver'] ?? 'mysql',
            'host'     => $conf['host'] ?? 'localhost',
            'database' => $conf['database'] ?? '',
            'username' => $conf['username'] ?? '',
            'password' => $conf['password'] ?? ''
        ];
    }

    /**
     * Accesseur direct au PDO si besoin spécifique
     */
    public function getPDO(): \PDO {
        return $this->pdo;
    }

    /*
     * Les méthodes de requêtes SQL (findAllPlaylists, findPlaylistById, saveEmptyPlaylist, saveTrack, etc.)
     * seront implémentées au fil du projet par le binôme.
     */
}
