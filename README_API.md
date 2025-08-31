# API Documentation - Application Asthme

## Vue d'ensemble

Cette API Laravel fournit les endpoints nécessaires pour l'application Ionic de gestion de l'asthme.

## Base URL

```
http://localhost:8000/api
```

## Endpoints

### Patients

#### GET /api/patients
Récupère tous les patients avec leurs crises et traitements.

**Réponse :**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "nom": "Dupont",
      "prenom": "Jean",
      "date_naissance": "1985-03-15",
      "email": "jean.dupont@email.com",
      "telephone": "0123456789",
      "severite_asthme": "modere",
      "crises": [],
      "traitements": []
    }
  ]
}
```

#### POST /api/patients
Crée un nouveau patient.

**Corps de la requête :**
```json
{
  "nom": "Dupont",
  "prenom": "Jean",
  "date_naissance": "1985-03-15",
  "email": "jean.dupont@email.com",
  "telephone": "0123456789",
  "severite_asthme": "modere",
  "antecedents": "Antécédents médicaux...",
  "allergies": "Allergies connues...",
  "medecin_traitant": "Dr. Martin",
  "traitements_actuels": "Traitements en cours...",
  "notes_medicales": "Notes importantes..."
}
```

#### GET /api/patients/{id}
Récupère un patient spécifique.

#### PUT /api/patients/{id}
Met à jour un patient.

#### DELETE /api/patients/{id}
Supprime un patient.

### Crises

#### GET /api/crises
Récupère toutes les crises.

#### POST /api/crises
Crée une nouvelle crise.

**Corps de la requête :**
```json
{
  "patient_id": 1,
  "debut_crise": "2025-08-31 10:00:00",
  "intensite": "modere",
  "symptomes": "Difficultés respiratoires, toux",
  "declencheurs": "Poussière, stress",
  "traitements_utilises": "Ventoline 2 bouffées",
  "hospitalisation": false,
  "notes": "Crise maîtrisée rapidement"
}
```

#### GET /api/patients/{patient}/crises
Récupère les crises d'un patient spécifique.

### Traitements

#### GET /api/traitements
Récupère tous les traitements.

#### POST /api/traitements
Crée un nouveau traitement.

**Corps de la requête :**
```json
{
  "patient_id": 1,
  "nom_medicament": "Ventoline",
  "description": "Bronchodilatateur à action rapide",
  "dosage": "2 bouffées",
  "frequence": "Au besoin",
  "type": "rescue",
  "date_debut": "2025-01-01",
  "instructions": "Utiliser en cas de crise"
}
```

#### GET /api/patients/{patient}/traitements
Récupère les traitements d'un patient spécifique.

### Conseils

#### GET /api/conseils
Récupère tous les conseils.

**Réponse :**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "titre": "Évitez les déclencheurs courants",
      "contenu": "Identifiez et évitez les facteurs qui déclenchent vos crises d'asthme...",
      "categorie": "prevention",
      "niveau_severite": "tous",
      "ordre_affichage": 1
    }
  ]
}
```

#### GET /api/conseils/categorie/{categorie}
Récupère les conseils par catégorie (prevention, gestion_crise, lifestyle, medicaments, urgence).

#### GET /api/conseils/severite/{severite}
Récupère les conseils par niveau de sévérité (tous, leger, modere, severe).

## Codes de statut HTTP

- `200` : Succès
- `201` : Créé avec succès
- `404` : Ressource non trouvée
- `422` : Erreur de validation
- `500` : Erreur serveur

## Structure de réponse

Toutes les réponses suivent cette structure :

```json
{
  "success": true|false,
  "message": "Message descriptif",
  "data": {...},
  "errors": {...} // En cas d'erreur de validation
}
```

## Installation et démarrage

1. Installer les dépendances :
```bash
composer install
```

2. Configurer la base de données dans `.env`

3. Exécuter les migrations :
```bash
php artisan migrate
```

4. Ajouter les données de test :
```bash
php artisan db:seed --class=ConseilsSeeder
```

5. Démarrer le serveur :
```bash
php artisan serve
```

Le serveur sera accessible sur `http://localhost:8000`
