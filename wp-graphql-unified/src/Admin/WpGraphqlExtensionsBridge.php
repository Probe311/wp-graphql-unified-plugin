<?php

namespace WPGraphQLUnified\Admin;

use WPGraphQLUnified\Plugin;
use WPGraphQLUnified\Support\FeatureFlags;

/**
 * Intègre le bundle au menu GraphQL (éléphant) et à la page Extensions.
 */
final class WpGraphqlExtensionsBridge {
	public static function register(): void {
		add_filter( 'graphql_get_extensions', array( self::class, 'filter_extensions' ), 50 );
		add_action( 'admin_menu', array( self::class, 'relocate_gutenberg_under_graphql' ), 100 );
	}

	/**
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
			'name'              => __( 'WPGraphQL Unified (bundle intégré)', 'wp-graphql-unified' ),
			'description'       => self::build_bundle_extensions_description(),
			'plugin_url'        => $plugin_root_url,
			'support_url'       => '' !== $repo ? $repo : $plugin_root_url,
			'documentation_url' => '' !== $repo ? $repo : $plugin_root_url,
			'repo_url'          => '' !== $repo ? $repo : '',
			'settings_path'     => 'admin.php?page=' . PluginAdmin::GRAPHQL_SUBMENU_SLUG,
			'author'            => array(
				'name'     => __( 'Julien Vaissier', 'wp-graphql-unified' ),
				'homepage' => 'https://julienvaissier.fr/fr/',
			),
		);

		return $extensions;
	}

	private static function build_bundle_extensions_description(): string {
		$on  = array();
		$off = array();

		foreach ( Plugin::bundle_flag_order() as $flag ) {
			if ( ! array_key_exists( $flag, FeatureFlags::defaults() ) ) {
				continue;
			}
			$label = self::flag_short_label( $flag );
			if ( FeatureFlags::enabled( $flag ) ) {
				$on[] = $label;
			} else {
				$off[] = $label;
			}
		}

		/* translators: 1: comma-separated list of active packages, 2: comma-separated list of inactive packages */
		$body = sprintf(
			__( 'Actifs : %1$s. Inactifs : %2$s. Réglages : menu GraphQL → Paquets unifiés (ou Réglages → WPGraphQL Unified).', 'wp-graphql-unified' ),
			implode( ', ', $on ),
			$off !== array() ? implode( ', ', $off ) : '—'
		);

		return $body;
	}

	private static function flag_short_label( string $flag ): string {
		$labels = array(
			'core'             => __( 'Core', 'wp-graphql-unified' ),
			'cpt'              => __( 'CPT', 'wp-graphql-unified' ),
			'enable_all'       => __( 'Tous les CPT', 'wp-graphql-unified' ),
			'cpt_ui'           => __( 'CPT UI', 'wp-graphql-unified' ),
			'meta_query'       => __( 'Meta query', 'wp-graphql-unified' ),
			'tax_query'        => __( 'Tax query', 'wp-graphql-unified' ),
			'meta'             => __( 'Meta register_meta', 'wp-graphql-unified' ),
			'total_counts'     => __( 'Total counts', 'wp-graphql-unified' ),
			'mb_relationships' => __( 'MB Relationships', 'wp-graphql-unified' ),
			'seo'              => __( 'SEO (Yoast / fallback)', 'wp-graphql-unified' ),
			'acf'              => __( 'ACF', 'wp-graphql-unified' ),
			'gutenberg'        => __( 'Gutenberg', 'wp-graphql-unified' ),
			'woo'              => __( 'WooCommerce', 'wp-graphql-unified' ),
			'jwt'              => __( 'JWT', 'wp-graphql-unified' ),
		);

		return $labels[ $flag ] ?? $flag;
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
