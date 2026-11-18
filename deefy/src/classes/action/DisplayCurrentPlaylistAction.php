<?php

namespace iutnc\deefy\action;

use iutnc\deefy\render\PlaylistRenderer;
use iutnc\deefy\render\Renderer;

class DisplayCurrentPlaylistAction extends Action {

    public function execute(): string {
        // Vérifie si une playlist courante valide est stockée en session
        if (!isset($_SESSION['current_playlist']) || !($_SESSION['current_playlist'] instanceof \iutnc\deefy\audio\lists\Playlist)) {
            return "<div class=\"info-box\"><p>Aucune playlist courante sélectionnée pour le moment.</p><p><a href=\"?action=mes-playlists\" class=\"btn\">Choisir une playlist</a> ou <a href=\"?action=creer-playlist\" class=\"btn btn-secondary\">En créer une</a></p></div>";
        }

        $playlist = $_SESSION['current_playlist'];

        // Affichage au format long
        $renderer = new PlaylistRenderer($playlist);
        $html = $renderer->render(Renderer::LONG);

        // Actions associées
        $html .= "<div class=\"playlist-actions\" style=\"margin-top: 20px;\">";
        $html .= "<a href=\"?action=ajouter-piste\" class=\"btn\">Ajouter une piste à cette playlist</a> ";
        $html .= "<a href=\"?action=mes-playlists\" class=\"btn btn-secondary\">Retour à la liste</a>";
        $html .= "</div>";

        return $html;
    }
}
