<?php

namespace WPGraphQLUnified\Modules;

use WPGraphQLUnified\Contracts\ModuleInterface;
use WPGraphQLUnified\Support\BundledLegacy;
use WPGraphQLUnified\Support\ModuleStatusReporter;

final class CptUiModule implements ModuleInterface {
	public function register(): void {
		if ( class_exists( 'WPGraphQL_CPT_UI' ) ) {
			return;
		}
		if ( ! function_exists( 'cptui_get_post_type_data' ) ) {
			ModuleStatusReporter::warning( 'CPTUI', 'Custom Post Type UI is not active. WPGraphQL CPT UI bridge was not loaded.' );
			return;
		}

		BundledLegacy::require_file(
			'wp-graphql-custom-post-type-ui-master/wp-graphql-custom-post-type-ui-master/wp-graphql-custom-post-type-ui.php',
			'CPTUI',
			'Bundled WPGraphQL Custom Post Type UI source not found.'
		);
	}
}
