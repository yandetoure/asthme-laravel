# API Médicaments - Documentation

## Vue d'ensemble

L'API des médicaments permet de gérer les informations sur les médicaments utilisés dans le traitement de l'asthme. Elle fournit des endpoints CRUD complets ainsi que des fonctionnalités de recherche et de filtrage par catégorie.

## Endpoints

### 1. Liste des médicaments
**GET** `/api/medicaments`

Retourne la liste de tous les médicaments disponibles.

**Réponse :**
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "titre": "Ventoline (Salbutamol)",
            "description": "Bronchodilatateur à action rapide pour soulager les crises d'asthme",
            "image": "ventoline.jpg",
            "categorie": "Bronchodilatateur",
            "forme_pharmaceutique": "Aérosol doseur",
            "indications": "Traitement des crises d'asthme et prévention de l'asthme d'effort",
            "contre_indications": "Hypersensibilité au salbutamol ou à l'un des excipients",
            "effets_secondaires": "Tremblements, tachycardie, céphalées, nervosité",
            "posologie": "1-2 bouffées en cas de crise, maximum 8 bouffées par jour",
            "interactions": "Peut interagir avec les bêta-bloquants",
            "disponible": true,
            "created_at": "2025-08-31T16:06:00.000000Z",
            "updated_at": "2025-08-31T16:06:00.000000Z"
        }
    ]
}
```

### 2. Créer un médicament
**POST** `/api/medicaments`

Crée un nouveau médicament.

**Corps de la requête :**
```json
{
    "titre": "Nouveau Médicament",
    "description": "Description du médicament",
    "image": "image.jpg",
    "categorie": "Bronchodilatateur",
    "forme_pharmaceutique": "Aérosol doseur",
    "indications": "Indications d'utilisation",
    "contre_indications": "Contre-indications",
    "effets_secondaires": "Effets secondaires possibles",
    "posologie": "Posologie recommandée",
    "interactions": "Interactions médicamenteuses",
    "disponible": true
}
```

**Champs requis :**
- `titre` (string, max 255 caractères)
- `description` (string)
- `categorie` (string, max 255 caractères)

**Champs optionnels :**
- `image` (string)
- `forme_pharmaceutique` (string, max 255 caractères)
- `indications` (string)
- `contre_indications` (string)
- `effets_secondaires` (string)
- `posologie` (string)
- `interactions` (string)
- `disponible` (boolean, défaut: true)

### 3. Obtenir un médicament
**GET** `/api/medicaments/{id}`

Retourne les détails d'un médicament spécifique.

### 4. Mettre à jour un médicament
**PUT/PATCH** `/api/medicaments/{id}`

Met à jour un médicament existant.

### 5. Supprimer un médicament
**DELETE** `/api/medicaments/{id}`

Supprime un médicament.

### 6. Rechercher des médicaments
**GET** `/api/medicaments/search?q={query}`

Recherche des médicaments par titre, description ou catégorie.

**Paramètres :**
- `q` (string) : Terme de recherche

### 7. Filtrer par catégorie
**GET** `/api/medicaments/categorie/{category}`

Retourne les médicaments d'une catégorie spécifique.

## Catégories de médicaments

Les médicaments sont organisés en plusieurs catégories :

1. **Bronchodilatateur** - Médicaments à action rapide pour soulager les crises
2. **Traitement de fond** - Médicaments pour le contrôle à long terme
3. **Corticostéroïde** - Anti-inflammatoires inhalés
4. **Bronchodilatateur longue durée** - Médicaments à action prolongée
5. **Antileucotriène** - Médicaments préventifs

## Relations avec les traitements

Chaque médicament peut être associé à plusieurs traitements. La relation est gérée via la clé étrangère `medicament_id` dans la table `traitements`.

## Exemples d'utilisation

### Recherche de bronchodilatateurs
```bash
curl "http://localhost:8000/api/medicaments/categorie/Bronchodilatateur"
```

### Recherche par nom
```bash
curl "http://localhost:8000/api/medicaments/search?q=ventoline"
```

### Création d'un nouveau médicament
```bash
curl -X POST "http://localhost:8000/api/medicaments" \
  -H "Content-Type: application/json" \
  -d '{
    "titre": "Nouveau Bronchodilatateur",
    "description": "Description du médicament",
    "categorie": "Bronchodilatateur",
    "forme_pharmaceutique": "Aérosol doseur",
    "disponible": true
  }'
```

## Gestion des erreurs

L'API retourne des codes de statut HTTP appropriés :

- `200` - Succès
- `201` - Créé avec succès
- `400` - Requête invalide
- `404` - Ressource non trouvée
- `422` - Erreur de validation
- `500` - Erreur serveur

Les erreurs de validation incluent des détails sur les champs problématiques :

```json
{
    "message": "The given data was invalid.",
    "errors": {
        "titre": ["Le titre est requis."],
        "categorie": ["La catégorie est requise."]
    }
}
```
