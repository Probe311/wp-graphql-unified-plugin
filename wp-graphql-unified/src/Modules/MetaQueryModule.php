<?php

namespace WPGraphQLUnified\Modules;

use WPGraphQLUnified\Contracts\ModuleInterface;
use WPGraphQLUnified\Support\BundledLegacy;

final class MetaQueryModule implements ModuleInterface {
	public function register(): void {
		if ( defined( 'WPGRAPHQL_METAQUERY_VERSION' ) ) {
			return;
		}

		BundledLegacy::require_file(
			'wp-graphql-meta-query-develop/wp-graphql-meta-query-develop/wp-graphql-meta-query.php',
			'MetaQuery',
			'Bundled WPGraphQL Meta Query source not found.'
		);
	}
}
