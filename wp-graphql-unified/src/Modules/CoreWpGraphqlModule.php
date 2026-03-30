<?php

namespace WPGraphQLUnified\Modules;

use WPGraphQLUnified\Contracts\ModuleInterface;
use WPGraphQLUnified\Support\LegacyPathResolver;
use WPGraphQLUnified\Support\ModuleStatusReporter;

final class CoreWpGraphqlModule implements ModuleInterface {
	public function register(): void {
		if ( class_exists( '\WPGraphQL' ) ) {
			return;
		}

		$main_file = LegacyPathResolver::resolve_first(
			array(
				'wp-graphql.2.10.1/wp-graphql/wp-graphql.php',
				'wp-graphql/wp-graphql.php',
			)
		);
		if ( '' === $main_file ) {
			ModuleStatusReporter::error( 'Core', 'WPGraphQL core source not found in bundled legacy files.' );
			return;
		}

		require_once $main_file;
	}
}
