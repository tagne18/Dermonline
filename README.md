# DermOnline - Plateforme de Gestion Médicale

![DermOnline Logo](public/images/logo.png)

> Plateforme de gestion de rendez-vous et de suivi médical pour les dermatologues

## 📋 Table des matières

- [Présentation](#-présentation)
- [Fonctionnalités](#-fonctionnalités)
- [Technologies utilisées](#-technologies-utilisées)
- [Prérequis](#-prérequis)
- [Installation](#-installation)
- [Configuration](#-configuration)
- [Déploiement](#-déploiement)
- [Sécurité](#-sécurité)
- [Contribution](#-contribution)
- [Licence](#-licence)

## 🚀 Présentation

DermOnline est une application web complète dédiée à la gestion des consultations médicales pour les dermatologues. Cette plateforme permet de gérer les rendez-vous, les dossiers patients, les prescriptions et le suivi médical de manière efficace et sécurisée.

## ✨ Fonctionnalités

### Pour les médecins
- Gestion du planning des consultations
- Dossiers patients complets
- Prescriptions électroniques
- Historique des consultations
- Tableau de bord personnalisé
- Gestion des disponibilités

### Pour les patients
- Prise de rendez-vous en ligne
- Consultation du dossier médical
- Rappels de rendez-vous
- Téléconsultation (optionnelle)

### Administration
- Gestion des utilisateurs et rôles
- Tableaux de bord analytiques
- Gestion des paramètres système
- Rapports et statistiques

## 🛠 Technologies utilisées

- **Backend**: Laravel 10.x
- **Frontend**: 
  - Livewire 3.x
  - Tailwind CSS
  - Alpine.js
  - jQuery
- **Base de données**: MySQL 8.0+
- **Authentification**: Laravel Jetstream avec Fortify
- **Gestion des rôles**: Spatie Laravel Permission
- **Génération de PDF**: DomPDF
- **Traitement des images**: Intervention Image
- **Export Excel**: Maatwebsite Excel

## 📋 Prérequis

- PHP 8.2+
- Composer 2.5+
- Node.js 18+
- NPM 9+
- MySQL 8.0+
- Serveur web (Apache/Nginx)
- Extensions PHP requises :
  - BCMath PHP Extension
  - Ctype PHP Extension
  - cURL PHP Extension
  - DOM PHP Extension
  - Fileinfo PHP Extension
  - JSON PHP Extension
  - Mbstring PHP Extension
  - OpenSSL PHP Extension
  - PCRE PHP Extension
  - PDO PHP Extension
  - Tokenizer PHP Extension
  - XML PHP Extension

## 🚀 Installation

1. **Cloner le dépôt**
   ```bash
   git clone [URL_DU_DEPOT] dermonline
   cd dermonline
   ```

2. **Installer les dépendances PHP**
   ```bash
   composer install
   ```

3. **Installer les dépendances NPM**
   ```bash
   npm install
   npm run build
   ```

4. **Copier le fichier d'environnement**
   ```bash
   cp .env.example .env
   ```

5. **Générer la clé d'application**
   ```bash
   php artisan key:generate
   ```

6. **Configurer la base de données**
   ```bash
   php artisan migrate --seed
   ```

7. **Lancer le serveur de développement**
   ```bash
   php artisan serve
   ```

## ⚙️ Configuration

1. **Fichier .env**
   - Configurer les variables d'environnement dans le fichier `.env`
   - Spécifier les informations de connexion à la base de données
   - Configurer les paramètres d'email

2. **Permissions des dossiers**
   ```bash
   chmod -R 775 storage/
   chmod -R 775 bootstrap/cache/
   ```

3. **Lien de stockage**
   ```bash
   php artisan storage:link
   ```

## 🔒 Sécurité

- Authentification à deux facteurs
- Protection CSRF
- Validation des données côté serveur
- Protection contre les attaques XSS
- Hachage des mots de passe avec Bcrypt
- Gestion des sessions sécurisées

## 🤝 Contribution

1. Forkez le projet
2. Créez une branche pour votre fonctionnalité (`git checkout -b feature/AmazingFeature`)
3. Committez vos changements (`git commit -m 'Ajout d'une fonctionnalité incroyable'`)
4. Poussez vers la branche (`git push origin feature/AmazingFeature`)
5. Ouvrez une Pull Request

## 📄 Licence

Ce projet est sous licence MIT. Voir le fichier `LICENSE` pour plus de détails.

## ✨ Crédits

- [tagne loic]
- [Tous les contributeurs](https://github.com/votrecompte/dermonline/contributors)
