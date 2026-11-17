<?php

namespace iutnc\deefy\render;

use iutnc\deefy\audio\lists\AudioList;
use iutnc\deefy\audio\tracks\AlbumTrack;
use iutnc\deefy\audio\tracks\PodcastTrack;

class AudioListRenderer implements Renderer {

    protected AudioList $audioList;

    public function __construct(AudioList $audioList) {
        $this->audioList = $audioList;
    }

    public function render(int $selector): string {
        $html = "<div class=\"audio-list\">";
        $html .= "<h2>" . htmlspecialchars($this->audioList->nom) . "</h2>";
        $html .= "<ul class=\"track-list\">";

        foreach ($this->audioList->pistes as $piste) {
            $html .= "<li>";
            $renderer = new AudioTrackRenderer($piste);
            $html .= $renderer->render(Renderer::COMPACT);
            $html .= "</li>";
        }

        $html .= "</ul>";
        $html .= "<p>Nombre de pistes : {$this->audioList->nb_pistes} — Durée totale : {$this->audioList->duree_totale} secondes</p>";
        $html .= "</div>";

        return $html;
    }
}