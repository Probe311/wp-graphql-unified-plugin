<?php

namespace WPGraphQLUnified\Support;

/**
 * Charge un fichier PHP depuis legacy/ uniquement (plugin autonome).
 */
final class BundledLegacy {
	public static function require_file( string $relative_path, string $reporter_module, string $error_detail ): bool {
		$path = LegacyPathResolver::resolve( $relative_path );
		if ( '' !== $path ) {
			require_once $path;
			return true;
		}
		ModuleStatusReporter::error( $reporter_module, $error_detail );
		return false;
	}
}
