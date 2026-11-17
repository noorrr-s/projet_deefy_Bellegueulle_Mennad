<?php

namespace deefy\audio\lists;

use deefy\exception\InvalidPropertyNameException;

class AudioList {
    protected ?int $id = null;
    protected string $nom;
    protected int $nb_pistes;
    protected int $duree_totale;
    protected array $pistes;

    public function __construct(string $nom, array $pistes = []) {
        $this->nom = $nom;
        $this->pistes = $pistes;
        $this->nb_pistes = count($pistes);
        $this->duree_totale = 0;
        foreach ($pistes as $piste) {
            $this->duree_totale += $piste->duree;
        }
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
}