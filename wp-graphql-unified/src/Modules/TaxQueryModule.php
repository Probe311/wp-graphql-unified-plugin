<?php

namespace WPGraphQLUnified\Modules;

use WPGraphQLUnified\Contracts\ModuleInterface;
use WPGraphQLUnified\Support\FeatureFlags;
use WPGraphQLUnified\Support\LegacyPathResolver;
use WPGraphQLUnified\Support\ModuleStatusReporter;

final class TaxQueryModule implements ModuleInterface {
	public function register(): void {
		if ( ! FeatureFlags::enabled( 'tax_query' ) ) {
			return;
		}
		if ( defined( 'WPGRAPHQL_TAXQUERY_VERSION' ) ) {
			return;
		}

		$main_file = LegacyPathResolver::resolve(
			'wp-graphql-tax-query-develop/wp-graphql-tax-query-develop/wp-graphql-tax-query.php'
		);
		if ( '' !== $main_file ) {
			require_once $main_file;
			return;
		}

		ModuleStatusReporter::error( 'TaxQuery', 'Bundled WPGraphQL Tax Query source not found.' );
	}
}
