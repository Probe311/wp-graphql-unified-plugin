<?php

namespace WPGraphQLUnified;

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

	/** @var array<string,ModuleInterface> */
	private array $modules = array();

	public static function instance(): self {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	public function boot(): void {
		$this->modules = $this->build_modules();
		if ( isset( $this->modules['core'] ) ) {
			$this->modules['core']->register();
		}

		add_action( 'plugins_loaded', array( $this, 'register_non_core_modules' ), 20 );
		add_action( 'admin_notices', array( ModuleStatusReporter::class, 'render_admin_notices' ) );
	}

	public function register_non_core_modules(): void {
		$order = array(
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
		foreach ( $order as $key ) {
			if ( isset( $this->modules[ $key ] ) ) {
				$this->modules[ $key ]->register();
			}
		}
	}

	/**
	 * @return array<string,ModuleInterface>
	 */
	private function build_modules(): array {
		$modules = array();

		if ( FeatureFlags::enabled( 'core' ) ) {
			$modules['core'] = new CoreWpGraphqlModule();
		}
		if ( FeatureFlags::enabled( 'cpt' ) ) {
			$modules['cpt'] = new CptModule();
		}
		if ( FeatureFlags::enabled( 'enable_all' ) ) {
			$modules['enable_all'] = new EnableAllPostTypesModule();
		}
		if ( FeatureFlags::enabled( 'cpt_ui' ) ) {
			$modules['cpt_ui'] = new CptUiModule();
		}
		if ( FeatureFlags::enabled( 'meta_query' ) ) {
			$modules['meta_query'] = new MetaQueryModule();
		}
		if ( FeatureFlags::enabled( 'tax_query' ) ) {
			$modules['tax_query'] = new TaxQueryModule();
		}
		if ( FeatureFlags::enabled( 'meta' ) ) {
			$modules['wpgraphql_meta'] = new WpGraphqlMetaModule();
		}
		if ( FeatureFlags::enabled( 'total_counts' ) ) {
			$modules['total_counts'] = new TotalCountsModule();
		}
		if ( FeatureFlags::enabled( 'mb_relationships' ) ) {
			$modules['mb_relationships'] = new MbRelationshipsModule();
		}
		if ( FeatureFlags::enabled( 'seo' ) ) {
			$modules['seo'] = new SeoRouterModule();
		}
		if ( FeatureFlags::enabled( 'acf' ) ) {
			$modules['acf'] = new AcfModule();
		}
		if ( FeatureFlags::enabled( 'gutenberg' ) ) {
			$modules['gutenberg'] = new GutenbergModule();
		}
		if ( FeatureFlags::enabled( 'woo' ) ) {
			$modules['woo'] = new WooCommerceModule();
		}
		if ( FeatureFlags::enabled( 'jwt' ) ) {
			$modules['jwt'] = new JwtAuthModule();
		}

		return $modules;
	}
}
