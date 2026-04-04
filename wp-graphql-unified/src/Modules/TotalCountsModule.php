<?php

namespace WPGraphQLUnified\Modules;

use WPGraphQLUnified\Contracts\ModuleInterface;
use WPGraphQLUnified\Support\BundledLegacy;

final class TotalCountsModule implements ModuleInterface {
	public function register(): void {
		if ( function_exists( 'Cactus\\GQLTC\\init' ) ) {
			return;
		}

		BundledLegacy::require_file(
			'total-counts-for-wp-graphql-master/total-counts-for-wp-graphql-master/total-counts-for-wp-graphql.php',
			'TotalCounts',
			'Bundled Total Counts for WPGraphQL source not found.'
		);
	}
}
