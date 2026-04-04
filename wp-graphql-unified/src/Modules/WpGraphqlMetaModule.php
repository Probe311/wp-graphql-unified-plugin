<?php

namespace WPGraphQLUnified\Modules;

use WPGraphQLUnified\Contracts\ModuleInterface;
use WPGraphQLUnified\Support\BundledLegacy;

final class WpGraphqlMetaModule implements ModuleInterface {
	public function register(): void {
		static $loaded = false;
		if ( $loaded ) {
			return;
		}

		if (
			BundledLegacy::require_file(
				'wp-graphql-meta-master/wp-graphql-meta-master/wp-graphql-meta.php',
				'Meta',
				'Bundled WP GraphQL Meta source not found.'
			)
		) {
			$loaded = true;
		}
	}
}
