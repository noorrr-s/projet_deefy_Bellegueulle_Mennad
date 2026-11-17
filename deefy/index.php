<?php

// Démarrage systématique de la session (Cours 10)
session_start();

// Chargement automatique des classes via Composer (Cours 7)
require_once __DIR__ . '/vendor/autoload.php';

use iutnc\deefy\dispatch\Router;
use iutnc\deefy\repository\DeefyRepository;
use iutnc\deefy\exception\RouterException;
use iutnc\deefy\exception\DatabaseException;

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
$router->addRoute('default', \iutnc\deefy\action\DefaultAction::class);
$router->addRoute('mes-playlists', \iutnc\deefy\action\DisplayAllPlaylistsAction::class);
$router->addRoute('afficher-playlist', \iutnc\deefy\action\DisplayPlaylistAction::class);
$router->addRoute('playlist-courante', \iutnc\deefy\action\DisplayCurrentPlaylistAction::class)
$router->addRoute('ajouter-piste', \iutnc\deefy\action\AddTrackAction::class);
$router->addRoute('creer-playlist', \iutnc\deefy\action\AddPlaylistAction::class);

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
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif; margin: 0; padding: 20px; background: #f8fafc; color: #1e293b; }
        header { background: #0f172a; color: white; padding: 15px 25px; border-radius: 8px; margin-bottom: 20px; }
        header h1 { margin: 0 0 10px 0; font-size: 1.6rem; }
        nav ul { list-style: none; margin: 0; padding: 0; display: flex; gap: 15px; flex-wrap: wrap; }
        nav a { color: #38bdf8; text-decoration: none; font-weight: 500; }
        nav a:hover { text-decoration: underline; }
        main { background: white; padding: 25px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
        .error-message { background: #fee2e2; color: #991b1b; padding: 15px; border-radius: 6px; border: 1px solid #f87171; }
        .btn { display: inline-block; background: #2563eb; color: white; padding: 8px 16px; border-radius: 6px; text-decoration: none; font-weight: 500; }
        .btn:hover { background: #1d4ed8; }
        .btn-secondary { background: #64748b; }
        .btn-secondary:hover { background: #475569; }
        .playlists-list { list-style: none; padding: 0; }
        .playlists-list li { margin: 10px 0; padding: 12px; background: #f1f5f9; border-radius: 6px; }
        .playlists-list a { text-decoration: none; color: #0f172a; font-weight: 600; font-size: 1.1rem; }
        .playlists-list a:hover { color: #2563eb; }
        .track-list { list-style: none; padding: 0; }
        .track-list li { padding: 10px; margin-bottom: 8px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; }
        footer { margin-top: 30px; text-align: center; font-size: 0.85rem; color: #64748b; }
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