# Mini-Projet Deefy (R3.01 - Développement Web S3)

**IUT Nancy-Charlemagne - BUT Informatique S3**  
*Binôme : [Étudiant 1] & [Étudiant 2]*

---

## 📋 Tableau de bord des fonctionnalités

Conformément au sujet du projet, voici le suivi de réalisation :

| N° | Fonctionnalité | Rôle principal | Statut |
|:---|:---|:---:|:---:|
| **1** | **Mes playlists** : Affiche la liste des playlists enregistrées en BD | Personne B | ⏳ En attente |
| **2** | **Sélection de playlist** : Cliquable, affichage complet et devient courante en session | Personne B / A | ⏳ En attente |
| **3** | **Ajouter une piste** : Formulaire pour ajouter une piste à la playlist courante | Personne A | ⏳ En attente |
| **4** | **Créer une playlist vide** : Formulaire de création, insertion BD et mise en session | Personne B | ⏳ En attente |
| **5** | **Afficher la playlist courante** : Affiche la playlist stockée en session | Personne A | ⏳ En attente |
| **6** | *(Optionnel)* **S'inscrire** : Création d'un compte avec mot de passe haché | Binôme | ⏳ Optionnel |
| **7** | *(Optionnel)* **S'authentifier** : Connexion utilisateur avec session | Binôme | ⏳ Optionnel |

---

## 🛠️ Installation et configuration locale

1. **Génération de l'autoloader Composer** :
   ```bash
   composer dump-autoload
   ```
2. **Initialisation de la base de données** :
   * Exécuter le script SQL dans votre serveur MySQL / MariaDB :
     ```bash
     mysql -u root -p < sql/deefy.sql
     ```
3. **Configuration de la connexion BD** :
   * Copier le modèle `config/config.db.ini.dist` vers `config/config.db.ini` :
     ```bash
     cp config/config.db.ini.dist config/config.db.ini
     ```
   * Renseigner vos identifiants réels (ce fichier est ignoré par Git).

4. **Lancement du serveur de développement local** :
   ```bash
   php -S localhost:8000
   ```
   Rendez-vous sur [http://localhost:8000](http://localhost:8000).

---

## 🔑 Identifiants de test (si authentification requise)

* **Email** : `test@univ-lorraine.fr`
* **Mot de passe** : `secret123`
* **Rôle** : `STANDARD`

---

## 🚀 Déploiement sur le serveur `webetu`

1. Déposer les fichiers dans votre dossier public sur `webetu.iutnc.univ-lorraine.fr`.
2. Importer `sql/deefy.sql` sur la base de données de l'IUT (via phpMyAdmin ou terminal).
3. Créer le fichier `config/config.db.ini` sur `webetu` avec vos identifiants MySQL universitaires.
4. S'assurer que le dossier `vendor/` est généré ou transféré avec les droits de lecture adéquats.
