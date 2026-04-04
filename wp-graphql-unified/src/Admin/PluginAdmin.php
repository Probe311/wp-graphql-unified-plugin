<?php

namespace WPGraphQLUnified\Admin;

use WPGraphQLUnified\Plugin;
use WPGraphQLUnified\Support\FeatureFlags;

final class PluginAdmin {
	public const GRAPHQL_SUBMENU_SLUG = 'wpgraphql-unified-graphql';

	private const OPTION_NAME  = 'wpgraphql_unified_feature_flags';
	private const PAGE_SLUG    = 'wpgraphql-unified-settings';
	private const LEGACY_TOOLS = 'wpgraphql-unified-status';
	private const NONCE_ACTION = 'wpgraphql_unified_save_packages';
	private const NONCE_FIELD  = 'wpgraphql_unified_packages_nonce';
	private const RETURN_FIELD = 'wpgraphql_unified_return_page';

	public static function register(): void {
		add_action( 'admin_init', array( self::class, 'maybe_redirect_legacy_tools' ) );
		add_action( 'admin_init', array( self::class, 'maybe_save_packages' ) );
		add_action( 'admin_menu', array( self::class, 'add_settings_page' ) );
		add_action( 'admin_menu', array( self::class, 'add_graphql_elephant_submenu' ), 11 );
		add_filter( 'plugin_row_meta', array( self::class, 'row_meta' ), 10, 2 );
		add_filter( 'plugin_action_links_' . plugin_basename( WPGRAPHQL_UNIFIED_FILE ), array( self::class, 'action_links' ), 10, 2 );
	}

	public static function maybe_redirect_legacy_tools(): void {
		global $pagenow;
		if ( 'tools.php' !== $pagenow ) {
			return;
		}
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- simple redirect, no mutation
		if ( ! isset( $_GET['page'] ) || self::LEGACY_TOOLS !== $_GET['page'] ) {
			return;
		}
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		wp_safe_redirect( admin_url( 'options-general.php?page=' . self::PAGE_SLUG ) );
		exit;
	}

