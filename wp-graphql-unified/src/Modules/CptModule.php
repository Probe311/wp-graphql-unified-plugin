<?php

namespace WPGraphQLUnified\Modules;

use WPGraphQLUnified\Contracts\ModuleInterface;
use WPGraphQLUnified\Support\BundledLegacy;

final class CptModule implements ModuleInterface {
	public function register(): void {
		if ( class_exists( '\WPGraphQL\Extensions\CPT' ) ) {
			return;
		}

		BundledLegacy::require_file(
			'wp-graphql-cpt-master/wp-graphql-cpt-master/wp-graphql-cpt.php',
			'CPT',
			'Bundled WP GraphQL CPT source not found.'
		);
	}
}
