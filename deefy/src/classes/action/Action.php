<?php

namespace deefy\action;

abstract class Action {

    protected ?string $http_method = null;
    protected ?string $hostname = null;
    protected ?string $script_name = null;

    public function __construct() {
        $this->http_method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $this->hostname = $_SERVER['HTTP_HOST'] ?? '';
        $this->script_name = $_SERVER['SCRIPT_NAME'] ?? '';
    }

    /**
     * Exécute les traitements de l'action et retourne le code HTML à afficher.
     * @return string Code HTML résultant
     */
    abstract public function execute(): string;
}
