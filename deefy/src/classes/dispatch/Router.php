<?php

namespace deefy\dispatch;

use deefy\exception\RouterException;

class Router {

    protected ?string $action = null;
    protected array $actions = [];

    public function __construct() {
        $this->action = $_GET['action'] ?? 'default';
    }

    /**
     * Enregistre un couple (nom d'action, classe Action)
     */
    public function addRoute(string $action, string $action_class): self {
        $this->actions[$action] = $action_class;
        return $this;
    }

    /**
     * Instancie l'action demandée et exécute son traitement
     */
    public function run(): string {
        if (isset($this->actions[$this->action])) {
            $actionClass = $this->actions[$this->action];
            $actionInstance = new $actionClass();
            return $actionInstance->execute();
        }

        throw new RouterException("L'action demandée '{$this->action}' n'existe pas.");
    }
}
