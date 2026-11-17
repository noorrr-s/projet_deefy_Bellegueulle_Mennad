<?php

namespace deefy\action;

class DefaultAction extends Action {

    public function execute(): string {
        $html = <<<HTML
        <div class="welcome-box">
            <h2>Bienvenue sur Deefy 🎵</h2>
            <p>Sélectionnez une option dans le menu ci-dessus pour naviguer dans l'application :</p>
            <ul>
                <li><a href="?action=mes-playlists">Consulter mes playlists</a></li>
                <li><a href="?action=creer-playlist">Créer une nouvelle playlist vide</a></li>
                <li><a href="?action=playlist-courante">Voir la playlist courante (en session)</a></li>
            </ul>
        </div>
HTML;
        return $html;
    }
}
