<?php

namespace iutnc\deefy\repository;

use iutnc\deefy\audio\lists\Playlist;
use iutnc\deefy\audio\tracks\AudioTrack;
use iutnc\deefy\exception\DatabaseException;

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
     * Charge le fichier .ini de configuration (Cours 13)
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
     * Retourne toutes les playlists enregistrées en base de données (Fonctionnalité 1)
     * @return Playlist[]
     */
    public function findAllPlaylists(): array {
        $stmt = $this->pdo->query("SELECT id, nom FROM playlist ORDER BY nom ASC");
        $results = [];

        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $pl = new Playlist($row['nom']);
            $pl->setId((int)$row['id']);
            $results[] = $pl;
        }

        return $results;
    }

    /**
     * Recherche une playlist par son ID et charge toutes ses pistes associées (Fonctionnalité 2)
     */
    public function findPlaylistById(int $id): Playlist {
        $stmt = $this->pdo->prepare("SELECT id, nom FROM playlist WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$row) {
            throw new DatabaseException("Aucune playlist trouvée avec l'identifiant {$id}.");
        }

        $playlist = new Playlist($row['nom']);
        $playlist->setId((int)$row['id']);

        // Chargement des pistes associées
        $queryTracks = "SELECT t.id, t.titre, t.genre, t.duree, t.filename 
                        FROM track t 
                        INNER JOIN playlist2track p2t ON t.id = p2t.track_id 
                        WHERE p2t.playlist_id = :playlist_id 
                        ORDER BY p2t.no_piste_dans_liste ASC";
        $stmtTracks = $this->pdo->prepare($queryTracks);
        $stmtTracks->execute(['playlist_id' => $id]);

        while ($trackRow = $stmtTracks->fetch(\PDO::FETCH_ASSOC)) {
            $track = new AudioTrack($trackRow['titre'], $trackRow['filename'] ?? '');
            $track->setId((int)$trackRow['id']);
            $track->setDuree((int)$trackRow['duree']);
            if (!empty($trackRow['genre'])) {
                $track->setGenre($trackRow['genre']);
            }
            $playlist->addTrack($track);
        }

        return $playlist;
    }

    /**
     * Enregistre une nouvelle playlist vide en base de données (Cours 13 slide 12 / Fonctionnalité 4)
     */
    public function saveEmptyPlaylist(Playlist $pl): Playlist {
        $query = "INSERT INTO playlist (nom) VALUES (:nom)";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(['nom' => $pl->nom]);
        $pl->setId((int)$this->pdo->lastInsertId());
        return $pl;
    }

    /**
     * Enregistre une piste en base de données et l'associe à une playlist
     */
    public function addTrackToPlaylist(int $playlistId, AudioTrack $track): void {
        // 1. Insertion de la piste
        $queryTrack = "INSERT INTO track (titre, genre, duree, filename) VALUES (:titre, :genre, :duree, :filename)";
        $stmtTrack = $this->pdo->prepare($queryTrack);
        $stmtTrack->execute([
            'titre'    => $track->titre,
            'genre'    => $track->genre,
            'duree'    => $track->duree,
            'filename' => $track->nom_fichier
        ]);
        $trackId = (int)$this->pdo->lastInsertId();
        $track->setId($trackId);

        // 2. Détermination du prochain numéro de piste
        $stmtNum = $this->pdo->prepare("SELECT COALESCE(MAX(no_piste_dans_liste), 0) + 1 AS next_num FROM playlist2track WHERE playlist_id = :pl_id");
        $stmtNum->execute(['pl_id' => $playlistId]);
        $nextNum = (int)$stmtNum->fetchColumn();

        // 3. Liaison playlist <-> piste
        $queryLink = "INSERT INTO playlist2track (playlist_id, track_id, no_piste_dans_liste) VALUES (:pl_id, :tr_id, :no_piste)";
        $stmtLink = $this->pdo->prepare($queryLink);
        $stmtLink->execute([
            'pl_id'    => $playlistId,
            'tr_id'    => $trackId,
            'no_piste' => $nextNum
        ]);
    }

    public function getPDO(): \PDO {
        return $this->pdo;
    }
}
