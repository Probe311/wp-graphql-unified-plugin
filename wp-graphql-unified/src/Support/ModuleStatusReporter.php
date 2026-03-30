<?php

namespace WPGraphQLUnified\Support;

final class ModuleStatusReporter {
	/**
	 * @var array<int,array{module:string,level:string,message:string}>
	 */
	private static array $messages = array();

	public static function warning( string $module, string $message ): void {
		self::$messages[] = array(
			'module'  => $module,
			'level'   => 'warning',
			'message' => $message,
		);
	}

	public static function error( string $module, string $message ): void {
		self::$messages[] = array(
			'module'  => $module,
			'level'   => 'error',
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
			echo '<div class="notice ' . esc_attr( $level ) . '"><p><strong>WPGraphQL Unified - ' . $module . ':</strong> ' . $message . '</p></div>';
		}
	}
}
