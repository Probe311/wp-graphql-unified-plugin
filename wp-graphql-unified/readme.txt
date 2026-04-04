=== WPGraphQL Unified ===
Contributors: probe311
Tags: wordpress, graphql, wpgraphql, woocommerce, acf, gutenberg, jwt, headless, nextjs, react, meta-query, tax-query, cpt, yoast
Requires at least: 6.0
Tested up to: 6.8
Requires PHP: 7.4
Stable tag: 0.2.0
License: GPL-2.0-or-later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Plugin WordPress unifie qui embarque WPGraphQL core et les extensions du depot (CPT, meta/tax query, meta register_meta, total counts, MB Relationships, Yoast SEO GraphQL, Woo, ACF, JWT, Gutenberg, SEO fallback) dans une seule extension autonome.

== Description ==

WPGraphQL Unified regroupe les composants WPGraphQL essentiels dans un plugin unique pour simplifier l'installation, la maintenance et le deploiement headless.

Objectifs principaux:

* Unifier WPGraphQL core et modules autour d'une seule extension.
* Garder une compatibilite maximale du schema GraphQL existant.
* Renforcer la robustesse avec un bootstrap modulaire, des guards anti-collision et des tests de non-regression.

Modules inclus:

* WPGraphQL (core)
* WP GraphQL CPT, Enable all post types, WPGraphQL Custom Post Type UI
* WPGraphQL Meta Query, WPGraphQL Tax Query, WP GraphQL Meta
* Total Counts for WPGraphQL
* WP GraphQL MB Relationships (si MB Relationships actif)
* Add WPGraphQL SEO si Yoast SEO actif, sinon champs meta SEO legers
* WPGraphQL WooCommerce, WPGraphQL for ACF, JWT, Gutenberg

Depuis **Outils > WPGraphQL Unified**, un tableau resume les drapeaux, les constantes `WPGRAPHQL_UNIFIED_ENABLE_*` et les dependances detectees (WooCommerce, ACF, Yoast, etc.).

== Installation ==

1. Copier le dossier `wp-graphql-unified` dans `wp-content/plugins/`.
2. Activer le plugin dans l'administration WordPress.
3. Verifier l'endpoint GraphQL (`/graphql`).

== Frequently Asked Questions ==

= Est-ce compatible avec React / Next.js ? =

Oui, le plugin expose une API GraphQL standard compatible avec Apollo, urql, Relay et fetch.

= Puis-je desactiver certains modules ? =

Oui, via les constantes `WPGRAPHQL_UNIFIED_ENABLE_*` dans `wp-config.php`.

= Ou voir l'etat des modules ? =

Dans **Outils > WPGraphQL Unified** (lien aussi sous le nom du plugin dans la liste des extensions).

== Changelog ==

= 0.2.0 =
* Refactor declaratif des modules dans `Plugin.php`, hook `wpgraphql_unified_booted`, filtre `wpgraphql_unified_legacy_path`.
* Page d'etat admin, descriptions i18n des drapeaux, deduplication des notices, constantes `WPGRAPHQL_UNIFIED_URL` / `WPGRAPHQL_UNIFIED_REPO_URL`.

= 0.1.0 =
* Premiere version unifiee avec core embarque et modules legacy.
* Durcissement bootstrap, guards schema, diagnostics admin et scripts de regression.
