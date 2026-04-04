<?php

namespace WPGraphQLUnified\Admin;

use WPGraphQLUnified\Support\FeatureFlags;

/**
 * Harmonise l’UI WPGraphQL : page Extensions et menu Gutenberg.
 */
final class WpGraphqlExtensionsBridge {
	public static function register(): void {
		add_filter( 'graphql_get_extensions', array( self::class, 'filter_extensions' ), 50 );
		add_action( 'admin_menu', array( self::class, 'relocate_gutenberg_under_graphql' ), 100 );
	}

	/**
	 * Retire les fiches « installer » pour les extensions déjà fournies par le bundle,
	 * et ajoute une entrée reconnue comme installée/active (slug = dossier du plugin).
	 *
	 * @param array<string,array<string,mixed>> $extensions
	 * @return array<string,array<string,mixed>>
	 */
	public static function filter_extensions( array $extensions ): array {
		if ( FeatureFlags::enabled( 'acf' ) ) {
			unset( $extensions['wp-graphql/wpgraphql-acf'] );
		}
		if ( FeatureFlags::enabled( 'seo' ) ) {
			unset( $extensions['ashhitch/wp-graphql-yoast-seo'] );
		}

		$plugin_root_url = untrailingslashit( plugins_url( '', WPGRAPHQL_UNIFIED_FILE ) );
		$repo            = defined( 'WPGRAPHQL_UNIFIED_REPO_URL' ) ? (string) WPGRAPHQL_UNIFIED_REPO_URL : '';

		$extensions['wpgraphql-unified-bundle'] = array(
			'name'              => __( 'WPGraphQL Unified (extensions intégrées)', 'wp-graphql-unified' ),
			'description'       => __( 'Ce plugin regroupe WPGraphQL et plusieurs extensions (ACF, SEO Yoast GraphQL, WooCommerce, JWT, Gutenberg, requêtes meta/tax, etc.). Activez ou désactivez chaque paquet dans Réglages → WPGraphQL Unified.', 'wp-graphql-unified' ),
			'plugin_url'        => $plugin_root_url,
			'support_url'       => '' !== $repo ? $repo : $plugin_root_url,
			'documentation_url' => '' !== $repo ? $repo : $plugin_root_url,
			'repo_url'          => '' !== $repo ? $repo : '',
			'author'            => array(
				'name'     => __( 'Julien Vaissier', 'wp-graphql-unified' ),
				'homepage' => 'https://julienvaissier.fr/fr/',
			),
		);

		return $extensions;
	}

	/**
	 * Remplace le menu racine « GraphQL Gutenberg » par un sous-menu du menu GraphQL (GraphiQL).
	 */
	public static function relocate_gutenberg_under_graphql(): void {
		if ( ! FeatureFlags::enabled( 'gutenberg' ) || ! defined( 'WP_GRAPHQL_GUTENBERG_VERSION' ) ) {
			return;
		}
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		global $submenu;
		if ( ! isset( $submenu['graphiql-ide'] ) ) {
			return;
		}

		remove_menu_page( 'wp-graphql-gutenberg-admin' );

		add_submenu_page(
			'graphiql-ide',
			__( 'GraphQL Gutenberg', 'wp-graphql-gutenberg' ),
			__( 'Gutenberg (blocs)', 'wp-graphql-unified' ),
			'manage_options',
			'wp-graphql-gutenberg-admin',
			static function () {
				echo '<div class="wrap"><div id="wp-graphql-gutenberg-admin"></div></div>';
			}
		);
	}
}
