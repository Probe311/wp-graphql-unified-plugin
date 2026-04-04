<?php

namespace WPGraphQLUnified\Modules;

use WPGraphQLUnified\Contracts\ModuleInterface;
use WPGraphQLUnified\Support\LegacyPathResolver;
use WPGraphQLUnified\Support\ModuleStatusReporter;

/**
 * Chooses Yoast WPGraphQL extension when Yoast SEO is active, otherwise falls back to lightweight meta fields.
 */
final class SeoRouterModule implements ModuleInterface {
	public function register(): void {
		if ( defined( 'WPGRAPHQL_YOAST_SEO_VERSION' ) ) {
			return;
		}

		if ( function_exists( 'YoastSEO' ) ) {
			$main_file = LegacyPathResolver::resolve(
				'wp-graphql-yoast-seo-master/wp-graphql-yoast-seo-master/wp-graphql-yoast-seo.php'
			);
			if ( '' !== $main_file ) {
				require_once $main_file;
				return;
			}
			ModuleStatusReporter::error( 'YoastSEO', 'Bundled Add WPGraphQL SEO source not found.' );
			return;
		}

		( new GoogleSchemaSeoModule() )->register();
	}
}
