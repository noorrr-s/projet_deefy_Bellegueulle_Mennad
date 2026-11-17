<?php

namespace iutnc\deefy\action;

use iutnc\deefy\audio\lists\Playlist;
use iutnc\deefy\repository\DeefyRepository;
use iutnc\deefy\exception\DatabaseException;

class AddPlaylistAction extends Action {

    public function execute(): string {
        if ($this->http_method === 'GET') {
            return $this->renderForm();
        }

        if ($this->http_method === 'POST') {
            return $this->processForm();
        }

        return "<div class=\"error-message\">Méthode HTTP non supportée.</div>";
    }

    /**
     * Affiche le formulaire de création de playlist (GET)
     */
    private function renderForm(): string {
        $html = <<<HTML
        <div class="form-container">
            <h2>Créer une nouvelle playlist</h2>
            <form method="post" action="?action=creer-playlist">
                <div style="margin-bottom: 15px;">
                    <label for="nom" style="display: block; font-weight: bold; margin-bottom: 5px;">Nom de la playlist :</label>
                    <input type="text" id="nom" name="nom" required placeholder="Ex : Soirée détente, Rock 90s..." 
                           style="width: 100%; max-width: 400px; padding: 10px; border: 1px solid #cbd5e1; border-radius: 6px; box-sizing: border-box;">
                </div>
                <div>
                    <button type="submit" class="btn">Créer la playlist</button>
                    <a href="?action=mes-playlists" class="btn btn-secondary" style="margin-left: 10px;">Annuler</a>
                </div>
            </form>
        </div>
HTML;
        return $html;
    }

    /**
     * Traite la validation du formulaire (POST - Cours 12 & 13)
     */
    private function processForm(): string {
        // Filtrage et assainissement contre les attaques XSS (Cours 12)
        $nom = filter_var($_POST['nom'] ?? '', FILTER_SANITIZE_SPECIAL_CHARS);
        $nom = trim((string)$nom);

        if (empty($nom)) {
            return "<div class=\"error-message\">Le nom de la playlist ne peut pas être vide. <a href=\"?action=creer-playlist\">Réessayer</a></div>";
        }

        try {
            // Création de l'entité Playlist et enregistrement en base de données
            $playlist = new Playlist($nom);
            $repo = DeefyRepository::getInstance();
            $playlist = $repo->saveEmptyPlaylist($playlist);

            // La nouvelle playlist devient la playlist courante stockée en session (Fonctionnalité 4)
            $_SESSION['current_playlist'] = $playlist;

            $nomAffiche = htmlspecialchars($playlist->nom);
            $id = (int)$playlist->id;

            $html = <<<HTML
            <div class="success-message" style="background: #dcfce7; color: #166534; padding: 20px; border-radius: 8px; border: 1px solid #86efac;">
                <h3 style="margin-top: 0;">🎉 Playlist créée avec succès !</h3>
                <p>La playlist <strong>{$nomAffiche}</strong> a bien été enregistrée et définie comme votre playlist courante.</p>
                <div style="margin-top: 15px;">
                    <a href="?action=afficher-playlist&id={$id}" class="btn">Afficher cette playlist</a>
                    <a href="?action=ajouter-piste" class="btn" style="background: #059669; margin-left: 10px;">➕ Ajouter une piste</a>
                    <a href="?action=mes-playlists" class="btn btn-secondary" style="margin-left: 10px;">Toutes les playlists</a>
                </div>
            </div>
HTML;
            return $html;
        } catch (DatabaseException $e) {
            return "<div class=\"error-message\">Erreur lors de l'enregistrement de la playlist : " . htmlspecialchars($e->getMessage()) . "</div>";
        }
    }
}
