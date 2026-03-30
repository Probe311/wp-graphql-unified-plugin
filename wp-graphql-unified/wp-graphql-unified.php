<?php
/**
 * Plugin Name: WPGraphQL Unified
 * Description: Unified WPGraphQL plugin bundling core, WooCommerce, ACF, JWT Auth, Gutenberg, and SEO schema fields.
 * Version: 0.1.0
 * Author: Unified GraphQL Team
 * License: GPL-2.0-or-later
 * Text Domain: wp-graphql-unified
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'WPGRAPHQL_UNIFIED_VERSION', '0.1.0' );
define( 'WPGRAPHQL_UNIFIED_FILE', __FILE__ );
define( 'WPGRAPHQL_UNIFIED_DIR', plugin_dir_path( __FILE__ ) );

require_once WPGRAPHQL_UNIFIED_DIR . 'src/autoload.php';

\WPGraphQLUnified\Plugin::instance()->boot();
