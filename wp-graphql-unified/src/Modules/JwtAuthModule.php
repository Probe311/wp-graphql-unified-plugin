<?php

namespace WPGraphQLUnified\Modules;

use WPGraphQLUnified\Contracts\ModuleInterface;
use WPGraphQLUnified\Support\BundledLegacy;

final class JwtAuthModule implements ModuleInterface {
	public function register(): void {
		if ( defined( 'WPGRAPHQL_JWT_AUTHENTICATION_VERSION' ) ) {
			return;
		}

		BundledLegacy::require_file(
			'wp-graphql-jwt-authentication-develop/wp-graphql-jwt-authentication-develop/wp-graphql-jwt-authentication.php',
			'JWT',
			'Bundled WPGraphQL JWT Authentication source not found.'
		);
	}
}
