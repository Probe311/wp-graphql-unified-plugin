<?php

namespace WPGraphQLUnified\Support;

/**
 * Charge un fichier PHP depuis legacy/ uniquement (plugin autonome).
 *
 * Filtre : wpgraphql_unified_legacy_path (string $absolute_path, string $relative_path)
 */
final class BundledLegacy {
	public static function require_file( string $relative_path, string $reporter_module, string $error_detail ): bool {
		$path = self::resolve_filtered( $relative_path );
		if ( '' !== $path ) {
			require_once $path;
			return true;
		}
		ModuleStatusReporter::error( $reporter_module, $error_detail );
		return false;
	}

	public static function resolve_filtered( string $relative_path ): string {
		$path = LegacyPathResolver::resolve( $relative_path );
		/**
		 * Permet d’override le chemin résolu (tests, packaging custom).
		 *
		 * @param string $path Chemin absolu ou vide.
		 * @param string $relative_path Chemin relatif sous legacy/.
		 */
		return (string) apply_filters( 'wpgraphql_unified_legacy_path', $path, $relative_path );
	}
}
