<?php

namespace WPGraphQLUnified\Modules;

use WPGraphQLUnified\Contracts\ModuleInterface;
use WPGraphQLUnified\Support\FeatureFlags;
use WPGraphQLUnified\Support\LegacyPathResolver;
use WPGraphQLUnified\Support\ModuleStatusReporter;

final class MetaQueryModule implements ModuleInterface {
	public function register(): void {
		if ( ! FeatureFlags::enabled( 'meta_query' ) ) {
			return;
		}
		if ( defined( 'WPGRAPHQL_METAQUERY_VERSION' ) ) {
			return;
		}

		$main_file = LegacyPathResolver::resolve(
			'wp-graphql-meta-query-develop/wp-graphql-meta-query-develop/wp-graphql-meta-query.php'
		);
		if ( '' !== $main_file ) {
			require_once $main_file;
			return;
		}

		ModuleStatusReporter::error( 'MetaQuery', 'Bundled WPGraphQL Meta Query source not found.' );
	}
}
