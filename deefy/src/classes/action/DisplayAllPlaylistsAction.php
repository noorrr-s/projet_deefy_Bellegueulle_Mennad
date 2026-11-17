<?php

namespace iutnc\deefy\action;

use iutnc\deefy\repository\DeefyRepository;

class DisplayAllPlaylistsAction extends Action {

    public function execute(): string {
        $repo = DeefyRepository::getInstance();
        $playlists = $repo->findAllPlaylists();

        $html = "<div class=\"playlists-container\">";
        $html .= "<h2>Mes Playlists</h2>";

        if (empty($playlists)) {
            $html .= "<p>Aucune playlist enregistrée pour le moment.</p>";
            $html .= "<p><a href=\"?action=creer-playlist\" class=\"btn\">Créer ma première playlist</a></p>";
        } else {
            $html .= "<ul class=\"playlists-list\">";
            foreach ($playlists as $pl) {
                $nom = htmlspecialchars($pl->nom);
                $id = (int)$pl->id;
                $html .= "<li><a href=\"?action=afficher-playlist&id={$id}\">🎵 {$nom}</a></li>";
            }
            $html .= "</ul>";
        }

        $html .= "</div>";
        return $html;
    }
}
