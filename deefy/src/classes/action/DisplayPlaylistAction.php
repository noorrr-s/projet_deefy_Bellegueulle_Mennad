<?php

namespace iutnc\deefy\action;

use iutnc\deefy\repository\DeefyRepository;
use iutnc\deefy\render\PlaylistRenderer;
use iutnc\deefy\render\Renderer;
use iutnc\deefy\exception\DatabaseException;

class DisplayPlaylistAction extends Action {

    public function execute(): string {
        $id = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);

        if ($id === false || $id === null) {
            return "<div class=\"error-message\">Identifiant de playlist invalide ou manquant.</div>";
        }

        try {
            $repo = DeefyRepository::getInstance();
            $playlist = $repo->findPlaylistById($id);

            // La playlist devient la playlist courante stockée en session (Fonctionnalité 2)
            $_SESSION['current_playlist'] = $playlist;

            $renderer = new PlaylistRenderer($playlist);
            $html = $renderer->render(Renderer::LONG);

            // Lien direct pour ajouter une piste (Fonctionnalité 3)
            $html .= "<div class=\"playlist-actions\" style=\"margin-top: 20px;\">";
            $html .= "<a href=\"?action=ajouter-piste\" class=\"btn\">Ajouter une piste à cette playlist</a> ";
            $html .= "<a href=\"?action=mes-playlists\" class=\"btn btn-secondary\">Retour à la liste</a>";
            $html .= "</div>";

            return $html;
        } catch (DatabaseException $e) {
            return "<div class=\"error-message\">" . htmlspecialchars($e->getMessage()) . "</div>";
        }
    }
}
