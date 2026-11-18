<?php

namespace iutnc\deefy\action;

use iutnc\deefy\audio\tracks\AudioTrack;
use iutnc\deefy\repository\DeefyRepository;
use iutnc\deefy\exception\DatabaseException;

class AddTrackAction extends Action {

    public function execute(): string {
        // Vérifie qu'une playlist courante valide est bien active en session
        if (!isset($_SESSION['current_playlist']) || !($_SESSION['current_playlist'] instanceof \iutnc\deefy\audio\lists\Playlist)) {
            return "<div class=\"error-message\">Erreur : Aucune playlist courante sélectionnée pour y ajouter une piste. <a href=\"?action=mes-playlists\">Sélectionner une playlist</a></div>";
        }

        // Si la requête est en POST, on traite l'ajout
        if ($this->http_method === 'POST') {
            return $this->processForm();
        }

        // Sinon, on affiche le formulaire en GET
        return $this->renderForm();
    }

    private function renderForm(): string {
        $playlistNom = htmlspecialchars($_SESSION['current_playlist']->nom ?? 'Playlist courante');

        $html = <<<HTML
        <div class="form-container">
            <h2>Ajouter une piste à la playlist : <em>{$playlistNom}</em></h2>
            <form method="post" action="?action=ajouter-piste">
                <div style="margin-bottom: 15px;">
                    <label for="titre" style="display: block; font-weight: bold; margin-bottom: 5px;">Titre de la piste :</label>
                    <input type="text" id="titre" name="titre" required placeholder="Ex : Stairway to Heaven" 
                           style="width: 100%; max-width: 400px; padding: 10px; border: 1px solid #cbd5e1; border-radius: 6px; box-sizing: border-box;">
                </div>

                <div style="margin-bottom: 15px;">
                    <label for="filename" style="display: block; font-weight: bold; margin-bottom: 5px;">Nom du fichier audio (ou chemin) :</label>
                    <input type="text" id="filename" name="filename" required placeholder="Ex : audio/rock.mp3" 
                           style="width: 100%; max-width: 400px; padding: 10px; border: 1px solid #cbd5e1; border-radius: 6px; box-sizing: border-box;">
                </div>

                <div style="margin-bottom: 15px;">
                    <label for="genre" style="display: block; font-weight: bold; margin-bottom: 5px;">Genre musical :</label>
                    <input type="text" id="genre" name="genre" placeholder="Ex : Rock, Pop, Jazz..." 
                           style="width: 100%; max-width: 400px; padding: 10px; border: 1px solid #cbd5e1; border-radius: 6px; box-sizing: border-box;">
                </div>

                <div style="margin-bottom: 20px;">
                    <label for="duree" style="display: block; font-weight: bold; margin-bottom: 5px;">Durée (en secondes) :</label>
                    <input type="number" id="duree" name="duree" min="0" value="0" 
                           style="width: 100%; max-width: 200px; padding: 10px; border: 1px solid #cbd5e1; border-radius: 6px; box-sizing: border-box;">
                </div>

                <div>
                    <button type="submit" class="btn">Ajouter la piste</button>
                    <a href="?action=playlist-courante" class="btn btn-secondary" style="margin-left: 10px;">Annuler</a>
                </div>
            </form>
        </div>
HTML;
        return $html;
    }

    private function processForm(): string {
        // Filtrage et assainissement des entrées (Cours 12)
        $titre = filter_var($_POST['titre'] ?? '', FILTER_SANITIZE_SPECIAL_CHARS);
        $filename = filter_var($_POST['filename'] ?? '', FILTER_SANITIZE_SPECIAL_CHARS);
        $genre = filter_var($_POST['genre'] ?? '', FILTER_SANITIZE_SPECIAL_CHARS);
        $duree = filter_var($_POST['duree'] ?? 0, FILTER_SANITIZE_NUMBER_INT);

        $titre = trim((string)$titre);
        $filename = trim((string)$filename);
        $genre = trim((string)$genre);
        $duree = (int)$duree;

        if (empty($titre) || empty($filename)) {
            return "<div class=\"error-message\">Le titre et le fichier audio sont obligatoires. <a href=\"?action=ajouter-piste\">Réessayer</a></div>";
        }

        // Instanciation de la piste
        $track = new AudioTrack($titre, $filename);
        if (!empty($genre)) {
            $track->setGenre($genre);
        }
        $track->setDuree($duree);

        // Ajout à la playlist en session
        $playlist = $_SESSION['current_playlist'];
        $playlist->addTrack($track);
        $_SESSION['current_playlist'] = $playlist;

        // Persistance en base de données si la playlist possède un ID
        $savedInDb = false;
        if (!empty($playlist->id)) {
            try {
                $repo = DeefyRepository::getInstance();
                $repo->addTrackToPlaylist((int)$playlist->id, $track);
                $savedInDb = true;
            } catch (DatabaseException $e) {
                error_log("Erreur lors de l'enregistrement de la piste en BD : " . $e->getMessage());
            }
        }

        $titreAffiche = htmlspecialchars($titre);
        $statusDb = $savedInDb ? " et sauvegardée dans la base de données" : "";

        $html = <<<HTML
        <div class="success-message" style="background: #dcfce7; color: #166534; padding: 20px; border-radius: 8px; border: 1px solid #86efac;">
            <h3 style="margin-top: 0;">Piste ajoutée avec succès !</h3>
            <p>La piste <strong>{$titreAffiche}</strong> a bien été ajoutée à la playlist courante{$statusDb}.</p>
            <div style="margin-top: 15px;">
                <a href="?action=playlist-courante" class="btn">Voir la playlist courante</a>
                <a href="?action=ajouter-piste" class="btn" style="background: #059669; margin-left: 10px;">Ajouter une autre piste</a>
                <a href="?action=mes-playlists" class="btn btn-secondary" style="margin-left: 10px;">Mes playlists</a>
            </div>
        </div>
HTML;
        return $html;
    }
}
