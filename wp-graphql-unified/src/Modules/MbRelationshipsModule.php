<?php

namespace WPGraphQLUnified\Modules;

use WPGraphQLUnified\Contracts\ModuleInterface;
use WPGraphQLUnified\Support\BundledLegacy;
use WPGraphQLUnified\Support\ModuleStatusReporter;

final class MbRelationshipsModule implements ModuleInterface {
	public function register(): void {
		if ( class_exists( '\WPGraphQL_MB_Relationships' ) ) {
			return;
		}
		if ( ! class_exists( 'MBR_Loader' ) ) {
			ModuleStatusReporter::warning(
				'MBRelationships',
				'MB Relationships is not active. WP GraphQL MB Relationships was not loaded.'
			);
			return;
		}

		BundledLegacy::require_file(
			'wp-graphql-mb-relationships-master/wp-graphql-mb-relationships-master/wp-graphql-mb-relationships.php',
			'MBRelationships',
			'Bundled WP GraphQL MB Relationships source not found.'
		);
	}
}
