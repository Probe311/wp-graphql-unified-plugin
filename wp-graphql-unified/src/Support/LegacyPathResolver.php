<?php

namespace WPGraphQLUnified\Support;

final class LegacyPathResolver {
	public static function resolve( string $relative_path ): string {
		$bundled = WPGRAPHQL_UNIFIED_DIR . 'legacy/' . ltrim( $relative_path, '/\\' );
		return file_exists( $bundled ) ? $bundled : '';
	}

	/**
	 * @param string[] $relative_paths
	 */
	public static function resolve_first( array $relative_paths ): string {
		foreach ( $relative_paths as $relative_path ) {
			$resolved = self::resolve( $relative_path );
			if ( '' !== $resolved ) {
				return $resolved;
			}
		}

		return '';
	}
}
