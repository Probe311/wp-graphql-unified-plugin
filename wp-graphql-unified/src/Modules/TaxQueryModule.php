<?php

namespace WPGraphQLUnified\Modules;

use WPGraphQLUnified\Contracts\ModuleInterface;
use WPGraphQLUnified\Support\BundledLegacy;

final class TaxQueryModule implements ModuleInterface {
	public function register(): void {
		if ( defined( 'WPGRAPHQL_TAXQUERY_VERSION' ) ) {
			return;
		}

		BundledLegacy::require_file(
			'wp-graphql-tax-query-develop/wp-graphql-tax-query-develop/wp-graphql-tax-query.php',
			'TaxQuery',
			'Bundled WPGraphQL Tax Query source not found.'
		);
	}
}
