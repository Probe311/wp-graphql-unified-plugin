<?php

if ( PHP_SAPI !== 'cli' ) {
	exit( 1 );
}

$plugin_root = dirname( __DIR__, 2 );
$legacy_root = $plugin_root . '/legacy';

$required = array(
	'wp-graphql.2.10.1/wp-graphql/wp-graphql.php',
	'wp-graphql-woocommerce-v0.19.0/wp-graphql-woocommerce.php',
	'wp-graphql-jwt-authentication-develop/wp-graphql-jwt-authentication-develop/wp-graphql-jwt-authentication.php',
	'wp-graphql-gutenberg-develop/wp-graphql-gutenberg-develop/plugin.php',
	'wpgraphql-acf-develop/wpgraphql-acf-develop/wpgraphql-acf.php',
	'total-counts-for-wp-graphql-master/total-counts-for-wp-graphql-master/total-counts-for-wp-graphql.php',
	'wp-graphql-cpt-master/wp-graphql-cpt-master/wp-graphql-cpt.php',
	'wp-graphql-custom-post-type-ui-master/wp-graphql-custom-post-type-ui-master/wp-graphql-custom-post-type-ui.php',
	'wp-graphql-enable-all-post-types-master/wp-graphql-enable-all-post-types-master/wp-graphql-enable-all-post-types.php',
	'wp-graphql-meta-master/wp-graphql-meta-master/wp-graphql-meta.php',
	'wp-graphql-meta-query-develop/wp-graphql-meta-query-develop/wp-graphql-meta-query.php',
	'wp-graphql-tax-query-develop/wp-graphql-tax-query-develop/wp-graphql-tax-query.php',
	'wp-graphql-mb-relationships-master/wp-graphql-mb-relationships-master/wp-graphql-mb-relationships.php',
	'wp-graphql-yoast-seo-master/wp-graphql-yoast-seo-master/wp-graphql-yoast-seo.php',
);

$missing = array();
foreach ( $required as $relative ) {
	$path = $legacy_root . '/' . $relative;
	if ( ! file_exists( $path ) ) {
		$missing[] = $relative;
	}
}

if ( ! empty( $missing ) ) {
	fwrite( STDERR, "Missing legacy files:\n" );
	foreach ( $missing as $item ) {
		fwrite( STDERR, " - {$item}\n" );
	}
	exit( 1 );
}

echo "Legacy structure OK\n";
exit( 0 );
