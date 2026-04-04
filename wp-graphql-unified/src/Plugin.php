<?php

namespace WPGraphQLUnified;

use WPGraphQLUnified\Admin\PluginAdmin;
use WPGraphQLUnified\Contracts\ModuleInterface;
use WPGraphQLUnified\Modules\AcfModule;
use WPGraphQLUnified\Modules\CoreWpGraphqlModule;
use WPGraphQLUnified\Modules\CptModule;
use WPGraphQLUnified\Modules\CptUiModule;
use WPGraphQLUnified\Modules\EnableAllPostTypesModule;
use WPGraphQLUnified\Modules\GutenbergModule;
use WPGraphQLUnified\Modules\JwtAuthModule;
use WPGraphQLUnified\Modules\MbRelationshipsModule;
use WPGraphQLUnified\Modules\MetaQueryModule;
use WPGraphQLUnified\Modules\TaxQueryModule;
use WPGraphQLUnified\Modules\TotalCountsModule;
use WPGraphQLUnified\Modules\WooCommerceModule;
use WPGraphQLUnified\Modules\WpGraphqlMetaModule;
use WPGraphQLUnified\Modules\SeoRouterModule;
use WPGraphQLUnified\Support\FeatureFlags;
use WPGraphQLUnified\Support\ModuleStatusReporter;

final class Plugin {
	private static ?Plugin $instance = null;

	/**
	 * Ordre d’enregistrement après `plugins_loaded` (dépendances schéma).
	 *
	 * @var list<string>
	 */
	private const REGISTER_ORDER = array(
		'cpt',
		'enable_all',
		'cpt_ui',
		'meta_query',
		'tax_query',
		'wpgraphql_meta',
		'total_counts',
		'mb_relationships',
		'seo',
		'acf',
		'gutenberg',
		'woo',
		'jwt',
	);

	/** @var array<string,ModuleInterface> */
	private array $modules = array();

