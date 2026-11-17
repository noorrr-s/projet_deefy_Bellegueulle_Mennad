<?php

namespace iutnc\deefy\render;

use iutnc\deefy\audio\tracks\AudioTrack;
use iutnc\deefy\audio\tracks\AlbumTrack;

class AudioTrackRenderer implements Renderer {
    protected AudioTrack $track;

    public function __construct(AudioTrack $track) {
        $this->track = $track;
    }

    public function render(int $selector): string {
        $html = "<div class=\"track\">";
        $html .= "<p><strong>Titre :</strong> " . htmlspecialchars($this->track->titre) . "</p>";
        $html .= "<audio controls src=\"" . htmlspecialchars($this->track->nom_fichier) . "\"></audio>";

        if ($selector === self::LONG) {
            $html .= "<p><strong>Genre :</strong> " . htmlspecialchars($this->track->genre) . "</p>";
            $html .= "<p><strong>Durée :</strong> " . $this->track->duree . " secondes</p>";
            if ($this->track instanceof AlbumTrack) {
                $html .= "<p><strong>Artiste :</strong> " . htmlspecialchars($this->track->artiste) . "</p>";
                $html .= "<p><strong>Album :</strong> " . htmlspecialchars($this->track->album) . "</p>";
            }
        }
        $html .= "</div>";
        return $html;
    }
}