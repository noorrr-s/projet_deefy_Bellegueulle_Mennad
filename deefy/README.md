# Mini-Projet Deefy (R3.01 - Développement Web S3)

**IUT Nancy-Charlemagne - BUT Informatique S3**  
*Binôme : Bellegueulle & Mennad*

---

## Tableau de bord des fonctionnalités

Conformément au sujet du projet, voici l'état d'avancement des fonctionnalités :

| N° | Fonctionnalité | Auteur | Statut |
|:---|:---|:---:|:---:|
| **1** | **Mes playlists** : Affiche la liste des playlists enregistrées en BD | Personne B | Réalisé (`DisplayAllPlaylistsAction`) |
| **2** | **Sélection de playlist** : Cliquable, affichage complet et devient courante en session | Personne B | Réalisé (`DisplayPlaylistAction`) |
| **3** | **Ajouter une piste** : Formulaire pour ajouter une piste à la playlist courante (session + BD) | Personne A | Réalisé (`AddTrackAction`) |
| **4** | **Créer une playlist vide** : Formulaire de création, insertion BD et mise en session | Personne B | Réalisé (`AddPlaylistAction`) |
| **5** | **Afficher la playlist courante** : Affiche la playlist stockée en session | Personne A | Réalisé (`DisplayCurrentPlaylistAction`) |
| **6** | *(Optionnel)* **S'inscrire** : Création d'un compte avec mot de passe haché | Binôme | Optionnel |
| **7** | *(Optionnel)* **S'authentifier** : Connexion utilisateur avec session | Binôme | Optionnel |

---

## Installation et configuration locale (XAMPP)

1. **Chemin du projet** :
   * Placé sous `C:\xampp\htdocs\Sae\projet_deefy_Bellegueulle_Mennad\deefy`
   * URL locale : [http://localhost/Sae/projet_deefy_Bellegueulle_Mennad/deefy/](http://localhost/Sae/projet_deefy_Bellegueulle_Mennad/deefy/)

2. **Base de données** :
   * Importer `sql/deefy.sql` dans MySQL (via phpMyAdmin ou console MySQL).
   * Copier `config/config.db.ini.dist` vers `config/config.db.ini` et renseigner vos identifiants MySQL.

---

## Identifiants de test (si authentification requise)

* **Email** : `test@univ-lorraine.fr`
* **Mot de passe** : `secret123`
* **Rôle** : `STANDARD`

---

## Déploiement sur le serveur webetu

1. Déposer les fichiers dans votre dossier public sur `webetu.iutnc.univ-lorraine.fr`.
2. Importer `sql/deefy.sql` sur votre base de données IUT.
3. Créer `config/config.db.ini` sur `webetu` avec les identifiants IUT.
