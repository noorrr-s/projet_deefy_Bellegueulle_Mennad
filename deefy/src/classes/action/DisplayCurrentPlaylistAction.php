<?php

namespace deefy\action;

use deefy\render\PlaylistRenderer;
use deefy\render\Renderer;

class DisplayCurrentPlaylistAction extends Action {
    public function execute(): string {
        // Vérifie si une playlist courante est stockée en session
        if (!isset($_SESSION['current_playlist'])) {
            return "<p>Aucune playlist courante sélectionnée.</p>";
        }

        $playlist = $_SESSION['current_playlist'];

        //affichage format long
        $renderer = new PlaylistRenderer($playlist);
        return $renderer->render(Renderer::LONG);
    }
}