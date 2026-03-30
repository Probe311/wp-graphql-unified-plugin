# WPGraphQL Unified

Plugin WordPress unique pour charger WPGraphQL core et les modules de compatibilite:

- WooCommerce
- ACF
- JWT Authentication
- Gutenberg
- Google Schema SEO fields

## Activation selective (feature flags)

Vous pouvez desactiver un module via constantes:

```php
define( 'WPGRAPHQL_UNIFIED_ENABLE_WOO', false );
define( 'WPGRAPHQL_UNIFIED_ENABLE_ACF', false );
define( 'WPGRAPHQL_UNIFIED_ENABLE_GUTENBERG', false );
define( 'WPGRAPHQL_UNIFIED_ENABLE_JWT', false );
define( 'WPGRAPHQL_UNIFIED_ENABLE_SEO', false );
```

## Sources legacy embarquees

Ce plugin est prevu pour fonctionner de maniere autonome avec:

- `wp-graphql-unified/legacy/wp-graphql.2.10.1/wp-graphql/wp-graphql.php`
- `wp-graphql-unified/legacy/wp-graphql-woocommerce-v0.19.0/wp-graphql-woocommerce.php`
- `wp-graphql-unified/legacy/wp-graphql-jwt-authentication-develop/wp-graphql-jwt-authentication-develop/wp-graphql-jwt-authentication.php`
- `wp-graphql-unified/legacy/wp-graphql-gutenberg-develop/wp-graphql-gutenberg-develop/plugin.php`
- `wp-graphql-unified/legacy/wpgraphql-acf-develop/wpgraphql-acf-develop/wpgraphql-acf.php`
- `wp-graphql-unified/legacy/wp-graphql-google-schema-master/wp-graphql-google-schema-master/Meta.php`

Le resolver garde un fallback dev vers le dossier frere du plugin:

1. `wp-graphql-unified/legacy/...` (bundle final recommande)
2. dossier frere du plugin (mode dev workspace)

## Diagnostics admin

Le plugin affiche des notices admin dediees si:

- le core WPGraphQL embarque est introuvable;
- un module embarque est introuvable;
- un prerequis plugin n'est pas actif (ex: WooCommerce, ACF).

## Tests de non-regression

Voir `tests/regression/README.md`.
