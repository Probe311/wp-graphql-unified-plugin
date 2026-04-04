# WPGraphQL Unified Plugin

Plugin WordPress unifié qui regroupe WPGraphQL core et les extensions principales dans une seule extension autonome.

## Objectif

Passer d'un stack de plusieurs extensions WPGraphQL à un plugin unique. Le dossier `wp-graphql-unified/legacy/` embarque notamment :

- WPGraphQL (core), WooCommerce, ACF, JWT, Gutenberg
- Total Counts, CPT, Enable all post types, CPT UI bridge
- Meta, Meta Query, Tax Query, MB Relationships, Add WPGraphQL SEO (Yoast)

Le snippet `google-schema` historique n'est pas charge pour eviter les doublons SEO.

Ce dépôt se limite au dossier `wp-graphql-unified/` : les sources d’extensions ne sont pas dupliquées à la racine (tout est dans `legacy/`).

## Contenu du dépôt

- `wp-graphql-unified/`
  - `wp-graphql-unified.php` : bootstrap du plugin
  - `src/` : orchestration modulaire, guards de schéma, feature flags, diagnostics
  - `legacy/` : sources embarquées des modules/core
  - `tests/regression/` : scripts de vérification structure + checks GraphQL

## Architecture

Le plugin charge les modules dans cet ordre (voir `wp-graphql-unified/src/Plugin.php`) :

1. Core WPGraphQL
2. CPT, Enable all post types, CPT UI (selon flags)
3. Meta Query, Tax Query, WP GraphQL Meta, Total Counts
4. MB Relationships (si Meta Box Relationships actif)
5. SEO (Yoast GraphQL ou fallback meta)
6. ACF, Gutenberg, WooCommerce, JWT

Fichiers cles : `src/Plugin.php`, `src/Modules/*`, `src/Support/SchemaRegistryGuards.php`, `src/Modules/SeoRouterModule.php`.

## Matrice compatibilite (prerequis WordPress)

| Extension embarquee | Prerequis optionnel |
|---------------------|---------------------|
| WooGraphQL | WooCommerce |
| WPGraphQL for ACF | Advanced Custom Fields |
| CPT UI bridge | Custom Post Type UI |
| MB Relationships | MB Relationships (Meta Box) |
| Add WPGraphQL SEO | Yoast SEO (`YoastSEO()`) |
| Fallback SEO | aucun (meta `_yoast_*` si presentes en base) |

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
define( 'WPGRAPHQL_UNIFIED_ENABLE_CPT', false );
define( 'WPGRAPHQL_UNIFIED_ENABLE_ENABLE_ALL', false );
define( 'WPGRAPHQL_UNIFIED_ENABLE_CPT_UI', false );
define( 'WPGRAPHQL_UNIFIED_ENABLE_META_QUERY', false );
define( 'WPGRAPHQL_UNIFIED_ENABLE_TAX_QUERY', false );
define( 'WPGRAPHQL_UNIFIED_ENABLE_META', false );
define( 'WPGRAPHQL_UNIFIED_ENABLE_TOTAL_COUNTS', false );
define( 'WPGRAPHQL_UNIFIED_ENABLE_MB_RELATIONSHIPS', false );
define( 'WPGRAPHQL_UNIFIED_ENABLE_SEO', false );
define( 'WPGRAPHQL_UNIFIED_ENABLE_WOO', false );
define( 'WPGRAPHQL_UNIFIED_ENABLE_ACF', false );
define( 'WPGRAPHQL_UNIFIED_ENABLE_GUTENBERG', false );
define( 'WPGRAPHQL_UNIFIED_ENABLE_JWT', false );
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
