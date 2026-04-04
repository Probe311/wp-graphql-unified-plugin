<?php

namespace WPGraphQLUnified\Admin;

use WPGraphQLUnified\Plugin;

final class PluginAdmin {
	public static function register(): void {
		add_action( 'admin_menu', array( self::class, 'add_tools_page' ) );
		add_filter( 'plugin_row_meta', array( self::class, 'row_meta' ), 10, 2 );
	}

	public static function add_tools_page(): void {
		add_management_page(
			__( 'WPGraphQL Unified', 'wp-graphql-unified' ),
			__( 'WPGraphQL Unified', 'wp-graphql-unified' ),
			'manage_options',
			'wpgraphql-unified-status',
			array( self::class, 'render_status' )
		);
	}

	public static function render_status(): void {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$rows    = Plugin::admin_status_rows();
		$version = defined( 'WPGRAPHQL_UNIFIED_VERSION' ) ? WPGRAPHQL_UNIFIED_VERSION : '';
		$wg_ver  = defined( 'WPGRAPHQL_VERSION' ) ? WPGRAPHQL_VERSION : '—';

		echo '<div class="wrap">';
		echo '<h1>' . esc_html__( 'WPGraphQL Unified — état', 'wp-graphql-unified' ) . '</h1>';
		echo '<p class="description">' . esc_html__( 'Drapeaux, constantes WPGRAPHQL_UNIFIED_ENABLE_* et dépendances WordPress.', 'wp-graphql-unified' ) . '</p>';
		echo '<p><strong>' . esc_html__( 'Version du plugin unifié', 'wp-graphql-unified' ) . ':</strong> ' . esc_html( (string) $version );
		echo ' &nbsp;|&nbsp; <strong>' . esc_html__( 'WPGraphQL', 'wp-graphql-unified' ) . ':</strong> ' . esc_html( (string) $wg_ver );
		echo ' &nbsp;|&nbsp; <strong>PHP:</strong> ' . esc_html( PHP_VERSION );
		echo '</p>';

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

		echo '<h2>' . esc_html__( 'Option wpgraphql_unified_feature_flags', 'wp-graphql-unified' ) . '</h2>';
		$opt = get_option( 'wpgraphql_unified_feature_flags', array() );
		if ( ! is_array( $opt ) || array() === $opt ) {
			echo '<p>' . esc_html__( 'Aucune surcharge en base (constantes ou défauts uniquement).', 'wp-graphql-unified' ) . '</p>';
		} else {
			echo '<pre style="max-width:100%;overflow:auto;">' . esc_html( wp_json_encode( $opt, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE ) ) . '</pre>';
		}

		echo '</div>';
	}

	/**
	 * @param array<int,string> $links
	 * @return array<int,string>
	 */
	public static function row_meta( array $links, string $file ): array {
		if ( plugin_basename( WPGRAPHQL_UNIFIED_FILE ) !== $file ) {
			return $links;
		}

		$url = admin_url( 'tools.php?page=wpgraphql-unified-status' );
		$links[] = '<a href="' . esc_url( $url ) . '">' . esc_html__( 'État des modules', 'wp-graphql-unified' ) . '</a>';

		if ( defined( 'WPGRAPHQL_UNIFIED_REPO_URL' ) && WPGRAPHQL_UNIFIED_REPO_URL ) {
			$links[] = '<a href="' . esc_url( (string) WPGRAPHQL_UNIFIED_REPO_URL ) . '" target="_blank" rel="noopener noreferrer">' . esc_html__( 'Dépôt', 'wp-graphql-unified' ) . '</a>';
		}

		return $links;
	}
}
