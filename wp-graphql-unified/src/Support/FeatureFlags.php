<?php

namespace WPGraphQLUnified\Support;

final class FeatureFlags {
	/**
	 * @return array<string,bool>
	 */
	public static function defaults(): array {
		return array(
			'core'      => true,
			'acf'       => true,
			'gutenberg' => true,
			'woo'       => true,
			'jwt'       => true,
			'seo'       => true,
		);
	}

	public static function enabled( string $flag ): bool {
		$defaults = self::defaults();
		$fallback = $defaults[ $flag ] ?? false;
		$const    = 'WPGRAPHQL_UNIFIED_ENABLE_' . strtoupper( $flag );

		if ( defined( $const ) ) {
			return (bool) constant( $const );
		}

		$option = get_option( 'wpgraphql_unified_feature_flags', array() );
		if ( is_array( $option ) && array_key_exists( $flag, $option ) ) {
			return self::to_bool( $option[ $flag ] );
		}

		return $fallback;
	}

	/**
	 * @param mixed $value
	 */
	private static function to_bool( $value ): bool {
		if ( is_bool( $value ) ) {
			return $value;
		}
		if ( is_int( $value ) ) {
			return 1 === $value;
		}
		if ( is_string( $value ) ) {
			return in_array( strtolower( $value ), array( '1', 'true', 'yes', 'on' ), true );
		}

		return (bool) $value;
	}
}
