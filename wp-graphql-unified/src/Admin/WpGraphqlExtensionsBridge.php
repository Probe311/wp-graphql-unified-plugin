<?php

namespace WPGraphQLUnified\Admin;

/**
 * Masque les écrans admin WPGraphQL « Extensions » et WPGraphQL Gutenberg (inutiles avec le bundle).
 */
final class WpGraphqlExtensionsBridge {
	public static function register(): void {
		add_action( 'admin_menu', array( self::class, 'hide_wpgraphql_extra_pages' ), 999 );
		add_action( 'admin_init', array( self::class, 'block_direct_access_to_removed_screens' ) );
	}

	/**
	 * Redirige les anciennes URL (favoris) vers Paquets unifiés.
	 */
	public static function block_direct_access_to_removed_screens(): void {
		if ( ! is_admin() || ! current_user_can( 'manage_options' ) ) {
			return;
		}
		global $pagenow;
		if ( 'admin.php' !== $pagenow ) {
			return;
		}
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- lecture seule
		if ( ! isset( $_GET['page'] ) ) {
			return;
		}
		$page = sanitize_text_field( wp_unslash( $_GET['page'] ) );
		if ( ! in_array( $page, array( 'wpgraphql-extensions', 'wp-graphql-gutenberg-admin' ), true ) ) {
			return;
		}

		$graphiql_on = function_exists( 'get_graphql_setting' ) && 'off' !== get_graphql_setting( 'graphiql_enabled', true );
		$target      = $graphiql_on
			? admin_url( 'admin.php?page=' . PluginAdmin::GRAPHQL_SUBMENU_SLUG )
			: admin_url( 'options-general.php?page=wpgraphql-unified-settings' );

		wp_safe_redirect( $target );
		exit;
	}

	public static function hide_wpgraphql_extra_pages(): void {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		remove_submenu_page( 'graphiql-ide', 'wpgraphql-extensions' );
		remove_menu_page( 'wp-graphql-gutenberg-admin' );
		remove_submenu_page( 'graphiql-ide', 'wp-graphql-gutenberg-admin' );
	}
}
