<?php

namespace WPGraphQLUnified\Modules;

use WPGraphQLUnified\Contracts\ModuleInterface;
use WPGraphQLUnified\Support\FeatureFlags;
use WPGraphQLUnified\Support\LegacyPathResolver;
use WPGraphQLUnified\Support\ModuleStatusReporter;

final class EnableAllPostTypesModule implements ModuleInterface {
	public function register(): void {
		if ( ! FeatureFlags::enabled( 'enable_all_post_types' ) ) {
			return;
		}

		static $loaded = false;
		if ( $loaded ) {
			return;
		}

		$main_file = LegacyPathResolver::resolve(
			'wp-graphql-enable-all-post-types-master/wp-graphql-enable-all-post-types-master/wp-graphql-enable-all-post-types.php'
		);
		if ( '' !== $main_file ) {
			require_once $main_file;
			$loaded = true;
			return;
		}

		ModuleStatusReporter::error( 'EnableAllPostTypes', 'Bundled WPGraphQL Enable all post types source not found.' );
	}
}
