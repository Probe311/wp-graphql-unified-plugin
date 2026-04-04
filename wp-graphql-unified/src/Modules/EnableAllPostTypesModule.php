<?php

namespace WPGraphQLUnified\Modules;

use WPGraphQLUnified\Contracts\ModuleInterface;
use WPGraphQLUnified\Support\BundledLegacy;

final class EnableAllPostTypesModule implements ModuleInterface {
	public function register(): void {
		static $loaded = false;
		if ( $loaded ) {
			return;
		}

		if (
			BundledLegacy::require_file(
				'wp-graphql-enable-all-post-types-master/wp-graphql-enable-all-post-types-master/wp-graphql-enable-all-post-types.php',
				'EnableAllPostTypes',
				'Bundled WPGraphQL Enable all post types source not found.'
			)
		) {
			$loaded = true;
		}
	}
}
