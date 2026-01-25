# 🏥 Gestion Santé – Application médicale (VILT)

Application web de **gestion des soins de santé** développée avec le stack **VILT** :

- **V**ue 3
- **I**nertia.js
- **L**aravel 12
- **T**ailwindCSS

L’application permet la gestion complète des **rendez-vous médicaux**, **dossiers médicaux**, **facturation**, **notifications**, et **calendriers** pour les **patients** et les **médecins**.

---

## 🚀 Fonctionnalités principales

### 👤 Authentification & rôles
- Inscription / connexion
- Rôles : **Patient** et **Médecin**
- Dashboards séparés
- Accès sécurisé par middleware

### 📅 Rendez-vous
- Prise de rendez-vous par le patient
- Confirmation / annulation par le médecin
- Suppression de rendez-vous (avant confirmation)
- Motif, type (présentiel / en ligne), durée, notes
- **Calendrier interactif (FullCalendar)** :
  - Patient : visualisation + création depuis le calendrier
  - Médecin : gestion + drag & drop

### 🕒 Timeline (historique)
- Historique complet du rendez-vous :
  - Création
  - Confirmation
  - Annulation
  - Dossier médical
  - Facturation

### 🩺 Dossiers médicaux
- Création par le médecin
- Lecture patient / médecin
- Accès sécurisé
- Export **PDF**

### 💳 Facturation
- Facture générée après création du dossier médical
- Montant défini par le médecin
- Paiement simulé
- PDF de la facture

### 🔔 Notifications
- Notifications **in-app**
- Badge dans la navbar
- Toast automatique (sans refresh)
- Marquer comme lu sans rechargement

---

## 🧰 Technologies utilisées

- **Backend** : Laravel 12
- **Frontend** : Vue 3 + Inertia
- **Styling** : TailwindCSS
- **Calendrier** : FullCalendar
- **PDF** : DomPDF
- **Base de données** : MySQL
- **Build** : Vite

---

## 📦 Installation du projet (nouveau PC)

### 🔧 Prérequis
- PHP **8.2+**
- Composer
- Node.js **18+**
- MySQL

---

### 🟢 Étapes d’installation

```bash
# 1. Cloner le projet
git clone <url-du-repo>
cd gestion-sante

# 2. Installer les dépendances PHP
composer install

# 3. Installer les dépendances frontend
npm install

# 4. Créer le fichier .env
cp .env.example .env

# 5. Générer la clé Laravel
php artisan key:generate

```

### 🟢 Configuration de la base de données

Dans le fichier .env :

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=gestion-sante
DB_USERNAME=root
DB_PASSWORD=

Créer la base de données :

CREATE DATABASE gestion-sante;

## 🟢 Migrations & tables
```bash
php artisan migrate
php artisan db:seed
```

🟢 Lancer l’application
# Backend
```bash
php artisan serve
```

# Frontend (dans un autre terminal)
```bash
npm run dev
```

Ouvrir :
http://127.0.0.1:8000
