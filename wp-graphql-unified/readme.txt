=== WPGraphQL Unified ===
Contributors: julienvaissier, probe311
Tags: wordpress, graphql, wpgraphql, woocommerce, acf, gutenberg, jwt, headless, nextjs, react, meta-query, tax-query, cpt, yoast
Requires at least: 6.5
Tested up to: 6.9
Requires PHP: 8.1
Stable tag: 0.3.2
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

Depuis **Reglages > WPGraphQL Unified**, activez ou desactivez chaque paquet, consultez l'auteur (Julien Vaissier), les versions WordPress / PHP / plugin et un tableau d'etat detaille.

== Installation ==

1. Copier le dossier `wp-graphql-unified` dans `wp-content/plugins/`.
2. Activer le plugin dans l'administration WordPress.
3. Verifier l'endpoint GraphQL (`/graphql`).

== Frequently Asked Questions ==

= Est-ce compatible avec React / Next.js ? =

Oui, le plugin expose une API GraphQL standard compatible avec Apollo, urql, Relay et fetch.

= Puis-je desactiver certains modules ? =

Oui : **Reglages > WPGraphQL Unified** (option `wpgraphql_unified_feature_flags`), ou via les constantes `WPGRAPHQL_UNIFIED_ENABLE_*` dans `wp-config.php` (prioritaires sur l'ecran).

= Ou voir l'etat des modules ? =

**Reglages > WPGraphQL Unified**, ou le lien **Reglages** / **Reglages & etat** sous le nom du plugin.

= Quelles versions WordPress / PHP ? =

WordPress **6.5+**, PHP **8.1+** (8.3 ou 8.4 recommandes). Teste avec WordPress **6.9.x**. Le front headless (React, Next.js) est indépendant : voir `package.json` a la racine du plugin pour les engines Node/npm.

== Changelog ==

= 0.3.2 =
* Sous-menu **GraphQL > Paquets unifiés** (meme ecran que Reglages, formulaire et redirection preserves).
* Carte Extensions : inventaire dynamique actifs / inactifs pour les 14 paquets ; lien reglages vers le menu elephant ; correctif legacy WPGraphQL pour conserver `settings_path`.

= 0.3.1 =
* Page WPGraphQL Extensions : retrait des cartes ACF / Add WPGraphQL SEO lorsqu'ils sont deja fournis par le bundle ; ajout d'une fiche « WPGraphQL Unified » reconnue comme installee.
* Menu « GraphQL Gutenberg » deplace sous **GraphQL** (sous-menu) lorsque GraphiQL est actif.

= 0.3.0 =
* Auteur : Julien Vaissier, URI https://julienvaissier.fr/fr/
* Page **Reglages > WPGraphQL Unified** : activation / desactivation des paquets, versions WordPress & PHP, prenom / nom, etat detaille. Redirection depuis l'ancienne page Outils.

= 0.2.1 =
* Exigences alignees sur les versions recentes : WordPress 6.5+, PHP 8.1+, teste jusqu'a 6.9.
* `package.json` (engines Node/npm) et documentation stack front React / Node dans le README.

= 0.2.0 =
* Refactor declaratif des modules dans `Plugin.php`, hook `wpgraphql_unified_booted`, filtre `wpgraphql_unified_legacy_path`.
* Page d'etat admin, descriptions i18n des drapeaux, deduplication des notices, constantes `WPGRAPHQL_UNIFIED_URL` / `WPGRAPHQL_UNIFIED_REPO_URL`.

= 0.1.0 =
* Premiere version unifiee avec core embarque et modules legacy.
* Durcissement bootstrap, guards schema, diagnostics admin et scripts de regression.
