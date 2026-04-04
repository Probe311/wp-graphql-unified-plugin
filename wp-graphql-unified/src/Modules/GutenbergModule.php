<?php

namespace WPGraphQLUnified\Modules;

use WPGraphQLUnified\Contracts\ModuleInterface;
use WPGraphQLUnified\Support\BundledLegacy;

final class GutenbergModule implements ModuleInterface {
	public function register(): void {
		if ( defined( 'WP_GRAPHQL_GUTENBERG_VERSION' ) ) {
			return;
		}

		BundledLegacy::require_file(
			'wp-graphql-gutenberg-develop/wp-graphql-gutenberg-develop/plugin.php',
			'Gutenberg',
			'Bundled WPGraphQL Gutenberg source not found.'
		);
	}
}
