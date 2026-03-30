# WPGraphQL Unified Plugin

Description courte: plugin WordPress unifie pour exposer un schema GraphQL complet (core + Woo + ACF + JWT + Gutenberg + SEO) via une seule extension.

Tags: wordpress, graphql, wpgraphql, woocommerce, acf, gutenberg, jwt, headless, nextjs, react

Plugin WordPress unifié qui regroupe WPGraphQL core et les extensions principales dans une seule extension autonome.

## Objectif

Passer d'un stack de plusieurs extensions WPGraphQL à un plugin unique :

- WPGraphQL (core)
- WPGraphQL WooCommerce
- WPGraphQL for ACF
- WPGraphQL JWT Authentication
- WPGraphQL Gutenberg
- WP GraphQL Google Schema

Ce dépôt contient le plugin final dans `wp-graphql-unified/`.

## Contenu du dépôt

- `wp-graphql-unified/`
  - `wp-graphql-unified.php` : bootstrap du plugin
  - `src/` : orchestration modulaire, guards de schéma, feature flags, diagnostics
  - `legacy/` : sources embarquées des modules/core
  - `tests/regression/` : scripts de vérification structure + checks GraphQL

## Architecture

Le plugin charge les modules dans cet ordre pour maximiser la compatibilité :

1. Core WPGraphQL
2. ACF
3. Gutenberg
4. WooCommerce
5. JWT Auth
6. SEO fields

Le chargement passe par un orchestrateur central et des modules isolés :

- `src/Plugin.php`
- `src/Modules/*`
- `src/Support/SchemaRegistryGuards.php`

## Compatibilité

Le plugin est conçu en mode compatibilité :

- conservation des noms historiques de types/champs/mutations
- guards anti-collision pour éviter les doubles enregistrements
- diagnostics admin en cas de prérequis manquant

### Compatibilité React / Next.js

Compatible avec les clients GraphQL standards (Apollo, urql, Relay, fetch).  
La compatibilité réelle dépend de la parité de schéma avec les requêtes du front : utiliser les tests de non-régression + introspection.

## Installation

### Option A - Depuis ce dépôt (dev)

1. Copier le dossier `wp-graphql-unified` dans `wp-content/plugins/`
2. Activer le plugin dans WordPress
3. Vérifier l'endpoint GraphQL `/graphql`

### Option B - Package ZIP

Compresser uniquement le dossier `wp-graphql-unified` et installer le ZIP via l'admin WordPress.

## Pré-requis

- WordPress compatible avec WPGraphQL
- PHP version compatible avec les sources embarquées
- Plugins métiers selon besoin :
  - WooCommerce (pour le module Woo)
  - ACF (pour le module ACF)

Le plugin reste actif même si certains prérequis ne sont pas présents, et affiche des diagnostics en admin.

## Feature Flags

Tu peux désactiver sélectivement des modules via constantes :

```php
define( 'WPGRAPHQL_UNIFIED_ENABLE_WOO', false );
define( 'WPGRAPHQL_UNIFIED_ENABLE_ACF', false );
define( 'WPGRAPHQL_UNIFIED_ENABLE_GUTENBERG', false );
define( 'WPGRAPHQL_UNIFIED_ENABLE_JWT', false );
define( 'WPGRAPHQL_UNIFIED_ENABLE_SEO', false );
```

## Tests et qualité

### Vérification structure legacy

```powershell
php .\wp-graphql-unified\tests\regression\verify-legacy-structure.php
```

### Vérification GraphQL (endpoint actif requis)

```powershell
php .\wp-graphql-unified\tests\regression\run-regression.php http://localhost/graphql
```

## Sécurité et durcissement

- guards de schéma (types/champs/mutations/connections)
- checks de prérequis avant chargement de module
- diagnostics admin échappés/sanitizés
- fallback de résolution des chemins legacy

## Déploiement recommandé

1. Vérifier `verify-legacy-structure.php`
2. Vérifier les requêtes critiques de ton front (Next.js/React)
3. Générer le ZIP de `wp-graphql-unified/`
4. Déployer en staging, puis production

## Limitations connues

- Les tests runtime GraphQL nécessitent un WordPress actif avec endpoint accessible.
- Selon les projets, certaines personnalisations tierces peuvent demander un ajustement fin (hooks custom, champs dynamiques ACF, flux Woo custom).

## Licence

Ce dépôt agrège des composants open source avec leurs licences respectives dans `legacy/`. Vérifier chaque composant avant redistribution publique.
