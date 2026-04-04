<?php

namespace WPGraphQLUnified\Modules;

use WPGraphQLUnified\Contracts\ModuleInterface;
use WPGraphQLUnified\Support\FeatureFlags;
use WPGraphQLUnified\Support\LegacyPathResolver;
use WPGraphQLUnified\Support\ModuleStatusReporter;

final class CptModule implements ModuleInterface {
	public function register(): void {
		if ( ! FeatureFlags::enabled( 'cpt' ) ) {
			return;
		}
		if ( class_exists( '\WPGraphQL\Extensions\CPT' ) ) {
			return;
		}

		$main_file = LegacyPathResolver::resolve( 'wp-graphql-cpt-master/wp-graphql-cpt-master/wp-graphql-cpt.php' );
		if ( '' !== $main_file ) {
			require_once $main_file;
			return;
		}

		ModuleStatusReporter::error( 'CPT', 'Bundled WP GraphQL CPT source not found.' );
	}
}
