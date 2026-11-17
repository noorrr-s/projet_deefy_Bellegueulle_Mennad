<?php

namespace iutnc\deefy\audio\tracks;

use iutnc\deefy\exception\InvalidPropertyNameException;
use iutnc\deefy\exception\InvalidPropertyValueException;

class AudioTrack {
    protected ?int $id = null;
    protected string $titre;
    protected string $nom_fichier;
    protected int $duree = 0;
    protected string $genre = '';

    public function __construct(string $titre, string $nom_fichier) {
        $this->titre = $titre;
        $this->nom_fichier = $nom_fichier;
    }

    public function setId(int $id): void {
        $this->id = $id;
    }

    public function __get(string $name) {
        if (property_exists($this, $name)) {
            return $this->$name;
        }
        throw new InvalidPropertyNameException("invalid property : {$name}");
    }

    public function setDuree(int $duree): void {
        if ($duree < 0) {
            throw new InvalidPropertyValueException("invalid property value : {$duree} pour la durée");
        }
        $this->duree = $duree;
    }

    public function setGenre(string $genre): void {
        $this->genre = $genre;
    }
}