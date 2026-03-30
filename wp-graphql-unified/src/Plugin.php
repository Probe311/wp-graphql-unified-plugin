<?php

namespace WPGraphQLUnified;

use WPGraphQLUnified\Contracts\ModuleInterface;
use WPGraphQLUnified\Modules\AcfModule;
use WPGraphQLUnified\Modules\CoreWpGraphqlModule;
use WPGraphQLUnified\Modules\GoogleSchemaSeoModule;
use WPGraphQLUnified\Modules\GutenbergModule;
use WPGraphQLUnified\Modules\JwtAuthModule;
use WPGraphQLUnified\Modules\WooCommerceModule;
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
		$order = array( 'acf', 'gutenberg', 'woo', 'jwt', 'seo' );
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
		if ( FeatureFlags::enabled( 'seo' ) ) {
			$modules['seo'] = new GoogleSchemaSeoModule();
		}

		return $modules;
	}
}
