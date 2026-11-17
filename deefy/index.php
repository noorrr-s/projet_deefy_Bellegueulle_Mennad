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
        // En cas d'erreur sur le fichier ini
        error_log($e->getMessage());
    }
}

// Configuration du Routeur (Cours 11)
$router = new Router();
$router->addRoute('default', \deefy\action\DefaultAction::class);

// Les routes des fonctionnalités seront ajoutées ici au fur et à mesure :
// $router->addRoute('mes-playlists', \deefy\action\DisplayAllPlaylistsAction::class);
// $router->addRoute('afficher-playlist', \deefy\action\DisplayPlaylistAction::class);
// $router->addRoute('ajouter-piste', \deefy\action\AddTrackAction::class);
// $router->addRoute('creer-playlist', \deefy\action\AddPlaylistAction::class);
// $router->addRoute('playlist-courante', \deefy\action\DisplayCurrentPlaylistAction::class);

try {
    $content = $router->run();
} catch (RouterException $e) {
    http_response_code(404);
    $content = "<div class='error-message'><strong>404 Non trouvé :</strong> " . htmlspecialchars($e->getMessage()) . "</div>";
} catch (\Exception $e) {
    http_response_code(500);
    $content = "<div class='error-message'><strong>Erreur :</strong> " . htmlspecialchars($e->getMessage()) . "</div>";
}

// Structure HTML5 valide (W3C) avec mise en page de base
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Deefy - Gestionnaire de Playlists</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif; margin: 0; padding: 20px; background: #f8fafc; color: #1e293b; }
        header { background: #0f172a; color: white; padding: 15px 25px; border-radius: 8px; margin-bottom: 20px; }
        header h1 { margin: 0 0 10px 0; font-size: 1.6rem; }
        nav ul { list-style: none; margin: 0; padding: 0; display: flex; gap: 15px; flex-wrap: wrap; }
        nav a { color: #38bdf8; text-decoration: none; font-weight: 500; }
        nav a:hover { text-decoration: underline; }
        main { background: white; padding: 25px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
        .error-message { background: #fee2e2; color: #991b1b; padding: 15px; border-radius: 6px; border: 1px solid #f87171; }
        footer { margin-top: 30px; text-align: center; font-size: 0.85rem; color: #64748b; }
    </style>
</head>
<body>
    <header>
        <h1>Deefy Music</h1>
        <nav>
            <ul>
                <li><a href="?action=default">Accueil</a></li>
                <li><a href="?action=mes-playlists">Mes Playlists</a></li>
                <li><a href="?action=creer-playlist">Créer une playlist</a></li>
                <li><a href="?action=playlist-courante">Playlist courante</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <?= $content ?>
    </main>

    <footer>
        <p>&copy; <?= date('Y') ?> Deefy - IUT Nancy-Charlemagne - BUT Informatique S3</p>
    </footer>
</body>
</html>
