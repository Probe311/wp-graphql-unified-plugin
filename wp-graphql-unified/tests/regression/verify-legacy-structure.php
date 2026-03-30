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
	'wp-graphql-google-schema-master/wp-graphql-google-schema-master/Meta.php',
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
