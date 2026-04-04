<?php

namespace WPGraphQLUnified\Modules;

use WPGraphQLUnified\Contracts\ModuleInterface;
use WPGraphQLUnified\Support\SchemaRegistryGuards;

final class GoogleSchemaSeoModule implements ModuleInterface {
	/**
	 * @var array<string,array{meta_key:string,type:string}>
	 */
	private array $fields = array(
		'seo_title'               => array( 'meta_key' => '_yoast_wpseo_title', 'type' => 'String' ),
		'seo_metadesc'            => array( 'meta_key' => '_yoast_wpseo_metadesc', 'type' => 'String' ),
		'seo_noindex'             => array( 'meta_key' => '_yoast_wpseo_meta-robots-noindex', 'type' => 'String' ),
		'seo_nofollow'            => array( 'meta_key' => '_yoast_wpseo_meta-robots-nofollow', 'type' => 'Boolean' ),
		'opengraph_image'         => array( 'meta_key' => '_yoast_wpseo_opengraph-image', 'type' => 'String' ),
		'opengraph_title'         => array( 'meta_key' => '_yoast_wpseo_opengraph-title', 'type' => 'String' ),
		'opengraph_description'   => array( 'meta_key' => '_yoast_wpseo_opengraph-description', 'type' => 'String' ),
		'seo_twitter_title'       => array( 'meta_key' => '_yoast_wpseo_twitter-title', 'type' => 'String' ),
		'seo_twitter_card'        => array( 'meta_key' => '_yoast_wpseo_twitter-card', 'type' => 'String' ),
		'seo_twitter_description' => array( 'meta_key' => '_yoast_wpseo_twitter-description', 'type' => 'String' ),
		'seo_twitter_image'       => array( 'meta_key' => '_yoast_wpseo_twitter-image', 'type' => 'String' ),
		'seo_canonical'           => array( 'meta_key' => '_yoast_wpseo_canonical', 'type' => 'String' ),
		'seo_redirect'            => array( 'meta_key' => '_yoast_wpseo_redirect', 'type' => 'String' ),
		'gos_simple_redirect'     => array( 'meta_key' => 'gos_simple_redirect', 'type' => 'String' ),
	);

	public function register(): void {
		if ( function_exists( 'YoastSEO' ) || defined( 'WPGRAPHQL_YOAST_SEO_VERSION' ) ) {
			return;
		}
		add_action( 'graphql_register_types', array( $this, 'register_fields' ), 1000 );
	}

	public function register_fields(): void {
		$post_types = get_post_types( array( 'public' => true ), 'objects' );
		foreach ( $post_types as $post_type ) {
			if ( empty( $post_type->graphql_single_name ) ) {
				continue;
			}

			$type_name = $post_type->graphql_single_name;
			foreach ( $this->fields as $field_name => $field_config ) {
				SchemaRegistryGuards::register_field(
					$type_name,
					$field_name,
					array(
						'type'    => $field_config['type'],
						'resolve' => static function ( $post ) use ( $field_config ) {
							$post_id = isset( $post->databaseId ) ? (int) $post->databaseId : 0;
							if ( $post_id <= 0 && isset( $post->ID ) ) {
								$post_id = (int) $post->ID;
							}
							if ( $post_id <= 0 ) {
								return null;
							}
							$value = get_post_meta( $post_id, $field_config['meta_key'], true );
							if ( 'Boolean' === $field_config['type'] ) {
								return in_array( strtolower( (string) $value ), array( '1', 'true', 'yes', 'on' ), true );
							}
							return $value;
						},
					)
				);
			}
		}
	}
}
