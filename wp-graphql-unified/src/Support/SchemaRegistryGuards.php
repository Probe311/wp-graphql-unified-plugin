<?php

namespace WPGraphQLUnified\Support;

final class SchemaRegistryGuards {
	/**
	 * @param array<string,mixed> $config
	 */
	public static function register_field( string $type_name, string $field_name, array $config ): void {
		if ( function_exists( 'register_graphql_field' ) && ! self::field_exists( $type_name, $field_name ) ) {
			register_graphql_field( $type_name, $field_name, $config );
		}
	}

	/**
	 * @param array<string,mixed> $config
	 */
	public static function register_type( string $type_name, array $config ): void {
		if ( function_exists( 'register_graphql_object_type' ) && ! self::type_exists( $type_name ) ) {
			register_graphql_object_type( $type_name, $config );
		}
	}

	/**
	 * @param array<string,mixed> $config
	 */
	public static function register_mutation( string $mutation_name, array $config ): void {
		if ( function_exists( 'register_graphql_mutation' ) && ! self::field_exists( 'RootMutation', $mutation_name ) ) {
			register_graphql_mutation( $mutation_name, $config );
		}
	}

	/**
	 * @param array<string,mixed> $config
	 */
	public static function register_connection( array $config ): void {
		if ( ! function_exists( 'register_graphql_connection' ) ) {
			return;
		}

		$from_field = $config['fromFieldName'] ?? '';
		$from_type  = $config['fromType'] ?? '';
		if ( is_string( $from_field ) && is_string( $from_type ) && '' !== $from_field && '' !== $from_type && self::field_exists( $from_type, $from_field ) ) {
			return;
		}

		register_graphql_connection( $config );
	}

	public static function type_exists( string $type_name ): bool {
		$schema = self::schema();
		return $schema ? null !== $schema->get_type( $type_name ) : false;
	}

	public static function field_exists( string $type_name, string $field_name ): bool {
		$schema = self::schema();
		if ( ! $schema ) {
			return false;
		}

		$type = $schema->get_type( $type_name );
		if ( ! $type || ! method_exists( $type, 'getFields' ) ) {
			return false;
		}

		$fields = $type->getFields();
		return is_array( $fields ) && array_key_exists( $field_name, $fields );
	}

	private static function schema() {
		if ( class_exists( '\WPGraphQL' ) && method_exists( '\WPGraphQL', 'get_schema' ) ) {
			return \WPGraphQL::get_schema();
		}

		return null;
	}
}