	public static function maybe_save_packages(): void {
		if ( ! isset( $_POST[ self::NONCE_FIELD ] ) ) {
			return;
		}
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST[ self::NONCE_FIELD ] ) ), self::NONCE_ACTION ) ) {
			return;
		}

		$raw = isset( $_POST[ self::OPTION_NAME ] ) && is_array( $_POST[ self::OPTION_NAME ] )
			? wp_unslash( $_POST[ self::OPTION_NAME ] )
			: array();
		/** @var array<string,mixed> $raw */
		$sanitized = FeatureFlags::sanitize_option_payload( $raw );
		update_option( self::OPTION_NAME, $sanitized );

		$allowed_return = array( self::PAGE_SLUG, self::GRAPHQL_SUBMENU_SLUG );
		$return         = isset( $_POST[ self::RETURN_FIELD ] )
			? sanitize_text_field( wp_unslash( $_POST[ self::RETURN_FIELD ] ) )
			: self::PAGE_SLUG;
		if ( ! in_array( $return, $allowed_return, true ) ) {
			$return = self::PAGE_SLUG;
		}
		$base   = self::PAGE_SLUG === $return
			? admin_url( 'options-general.php?page=' . self::PAGE_SLUG )
			: admin_url( 'admin.php?page=' . self::GRAPHQL_SUBMENU_SLUG );

		wp_safe_redirect( add_query_arg( 'wpgraphql_unified_saved', '1', $base ) );
		exit;
	}

	public static function add_graphql_elephant_submenu(): void {
		global $submenu;
		if ( ! isset( $submenu['graphiql-ide'] ) || ! current_user_can( 'manage_options' ) ) {
			return;
		}

		add_submenu_page(
			'graphiql-ide',
			__( 'WPGraphQL Unified — paquets', 'wp-graphql-unified' ),
			__( 'Paquets unifiés', 'wp-graphql-unified' ),
			'manage_options',
			self::GRAPHQL_SUBMENU_SLUG,
			array( self::class, 'render_settings_page' )
		);
	}

	public static function add_settings_page(): void {
		add_options_page(
			__( 'WPGraphQL Unified', 'wp-graphql-unified' ),
			__( 'WPGraphQL Unified', 'wp-graphql-unified' ),
			'manage_options',
			self::PAGE_SLUG,
			array( self::class, 'render_settings_page' )
		);
	}

	public static function render_settings_page(): void {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		if ( isset( $_GET['wpgraphql_unified_saved'] ) && '1' === $_GET['wpgraphql_unified_saved'] ) {
			echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__( 'Paquets enregistrés. Le schéma GraphQL sera aligné au prochain chargement complet de WordPress (rafraîchir l’IDE GraphQL ou une requête API).', 'wp-graphql-unified' ) . '</p></div>';
		}

		$rows        = Plugin::admin_status_rows();
		$p_version   = defined( 'WPGRAPHQL_UNIFIED_VERSION' ) ? WPGRAPHQL_UNIFIED_VERSION : '';
		$wg_ver      = defined( 'WPGRAPHQL_VERSION' ) ? WPGRAPHQL_VERSION : '—';
		$wp_ver      = get_bloginfo( 'version' );
		$author_url  = 'https://julienvaissier.fr/fr/';
		$descriptions = FeatureFlags::descriptions();
		$defaults    = FeatureFlags::defaults();

		$return_slug = self::current_settings_return_slug();

		echo '<div class="wrap">';
		echo '<h1>' . esc_html__( 'WPGraphQL Unified', 'wp-graphql-unified' ) . '</h1>';

		if ( self::GRAPHQL_SUBMENU_SLUG === $return_slug ) {
			echo '<div class="notice notice-info inline" style="margin:12px 0;"><p>' . esc_html__( 'Écran lié au menu GraphQL (éléphant) : tout ce qui est coché ici correspond aux extensions chargées avec WPGraphQL.', 'wp-graphql-unified' ) . '</p></div>';
		}

		echo '<div class="card" style="max-width:720px;margin-bottom:1.5em;padding:12px 16px;">';
		echo '<p style="margin:0 0 8px;"><strong>' . esc_html__( 'Auteur', 'wp-graphql-unified' ) . '</strong> — ';
		echo '<a href="' . esc_url( $author_url ) . '" target="_blank" rel="noopener noreferrer">' . esc_html__( 'Julien Vaissier', 'wp-graphql-unified' ) . '</a></p>';
		echo '<p style="margin:0 0 6px;"><strong>' . esc_html__( 'Prénom', 'wp-graphql-unified' ) . '</strong> : Julien &nbsp;|&nbsp; <strong>' . esc_html__( 'Nom', 'wp-graphql-unified' ) . '</strong> : Vaissier</p>';
		echo '<p style="margin:0 0 6px;"><strong>' . esc_html__( 'Version du plugin', 'wp-graphql-unified' ) . '</strong> : ' . esc_html( (string) $p_version ) . '</p>';
		echo '<p style="margin:0 0 6px;"><strong>' . esc_html__( 'WordPress', 'wp-graphql-unified' ) . '</strong> : ' . esc_html( (string) $wp_ver ) . '</p>';
		echo '<p style="margin:0 0 6px;"><strong>PHP</strong> : ' . esc_html( PHP_VERSION ) . '</p>';
		echo '<p style="margin:0;"><strong>' . esc_html__( 'WPGraphQL (core)', 'wp-graphql-unified' ) . '</strong> : ' . esc_html( (string) $wg_ver ) . '</p>';
		echo '</div>';

		echo '<h2>' . esc_html__( 'Paquets / modules', 'wp-graphql-unified' ) . '</h2>';
		echo '<p class="description">' . esc_html__( 'Décochez un paquet pour le désactiver. Les constantes WPGRAPHQL_UNIFIED_ENABLE_* dans wp-config.php priment et ne sont pas modifiables ici.', 'wp-graphql-unified' ) . '</p>';

		$form_action = self::settings_form_action_url( $return_slug );

		echo '<form method="post" action="' . esc_url( $form_action ) . '">';
		wp_nonce_field( self::NONCE_ACTION, self::NONCE_FIELD );
		echo '<input type="hidden" name="' . esc_attr( self::RETURN_FIELD ) . '" value="' . esc_attr( $return_slug ) . '" />';
		echo '<table class="form-table" role="presentation"><tbody>';

		foreach ( array_keys( $defaults ) as $flag ) {
			$locked   = FeatureFlags::is_locked_by_constant( $flag );
			$enabled  = FeatureFlags::enabled( $flag );
			$field_id = 'wpgraphql-pkg-' . $flag;
			$label    = isset( $descriptions[ $flag ] ) ? $descriptions[ $flag ] : $flag;

			echo '<tr><th scope="row">';
			echo '<label for="' . esc_attr( $field_id ) . '"><code>' . esc_html( $flag ) . '</code></label>';
			echo '</th><td>';
			if ( ! $locked ) {
				echo '<input type="hidden" name="' . esc_attr( self::OPTION_NAME ) . '[' . esc_attr( $flag ) . ']" value="0" />';
			}
			echo '<label><input type="checkbox" id="' . esc_attr( $field_id ) . '" name="' . esc_attr( self::OPTION_NAME ) . '[' . esc_attr( $flag ) . ']" value="1" ' . checked( $enabled, true, false ) . ( $locked ? ' disabled' : '' ) . ' /> ';
			echo esc_html( $label ) . '</label>';
			if ( $locked ) {
				$const = 'WPGRAPHQL_UNIFIED_ENABLE_' . strtoupper( $flag );
				echo '<p class="description">' . esc_html__( 'Verrouillé par la constante', 'wp-graphql-unified' ) . ' <code>' . esc_html( $const ) . '</code></p>';
			}
			echo '</td></tr>';
		}

		echo '</tbody></table>';
		submit_button( __( 'Enregistrer les paquets', 'wp-graphql-unified' ) );
		echo '</form>';

		echo '<hr style="margin:2em 0;" />';
		echo '<h2>' . esc_html__( 'État détaillé', 'wp-graphql-unified' ) . '</h2>';
		echo '<table class="widefat striped"><thead><tr>';
		echo '<th>' . esc_html__( 'Module', 'wp-graphql-unified' ) . '</th>';
		echo '<th>' . esc_html__( 'Drapeau', 'wp-graphql-unified' ) . '</th>';
		echo '<th>' . esc_html__( 'Activé', 'wp-graphql-unified' ) . '</th>';
		echo '<th>' . esc_html__( 'Description', 'wp-graphql-unified' ) . '</th>';
		echo '<th>' . esc_html__( 'Environnement', 'wp-graphql-unified' ) . '</th>';
		echo '</tr></thead><tbody>';

		foreach ( $rows as $row ) {
			$const = 'WPGRAPHQL_UNIFIED_ENABLE_' . strtoupper( $row['flag'] );
			$const_note = defined( $const )
				? ' <code>' . esc_html( $const ) . '</code>'
				: '';

			echo '<tr>';
			echo '<td><code>' . esc_html( $row['id'] ) . '</code></td>';
			echo '<td><code>' . esc_html( $row['flag'] ) . '</code>' . $const_note . '</td>';
			echo '<td>' . ( $row['enabled'] ? '✓' : '—' ) . '</td>';
			echo '<td>' . esc_html( $row['description'] ) . '</td>';
			echo '<td>' . esc_html( $row['env'] ) . '</td>';
			echo '</tr>';
		}

		echo '</tbody></table>';

		$opt = get_option( self::OPTION_NAME, array() );
		echo '<h2>' . esc_html__( 'Valeur brute en base (option)', 'wp-graphql-unified' ) . '</h2>';
		if ( ! is_array( $opt ) || array() === $opt ) {
			echo '<p>' . esc_html__( 'Aucune clé stockée — défauts du plugin pour les drapeaux non verrouillés.', 'wp-graphql-unified' ) . '</p>';
		} else {
			echo '<pre style="max-width:100%;overflow:auto;">' . esc_html( wp_json_encode( $opt, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE ) ) . '</pre>';
		}

		echo '</div>';
	}

	/**
	 * @param array<int,string> $links
	 * @return array<int,string>
	 */
	public static function action_links( array $links, string $file = '' ): array {
		unset( $file );
		$url      = admin_url( 'options-general.php?page=' . self::PAGE_SLUG );
		$settings = '<a href="' . esc_url( $url ) . '">' . esc_html__( 'Réglages', 'wp-graphql-unified' ) . '</a>';
		array_unshift( $links, $settings );

		return $links;
	}

	/**
	 * @param array<int,string> $links
	 * @return array<int,string>
	 */
	public static function row_meta( array $links, string $file ): array {
		if ( plugin_basename( WPGRAPHQL_UNIFIED_FILE ) !== $file ) {
			return $links;
		}

		$url = admin_url( 'options-general.php?page=' . self::PAGE_SLUG );
		$links[] = '<a href="' . esc_url( $url ) . '">' . esc_html__( 'Réglages & état', 'wp-graphql-unified' ) . '</a>';

		if ( defined( 'WPGRAPHQL_UNIFIED_REPO_URL' ) && WPGRAPHQL_UNIFIED_REPO_URL ) {
			$links[] = '<a href="' . esc_url( (string) WPGRAPHQL_UNIFIED_REPO_URL ) . '" target="_blank" rel="noopener noreferrer">' . esc_html__( 'Dépôt', 'wp-graphql-unified' ) . '</a>';
		}

		return $links;
	}

	private static function current_settings_return_slug(): string {
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- lecture seule du contexte d’écran
		if ( isset( $_GET['page'] ) && self::GRAPHQL_SUBMENU_SLUG === $_GET['page'] ) {
			return self::GRAPHQL_SUBMENU_SLUG;
		}

		return self::PAGE_SLUG;
	}

	private static function settings_form_action_url( string $return_slug ): string {
		if ( self::GRAPHQL_SUBMENU_SLUG === $return_slug ) {
			return admin_url( 'admin.php?page=' . self::GRAPHQL_SUBMENU_SLUG );
		}

		return admin_url( 'options-general.php?page=' . self::PAGE_SLUG );
	}
}
