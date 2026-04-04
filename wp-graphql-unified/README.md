# WPGraphQL Unified

Plugin WordPress unique qui embarque WPGraphQL core et les extensions GraphQL du workspace dans `legacy/`.

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

1. `wp-graphql-unified/legacy/...`
2. Fallback : dossier parent du plugin (dev)

## Diagnostics admin

Notices si source manquante ou prerequis WordPress absent (WooCommerce, ACF, CPT UI, MB Relationships, Yoast pour la branche SEO officielle).

## Tests

```powershell
php .\tests\regression\verify-legacy-structure.php
php .\tests\regression\run-regression.php http://localhost/graphql
```
