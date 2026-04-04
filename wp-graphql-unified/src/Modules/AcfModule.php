<?php

namespace WPGraphQLUnified\Modules;

use WPGraphQLUnified\Contracts\ModuleInterface;
use WPGraphQLUnified\Support\BundledLegacy;
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

		BundledLegacy::require_file(
			'wpgraphql-acf-develop/wpgraphql-acf-develop/wpgraphql-acf.php',
			'ACF',
			'Bundled WPGraphQL for ACF source not found.'
		);
	}
}
