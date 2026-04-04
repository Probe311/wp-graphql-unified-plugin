<?php

namespace WPGraphQLUnified\Support;

final class ModuleStatusReporter {
	/**
	 * @var array<int,array{module:string,level:string,message:string}>
	 */
	private static array $messages = array();

	/**
	 * @var array<string,true>
	 */
	private static array $seen = array();

	public static function warning( string $module, string $message ): void {
		self::push( $module, 'warning', $message );
	}

	public static function error( string $module, string $message ): void {
		self::push( $module, 'error', $message );
	}

	private static function push( string $module, string $level, string $message ): void {
		$key = $level . '|' . $module . '|' . $message;
		if ( isset( self::$seen[ $key ] ) ) {
			return;
		}
		self::$seen[ $key ] = true;
		self::$messages[]   = array(
			'module'  => $module,
			'level'   => $level,
			'message' => $message,
		);
	}

	public static function render_admin_notices(): void {
		if ( ! current_user_can( 'activate_plugins' ) ) {
			return;
		}

		foreach ( self::$messages as $entry ) {
			$level   = 'error' === $entry['level'] ? 'notice-error' : 'notice-warning';
			$module  = esc_html( $entry['module'] );
			$message = esc_html( $entry['message'] );
			echo '<div class="notice ' . esc_attr( $level ) . ' is-dismissible"><p><strong>' . esc_html__( 'WPGraphQL Unified', 'wp-graphql-unified' ) . ' — ' . $module . ':</strong> ' . $message . '</p></div>';
		}
	}
}
