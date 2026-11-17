<?php

namespace deefy\action;

use deefy\audio\tracks\AudioTrack;
use deefy\audio\tracks\AlbumTrack;
use deefy\repository\DeefyRepository;

class AddTrackAction extends Action {

    public function execute(): string {
        // Vérifie qu'une playlist courante est bien active en session
        if (!isset($_SESSION['current_playlist'])) {
            return "<p>Erreur : Aucune playlist courante sélectionnée pour y ajouter une piste.</p>";
        }

        $html = "";

        // Si la requête est en POST, on traite le formulaire
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $titre = filter_var($_POST['titre'] ?? '', FILTER_SANITIZE_SPECIAL_CHARS);
            $filename = filter_var($_POST['filename'] ?? '', FILTER_SANITIZE_SPECIAL_CHARS);
            $genre = filter_var($_POST['genre'] ?? '', FILTER_SANITIZE_SPECIAL_CHARS);
            $duree = filter_var($_POST['duree'] ?? 0, FILTER_SANITIZE_NUMBER_INT);

            if (!empty($titre) && !empty($filename)) {
                // Instanciation de la piste
                $track = new AudioTrack($titre, $filename);
                $track->setGenre($genre);
                $track->setDuree((int)$duree);

                // Ajout dans la playlist en session
                $playlist = $_SESSION['current_playlist'];
                $playlist->addTrack($track);

                // Mise à jour de la session
                $_SESSION['current_playlist'] = $playlist;


                $html .= "<p style=\"color: green;\">Piste \"<strong>" . $titre . "</strong>\" ajoutée avec succès à la playlist courante !</p>";
                $html .= "<a href=\"index.php?action=display-current-playlist\">Voir la playlist courante</a>";
                return $html;
            } else {
                $html .= "<p style=\"color: red;\">Erreur : Le titre et le nom du fichier sont obligatoires.</p>";
            }
        }

        // Si la requête est en GET (ou en cas d'erreur de saisie), on affiche le formulaire HTML5
        $html .= "<h2>Ajouter une piste à la playlist : " . htmlspecialchars($_SESSION['current_playlist']->nom) . "</h2>";
        $html .= "<form action=\"index.php?action=add-track\" method=\"POST\">";
        $html .= "  <label>Titre de la piste :</label><br>";
        $html .= "  <input type=\"text\" name=\"titre\" required><br><br>";

        $html .= "  <label>Nom du fichier (ex: audio.mp3) :</label><br>";
        $html .= "  <input type=\"text\" name=\"filename\" required><br><br>";

        $html .= "  <label>Genre :</label><br>";
        $html .= "  <input type=\"text\" name=\"genre\"><br><br>";

        $html .= "  <label>Durée (en secondes) :</label><br>";
        $html .= "  <input type=\"number\" name=\"duree\" min=\"0\" value=\"0\"><br><br>";

        $html .= "  <button type=\"submit\">Ajouter la piste</button>";
        $html .= "</form>";

        return $html;
    }
}