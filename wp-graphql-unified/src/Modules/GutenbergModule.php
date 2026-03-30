<?php

namespace WPGraphQLUnified\Modules;

use WPGraphQLUnified\Contracts\ModuleInterface;
use WPGraphQLUnified\Support\LegacyPathResolver;
use WPGraphQLUnified\Support\ModuleStatusReporter;

final class GutenbergModule implements ModuleInterface {
	public function register(): void {
		if ( defined( 'WP_GRAPHQL_GUTENBERG_VERSION' ) ) {
			return;
		}

		$main_file = LegacyPathResolver::resolve( 'wp-graphql-gutenberg-develop/wp-graphql-gutenberg-develop/plugin.php' );
		if ( '' !== $main_file ) {
			require_once $main_file;
			return;
		}

		ModuleStatusReporter::error( 'Gutenberg', 'Bundled WPGraphQL Gutenberg source not found.' );
	}
}
