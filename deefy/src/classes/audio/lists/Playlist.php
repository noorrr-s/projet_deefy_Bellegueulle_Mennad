<?php

namespace iutnc\deefy\audio\lists;

use iutnc\deefy\audio\tracks\AudioTrack;

class Playlist extends AudioList {

    public function __construct(string $nom, array $pistes = []) {
        parent::__construct($nom, $pistes);
    }

    public function addTrack(AudioTrack $piste): void {
        $this->pistes[] = $piste;
        $this->nb_pistes++;
        $this->duree_totale += $piste->duree;
    }

    public function removeTrack(int $index): void {
        if (isset($this->pistes[$index])) {
            $this->duree_totale -= $this->pistes[$index]->duree;
            unset($this->pistes[$index]);
            $this->pistes = array_values($this->pistes);
            $this->nb_pistes--;
        }
    }

    public function addTracks(array $pistes): void {
        foreach ($pistes as $piste) {
            $existe = false;
            foreach ($this->pistes as $p) {
                if ($p->nom_fichier === $piste->nom_fichier) {
                    $existe = true;
                    break;
                }
            }
            if (!$existe) {
                $this->addTrack($piste);
            }
        }
    }
}