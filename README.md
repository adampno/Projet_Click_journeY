# Click-journeY – Agence de Voyages Web

Projet web réalisé dans le cadre du module Informatique 4 (préING2, 2024-2025).

## Description

Click-journeY est un site web conçu pour une agence de voyages proposant des séjours prédéfinis en plusieurs étapes. Bien que les circuits géographiques ne puissent pas être modifiés par les clients, ces derniers peuvent personnaliser chaque étape avec diverses options (hébergement, restauration, activités, transport, etc.).

Le site propose une expérience de voyage pseudo-sur-mesure.

## Fonctionnalités principales

- Inscription / Connexion
- Consultation de circuits
- Personnalisation d’options par étape
- Paiement en ligne
- Gestion de compte utilisateur
- Déconnexion
- Interface administrateur

## Technologies utilisées

- Frontend : HTML5, CSS, JavaScript
- Backend : PHP
- Base de données : MySQL

## Architecture du projet

Le fichier principal à exécuter est :

```
/all/index.php
```

### Principaux dossiers :

- controllers/ : logique serveur
- database/ : gestion des données
- scripts/ : fonctions JS
- style/ : feuilles de style CSS
- assets/ : images, médias
- Divers fichiers .php pour les vues principales (index, seconnecter, voyage, etc.)

## Lancer le projet en local

1. Copier le dossier all/ dans le localhost sur XAMPP, WAMP, MAMP…
2. Démarrer Apache et MySQL via le panneau de contrôle.
3. Naviguer vers : http://localhost/all/index.php

NB : Si erreur vérifier le port.

## Phases de développement

Le projet a été structuré en 4 phases évolutives :
1. Interface graphique – Création de pages statiques et charte graphique
2. Serveur & données – Gestion utilisateurs, stockage
3. Interaction dynamique – JavaScript côté client
4. Requêtes – Mise à jour dynamique sans rechargement

## Équipe de développement

- Hiba Mesbahi
- Adam Pineau
- Alicia Kellai