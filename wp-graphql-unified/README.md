# WPGraphQL Unified

Plugin WordPress unique qui embarque WPGraphQL core et les extensions GraphQL du workspace dans `legacy/`.

## Compatibilite (versions recentes)

| Composant | Prise en charge |
|-----------|-----------------|
| WordPress | **6.5+** (teste avec **6.9.x**) |
| PHP | **8.1+** (8.3 / **8.4** recommandes sur l’hote) |
| React (front headless) | **18.3+** ou **19.x** avec Apollo Client, urql ou Relay |
| Node.js (Next.js, tooling) | **20 LTS** ou **22 LTS** (voir `package.json` du plugin pour `engines`) |

Ce depot ne shippe pas d’application React : l’API GraphQL reste standard ; les versions ci-dessus concernent l’environnement WordPress et les projets clients habituels.

## Modules inclus

| Module | Role |
|--------|------|
| Core WPGraphQL | Schema de base |
| WP GraphQL CPT | Filtres CPT / taxonomies vers GraphQL |
| Enable all post types | `show_in_graphql` sur les CPT publics |
| WPGraphQL Custom Post Type UI | Pont avec le plugin CPT UI |
| WPGraphQL Meta Query | Argument `meta_query` |
| WPGraphQL Tax Query | Argument `tax_query` |
| WP GraphQL Meta | Champs depuis `register_meta()` |
| Total Counts for WPGraphQL | Champ `total` sur `WPPageInfo` |
| WP GraphQL MB Relationships | Relations Meta Box (si MB Relationships actif) |
| SEO | Add WPGraphQL SEO si Yoast SEO actif, sinon champs meta legers |
| WPGraphQL for ACF | Si ACF actif |
| WPGraphQL Gutenberg | Blocs |
| WooGraphQL | Si WooCommerce actif |
| WPGraphQL JWT Authentication | Auth JWT |

Le snippet historique `wp-graphql-google-schema-master/Meta.php` n'est **pas** charge automatiquement (doublons avec la couche SEO).

## Activation selective (feature flags)

```php
define( 'WPGRAPHQL_UNIFIED_ENABLE_CORE', false );
define( 'WPGRAPHQL_UNIFIED_ENABLE_CPT', false );
define( 'WPGRAPHQL_UNIFIED_ENABLE_ENABLE_ALL', false );
define( 'WPGRAPHQL_UNIFIED_ENABLE_CPT_UI', false );
define( 'WPGRAPHQL_UNIFIED_ENABLE_META_QUERY', false );
define( 'WPGRAPHQL_UNIFIED_ENABLE_TAX_QUERY', false );
define( 'WPGRAPHQL_UNIFIED_ENABLE_META', false );
define( 'WPGRAPHQL_UNIFIED_ENABLE_TOTAL_COUNTS', false );
define( 'WPGRAPHQL_UNIFIED_ENABLE_MB_RELATIONSHIPS', false );
define( 'WPGRAPHQL_UNIFIED_ENABLE_SEO', false );
define( 'WPGRAPHQL_UNIFIED_ENABLE_ACF', false );
define( 'WPGRAPHQL_UNIFIED_ENABLE_GUTENBERG', false );
define( 'WPGRAPHQL_UNIFIED_ENABLE_WOO', false );
define( 'WPGRAPHQL_UNIFIED_ENABLE_JWT', false );
```

## Ordre de chargement

Voir `src/Plugin.php` : exposition CPT, filtres de requete, meta, totaux, MB, SEO, puis ACF, Gutenberg, Woo, JWT.

## Sources legacy

Toutes les extensions sont lues uniquement depuis `wp-graphql-unified/legacy/` (deploiement autonome, sans dossiers dupliques a la racine du depot).

## Diagnostics admin

Notices si source manquante ou prerequis WordPress absent (WooCommerce, ACF, CPT UI, MB Relationships, Yoast pour la branche SEO officielle).

**Réglages → WPGraphQL Unified** : activation des paquets (option `wpgraphql_unified_feature_flags`), tableau d’état, versions WordPress / PHP / plugin, auteur [Julien Vaissier](https://julienvaissier.fr/fr/). Lien **Réglages** dans la liste des extensions.

Hooks utiles : `wpgraphql_unified_booted`, filtre `wpgraphql_unified_legacy_path` (chemin absolu résolu pour un fichier sous `legacy/`).

### Menus GraphQL dans l’admin

Le menu principal **GraphQL** (éléphant : GraphiQL IDE, Réglages, …) est celui de **WPGraphQL**. Le bundle ajoute **GraphQL → Paquets unifiés** (identique à *Réglages → WPGraphQL Unified*).

Les entrées **Extensions** (catalogue d’extensions WPGraphQL) et l’écran admin **GraphQL Gutenberg** sont **masqués** : le schéma Gutenberg reste chargé si le paquet est activé, sans page de réglages dédiée dans le menu.

## Tests

```powershell
php .\tests\regression\verify-legacy-structure.php
php .\tests\regression\run-regression.php http://localhost/graphql
```
