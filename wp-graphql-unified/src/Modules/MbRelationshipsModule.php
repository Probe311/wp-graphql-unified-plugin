<?php

namespace WPGraphQLUnified\Modules;

use WPGraphQLUnified\Contracts\ModuleInterface;
use WPGraphQLUnified\Support\FeatureFlags;
use WPGraphQLUnified\Support\LegacyPathResolver;
use WPGraphQLUnified\Support\ModuleStatusReporter;

final class MbRelationshipsModule implements ModuleInterface {
	public function register(): void {
		if ( ! FeatureFlags::enabled( 'mb_relationships' ) ) {
			return;
		}
		if ( class_exists( '\WPGraphQL_MB_Relationships' ) ) {
			return;
		}
		if ( ! class_exists( 'MBR_Loader' ) ) {
			ModuleStatusReporter::warning(
				'MBRelationships',
				'MB Relationships is not active. WP GraphQL MB Relationships was not loaded.'
			);
			return;
		}

		$main_file = LegacyPathResolver::resolve(
			'wp-graphql-mb-relationships-master/wp-graphql-mb-relationships-master/wp-graphql-mb-relationships.php'
		);
		if ( '' !== $main_file ) {
			require_once $main_file;
			return;
		}

		ModuleStatusReporter::error( 'MBRelationships', 'Bundled WP GraphQL MB Relationships source not found.' );
	}
}
