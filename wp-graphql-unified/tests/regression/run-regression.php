<?php

if ( PHP_SAPI !== 'cli' ) {
	exit( 1 );
}

$endpoint = $argv[1] ?? '';
if ( '' === $endpoint ) {
	fwrite( STDERR, "Usage: php tests/regression/run-regression.php <graphql-endpoint>\n" );
	exit( 1 );
}

$queries_dir = __DIR__ . '/queries';
$files       = glob( $queries_dir . '/*.graphql' );

if ( ! is_array( $files ) || empty( $files ) ) {
	fwrite( STDERR, "No query files found.\n" );
	exit( 1 );
}

$exit_code = 0;
foreach ( $files as $file ) {
	$query = file_get_contents( $file );
	if ( false === $query ) {
		fwrite( STDERR, "Cannot read {$file}\n" );
		$exit_code = 1;
		continue;
	}

	$payload = json_encode( array( 'query' => $query ) );
	$opts    = array(
		'http' => array(
			'method'  => 'POST',
			'header'  => "Content-Type: application/json\r\n",
			'content' => $payload,
			'timeout' => 30,
		),
	);

	$response = @file_get_contents( $endpoint, false, stream_context_create( $opts ) );
	if ( false === $response ) {
		fwrite( STDERR, '[FAIL] ' . basename( $file ) . " request failed\n" );
		$exit_code = 1;
		continue;
	}

	$json = json_decode( $response, true );
	if ( ! is_array( $json ) ) {
		fwrite( STDERR, '[FAIL] ' . basename( $file ) . " invalid JSON response\n" );
		$exit_code = 1;
		continue;
	}

	if ( ! empty( $json['errors'] ) ) {
		fwrite( STDERR, '[FAIL] ' . basename( $file ) . " GraphQL errors detected\n" );
		$exit_code = 1;
		continue;
	}

	echo '[OK] ' . basename( $file ) . "\n";
}

exit( $exit_code );
