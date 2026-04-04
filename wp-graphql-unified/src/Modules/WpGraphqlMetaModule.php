<?php

namespace WPGraphQLUnified\Modules;

use WPGraphQLUnified\Contracts\ModuleInterface;
use WPGraphQLUnified\Support\FeatureFlags;
use WPGraphQLUnified\Support\LegacyPathResolver;
use WPGraphQLUnified\Support\ModuleStatusReporter;

final class WpGraphqlMetaModule implements ModuleInterface {
	public function register(): void {
		if ( ! FeatureFlags::enabled( 'meta' ) ) {
			return;
		}

		static $loaded = false;
		if ( $loaded ) {
			return;
		}

		$main_file = LegacyPathResolver::resolve( 'wp-graphql-meta-master/wp-graphql-meta-master/wp-graphql-meta.php' );
		if ( '' !== $main_file ) {
			require_once $main_file;
			$loaded = true;
			return;
		}

		ModuleStatusReporter::error( 'Meta', 'Bundled WP GraphQL Meta source not found.' );
	}
}
