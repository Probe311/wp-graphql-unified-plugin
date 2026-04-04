<?php

namespace WPGraphQLUnified\Modules;

use WPGraphQLUnified\Contracts\ModuleInterface;
use WPGraphQLUnified\Support\BundledLegacy;
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

		BundledLegacy::require_file(
			'wp-graphql-woocommerce-v0.19.0/wp-graphql-woocommerce.php',
			'WooCommerce',
			'Bundled WooGraphQL source not found.'
		);
	}
}
