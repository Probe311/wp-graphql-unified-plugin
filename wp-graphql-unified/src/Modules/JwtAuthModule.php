<?php

namespace WPGraphQLUnified\Modules;

use WPGraphQLUnified\Contracts\ModuleInterface;
use WPGraphQLUnified\Support\LegacyPathResolver;
use WPGraphQLUnified\Support\ModuleStatusReporter;

final class JwtAuthModule implements ModuleInterface {
	public function register(): void {
		if ( defined( 'WPGRAPHQL_JWT_AUTHENTICATION_VERSION' ) ) {
			return;
		}

		$main_file = LegacyPathResolver::resolve( 'wp-graphql-jwt-authentication-develop/wp-graphql-jwt-authentication-develop/wp-graphql-jwt-authentication.php' );
		if ( '' !== $main_file ) {
			require_once $main_file;
			return;
		}

		ModuleStatusReporter::error( 'JWT', 'Bundled WPGraphQL JWT Authentication source not found.' );
	}
}
