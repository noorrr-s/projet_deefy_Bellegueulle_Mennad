<?php

// Démarrage systématique de la session (Cours 10)
session_start();

// Chargement automatique des classes via Composer (Cours 7)
require_once __DIR__ . '/vendor/autoload.php';

use deefy\dispatch\Router;
use deefy\repository\DeefyRepository;
use deefy\exception\RouterException;
use deefy\exception\DatabaseException;

// Initialisation de la configuration base de données si le fichier ini existe (Cours 13)
$configFile = __DIR__ . '/config/config.db.ini';
if (file_exists($configFile)) {
    try {
        DeefyRepository::setConfig($configFile);
    } catch (DatabaseException $e) {
        error_log($e->getMessage());
    }
}

// Configuration du Routeur (Cours 11)
$router = new Router();
$router->addRoute('default', \deefy\action\DefaultAction::class);
$router->addRoute('playlist-courante', \deefy\action\DisplayCurrentPlaylistAction::class);
$router->addRoute('ajouter-piste', \deefy\action\AddTrackAction::class);

try {
    $content = $router->run();
} catch (RouterException $e) {
    http_response_code(404);
    $content = "<article class='error'><strong>404 Non trouvé :</strong> " . htmlspecialchars($e->getMessage()) . "</article>";
} catch (\Exception $e) {
    http_response_code(500);
    $content = "<article class='error'><strong>Erreur :</strong> " . htmlspecialchars($e->getMessage()) . "</article>";
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Deefy - Gestionnaire de Playlists</title>
    <!-- Intégration de Pico.css -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.min.css">
    <style>
        body { padding: 20px; }
        header { margin-bottom: 2rem; }
        .error { background-color: #ffe3e3; color: #c92a2a; border-left: 5px solid #c92a2a; padding: 1rem; }
    </style>
</head>
<body>
<header class="container">
    <nav>
        <ul>
            <li><strong>Deefy Music</strong></li>
        </ul>
        <ul>
            <li><a href="?action=default">Accueil</a></li>
            <li><a href="?action=playlist-courante">Playlist courante</a></li>
            <li><a href="?action=ajouter-piste" role="button" class="outline">Ajouter une piste</a></li>
        </ul>
    </nav>
</header>

<main class="container">
    <?= $content ?>
</main>

<footer class="container" style="text-align: center; font-size: 0.85rem; color: #707a8a; margin-top: 3rem;">
    <p>&copy; <?= date('Y') ?> Deefy - IUT Nancy-Charlemagne - BUT Informatique S3</p>
</footer>
</body>
</html>