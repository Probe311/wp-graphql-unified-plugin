<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

spl_autoload_register(
	static function ( $class ) {
		$prefix = 'WPGraphQLUnified\\';

		if ( 0 !== strpos( $class, $prefix ) ) {
			return;
		}

		$relative_class = substr( $class, strlen( $prefix ) );
		$relative_path  = str_replace( '\\', '/', $relative_class );
		$file           = WPGRAPHQL_UNIFIED_DIR . 'src/' . $relative_path . '.php';

		if ( file_exists( $file ) ) {
			require_once $file;
		}
	}
);
