<?php
/**
 * PHPUnit bootstrap file.
 *
 * @package GatherPress_ActivityPub
 * @subpackage Tests
 * @since 1.0.0
 */

// phpcs:disable Squiz.Commenting.FileComment.Missing

$gatherpress_activitypub_bootstrap_instance = PMC\Unit_Test\Bootstrap::get_instance();

tests_add_filter(
	'muplugins_loaded',
	static function () {
		echo ABSPATH;
		// Manually load needed plugins this plugins depends on, so that we can access their classes.
		require_once ABSPATH . '/wp-content/plugins/gatherpress/gatherpress.php';
		require_once ABSPATH . '/wp-content/plugins/activitypub/activitypub.php';
		// Manually load our plugin without having to setup the development folder in the correct plugin folder.
		require_once __DIR__ . '/../../../gatherpress-activitypub.php';
	}
);

tests_add_filter(
	'gatherpress_autoloader',
	static function ( array $namespaces ): array {
		$namespaces['GatherPress\Tests']             = GATHERPRESS_CORE_PATH . '/test/unit/php/';
		$namespaces['GatherPress_ActivityPub\Tests'] = __DIR__;

		return $namespaces;
	}
);

$gatherpress_activitypub_bootstrap_instance->start();
