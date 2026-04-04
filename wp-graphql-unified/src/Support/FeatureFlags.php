<?php

namespace WPGraphQLUnified\Support;

final class FeatureFlags {
	/**
	 * @return array<string,bool>
	 */
	public static function defaults(): array {
		return array(
			'core'             => true,
			'cpt'              => true,
			'enable_all'       => true,
			'cpt_ui'           => true,
			'meta_query'       => true,
			'tax_query'        => true,
			'meta'             => true,
			'total_counts'     => true,
			'mb_relationships' => true,
			'seo'              => true,
			'acf'              => true,
			'gutenberg'        => true,
			'woo'              => true,
			'jwt'              => true,
		);
	}

	/**
	 * Libellés pour l’admin et la doc (text domain wp-graphql-unified).
	 *
	 * @return array<string,string>
	 */
	public static function descriptions(): array {
		return array(
			'core'             => __( 'WPGraphQL core (requis pour le schéma de base).', 'wp-graphql-unified' ),
			'cpt'              => __( 'Exposition CPT / taxonomies via filtres GraphQL.', 'wp-graphql-unified' ),
			'enable_all'       => __( 'Active show_in_graphql sur les types publics.', 'wp-graphql-unified' ),
			'cpt_ui'           => __( 'Pont GraphQL pour Custom Post Type UI.', 'wp-graphql-unified' ),
			'meta_query'       => __( 'Argument metaQuery sur les connexions compatibles.', 'wp-graphql-unified' ),
			'tax_query'        => __( 'Argument taxQuery sur les connexions compatibles.', 'wp-graphql-unified' ),
			'meta'             => __( 'Champs meta issus de register_meta().', 'wp-graphql-unified' ),
			'total_counts'     => __( 'Champ total sur WPPageInfo (connections).', 'wp-graphql-unified' ),
			'mb_relationships' => __( 'Schéma MB Relationships (Meta Box).', 'wp-graphql-unified' ),
			'seo'              => __( 'SEO Yoast GraphQL ou champs meta de secours.', 'wp-graphql-unified' ),
			'acf'              => __( 'WPGraphQL for ACF.', 'wp-graphql-unified' ),
			'gutenberg'        => __( 'Blocs et prévisualisations Gutenberg.', 'wp-graphql-unified' ),
			'woo'              => __( 'WooGraphQL (WooCommerce).', 'wp-graphql-unified' ),
			'jwt'              => __( 'Authentification JWT pour GraphQL.', 'wp-graphql-unified' ),
		);
	}

	/**
	 * @return array<string,bool>
	 */
	public static function all_resolved(): array {
		$out = array();
		foreach ( array_keys( self::defaults() ) as $flag ) {
			$out[ $flag ] = self::enabled( $flag );
		}
		return $out;
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
