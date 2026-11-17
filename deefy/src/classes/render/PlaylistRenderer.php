<?php

namespace iutnc\deefy\render;

use iutnc\deefy\audio\lists\Playlist;

class PlaylistRenderer implements Renderer {
    protected Playlist $playlist;

    public function __construct(Playlist $playlist) {
        $this->playlist = $playlist;
    }

    public function render(int $selector): string {
        $html = "<div class=\"playlist\">";
        $html .= "<h2>Playlist : " . htmlspecialchars($this->playlist->nom) . "</h2>";
        $html .= "<ul class=\"track-list\">";

        foreach ($this->playlist->pistes as $piste) {
            $html .= "<li>";
            $renderer = new AudioTrackRenderer($piste);
            $html .= $renderer->render($selector);
            $html .= "</li>";
        }

        $html .= "</ul>";
        $html .= "<p>Nombre de pistes : {$this->playlist->nb_pistes} — Durée totale : {$this->playlist->duree_totale} secondes</p>";
        $html .= "</div>";

        return $html;
    }
}