	public static function instance(): self {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	public function boot(): void {
		add_action( 'plugins_loaded', array( $this, 'load_textdomain' ), 5 );

		$this->modules = $this->build_modules();
		if ( isset( $this->modules['core'] ) ) {
			$this->modules['core']->register();
		}

		add_action( 'plugins_loaded', array( $this, 'register_non_core_modules' ), 20 );
		add_action( 'admin_notices', array( ModuleStatusReporter::class, 'render_admin_notices' ) );
		PluginAdmin::register();
		/**
		 * Plugin initialisé (modules instanciés, hooks posés).
		 */
		do_action( 'wpgraphql_unified_booted', $this );
	}

	public function load_textdomain(): void {
		load_plugin_textdomain(
			'wp-graphql-unified',
			false,
			dirname( plugin_basename( WPGRAPHQL_UNIFIED_FILE ) ) . '/languages'
		);
	}

	public function register_non_core_modules(): void {
		foreach ( self::REGISTER_ORDER as $key ) {
			if ( isset( $this->modules[ $key ] ) ) {
				$this->modules[ $key ]->register();
			}
		}
	}

	/**
	 * Lignes pour l’écran d’état (Outils).
	 *
	 * @return list<array{id:string,flag:string,enabled:bool,description:string,env:string}>
	 */
	public static function admin_status_rows(): array {
		$map          = self::module_class_map();
		$descriptions = FeatureFlags::descriptions();
		$keys         = array_merge( array( 'core' ), self::REGISTER_ORDER );
		$rows         = array();

		foreach ( $keys as $key ) {
			if ( ! isset( $map[ $key ] ) ) {
				continue;
			}
			$flag = $map[ $key ]['flag'];
			$rows[] = array(
				'id'          => $key,
				'flag'        => $flag,
				'enabled'     => FeatureFlags::enabled( $flag ),
				'description' => $descriptions[ $flag ] ?? '',
				'env'         => self::admin_env_hint( $key ),
			);
		}

		return $rows;
	}

	private static function admin_env_hint( string $module_key ): string {
		switch ( $module_key ) {
			case 'core':
				return class_exists( 'WPGraphQL' )
					? __( 'WPGraphQL actif', 'wp-graphql-unified' )
					: __( 'WPGraphQL non chargé (vérifiez le bundle legacy)', 'wp-graphql-unified' );
			case 'woo':
				return class_exists( 'WooCommerce' )
					? __( 'WooCommerce détecté', 'wp-graphql-unified' )
					: __( 'WooCommerce absent — extensions WooGraphQL inactives sans Woo', 'wp-graphql-unified' );
			case 'acf':
				return function_exists( 'acf_get_field_groups' )
					? __( 'ACF détecté', 'wp-graphql-unified' )
					: __( 'ACF absent — module ACF sans effet utile', 'wp-graphql-unified' );
			case 'mb_relationships':
				return function_exists( 'mb_relationships_api' )
					? __( 'Meta Box Relationships détecté', 'wp-graphql-unified' )
					: __( 'Extension Meta Box Relationships absente', 'wp-graphql-unified' );
			case 'cpt_ui':
				return function_exists( 'cptui_get_post_type_data' )
					? __( 'Custom Post Type UI détecté', 'wp-graphql-unified' )
					: __( 'CPT UI absent — pont limité aux CPT enregistrés manuellement', 'wp-graphql-unified' );
			case 'seo':
				if ( function_exists( 'YoastSEO' ) ) {
					return __( 'Yoast SEO détecté (priorité schéma SEO)', 'wp-graphql-unified' );
				}
				return __( 'Pas de Yoast — fallback meta SEO embarqué si activé', 'wp-graphql-unified' );
			case 'gutenberg':
				return defined( 'WP_GRAPHQL_GUTENBERG_VERSION' )
					? __( 'Extension Gutenberg GraphQL active', 'wp-graphql-unified' )
					: __( 'Chargement depuis le bundle legacy au register', 'wp-graphql-unified' );
			case 'jwt':
				return __( 'JWT — dépend du bundle et des réglages serveur', 'wp-graphql-unified' );
			default:
				return '—';
		}
	}

	/**
	 * @return array<string,array{flag:string,class:class-string<ModuleInterface>}>
	 */
	private static function module_class_map(): array {
		return array(
			'core'             => array( 'flag' => 'core', 'class' => CoreWpGraphqlModule::class ),
			'cpt'              => array( 'flag' => 'cpt', 'class' => CptModule::class ),
			'enable_all'       => array( 'flag' => 'enable_all', 'class' => EnableAllPostTypesModule::class ),
			'cpt_ui'           => array( 'flag' => 'cpt_ui', 'class' => CptUiModule::class ),
			'meta_query'       => array( 'flag' => 'meta_query', 'class' => MetaQueryModule::class ),
			'tax_query'        => array( 'flag' => 'tax_query', 'class' => TaxQueryModule::class ),
			'wpgraphql_meta'   => array( 'flag' => 'meta', 'class' => WpGraphqlMetaModule::class ),
			'total_counts'     => array( 'flag' => 'total_counts', 'class' => TotalCountsModule::class ),
			'mb_relationships' => array( 'flag' => 'mb_relationships', 'class' => MbRelationshipsModule::class ),
			'seo'              => array( 'flag' => 'seo', 'class' => SeoRouterModule::class ),
			'acf'              => array( 'flag' => 'acf', 'class' => AcfModule::class ),
			'gutenberg'        => array( 'flag' => 'gutenberg', 'class' => GutenbergModule::class ),
			'woo'              => array( 'flag' => 'woo', 'class' => WooCommerceModule::class ),
			'jwt'              => array( 'flag' => 'jwt', 'class' => JwtAuthModule::class ),
		);
	}

	/**
	 * @return array<string,ModuleInterface>
	 */
	private function build_modules(): array {
		$modules = array();
		foreach ( self::module_class_map() as $key => $spec ) {
			if ( FeatureFlags::enabled( $spec['flag'] ) ) {
				$class           = $spec['class'];
				$modules[ $key ] = new $class();
			}
		}

		return $modules;
	}
}
