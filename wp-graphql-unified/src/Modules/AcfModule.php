<?php

namespace WPGraphQLUnified\Modules;

use WPGraphQLUnified\Contracts\ModuleInterface;
use WPGraphQLUnified\Support\LegacyPathResolver;
use WPGraphQLUnified\Support\ModuleStatusReporter;

final class AcfModule implements ModuleInterface {
	public function register(): void {
		if ( defined( 'WPGRAPHQL_FOR_ACF_VERSION' ) ) {
			return;
		}
		if ( ! function_exists( 'acf' ) ) {
			ModuleStatusReporter::warning( 'ACF', 'Advanced Custom Fields is not active. ACF schema was not loaded.' );
			return;
		}

		$main_file = LegacyPathResolver::resolve( 'wpgraphql-acf-develop/wpgraphql-acf-develop/wpgraphql-acf.php' );
		if ( '' !== $main_file ) {
			require_once $main_file;
			return;
		}

		ModuleStatusReporter::error( 'ACF', 'Bundled WPGraphQL for ACF source not found.' );
	}
}
