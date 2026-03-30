<?php

namespace WPGraphQLUnified\Modules;

use WPGraphQLUnified\Contracts\ModuleInterface;
use WPGraphQLUnified\Support\LegacyPathResolver;
use WPGraphQLUnified\Support\ModuleStatusReporter;

final class WooCommerceModule implements ModuleInterface {
	public function register(): void {
		if ( defined( 'WPGRAPHQL_WOOCOMMERCE_VERSION' ) ) {
			return;
		}
		if ( ! class_exists( '\WooCommerce' ) ) {
			ModuleStatusReporter::warning( 'WooCommerce', 'WooCommerce plugin is not active. Woo schema was not loaded.' );
			return;
		}

		$main_file = LegacyPathResolver::resolve( 'wp-graphql-woocommerce-v0.19.0/wp-graphql-woocommerce.php' );
		if ( '' !== $main_file ) {
			require_once $main_file;
			return;
		}

		ModuleStatusReporter::error( 'WooCommerce', 'Bundled WooGraphQL source not found.' );
	}
}
