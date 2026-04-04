<?php
/**
 * Plugin Name: WPGraphQL Unified
 * Description: Unified WPGraphQL plugin bundling core, WooCommerce, ACF, JWT Auth, Gutenberg, and SEO schema fields.
 * Version: 0.3.0
 * Requires at least: 6.5
 * Requires PHP: 8.1
 * Author: Julien Vaissier
 * Author URI: https://julienvaissier.fr/fr/
 * License: GPL-2.0-or-later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: wp-graphql-unified
 * Domain Path: /languages
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'WPGRAPHQL_UNIFIED_VERSION', '0.3.0' );
define( 'WPGRAPHQL_UNIFIED_FILE', __FILE__ );
define( 'WPGRAPHQL_UNIFIED_DIR', plugin_dir_path( __FILE__ ) );
define( 'WPGRAPHQL_UNIFIED_URL', plugin_dir_url( __FILE__ ) );
define( 'WPGRAPHQL_UNIFIED_REPO_URL', 'https://github.com/Probe311/WP-Graphql-Unified-Plugin' );

require_once WPGRAPHQL_UNIFIED_DIR . 'src/autoload.php';

\WPGraphQLUnified\Plugin::instance()->boot();
