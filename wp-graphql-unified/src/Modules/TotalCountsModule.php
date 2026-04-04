<?php

namespace WPGraphQLUnified\Modules;

use WPGraphQLUnified\Contracts\ModuleInterface;
use WPGraphQLUnified\Support\FeatureFlags;
use WPGraphQLUnified\Support\LegacyPathResolver;
use WPGraphQLUnified\Support\ModuleStatusReporter;

final class TotalCountsModule implements ModuleInterface {
	public function register(): void {
		if ( ! FeatureFlags::enabled( 'total_counts' ) ) {
			return;
		}
		if ( function_exists( 'Cactus\\GQLTC\\init' ) ) {
			return;
		}

		$main_file = LegacyPathResolver::resolve(
			'total-counts-for-wp-graphql-master/total-counts-for-wp-graphql-master/total-counts-for-wp-graphql.php'
		);
		if ( '' !== $main_file ) {
			require_once $main_file;
			return;
		}

		ModuleStatusReporter::error( 'TotalCounts', 'Bundled Total Counts for WPGraphQL source not found.' );
	}
}
