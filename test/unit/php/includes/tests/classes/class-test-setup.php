<?php
/**
 * Class handles unit tests for GatherPress_ActivityPub\Setup.
 *
 * @package GatherPress_ActivityPub
 * @since 1.0.0
 * @license AGPL-3.0-or-later
 */

namespace GatherPress_ActivityPub\Tests\Core;

use GatherPress_ActivityPub\Tests\Base;
use GatherPress_ActivityPub\Setup;

/**
 * Class Test_Setup.
 *
 * @coversDefaultClass \GatherPress_ActivityPub\Setup
 */
class Test_Setup extends Base {
	/**
	 * Coverage for setup_hooks.
	 *
	 * @covers ::__construct
	 * @covers ::instantiate_classes
	 * @covers ::setup_hooks
	 *
	 * @return void
	 */
	public function test_setup_hooks(): void {
		$instance = Setup::get_instance();
		$hooks    = array(
			array(
				'type'     => 'filter',
				'name'     => 'gatherpress_sub_pages',
				'priority' => 10,
				'callback' => array( $instance, 'setup_sub_page' ),
			),
			array(
				'type'     => 'filter',
				'name'     => 'activitypub_transformer',
				'priority' => 10,
				'callback' => array( $instance, 'register_activitypub_transformer' ),
			),
			array(
				'type'     => 'filter',
				'name'     => sprintf( 'plugin_action_links_%s/%s', basename( GATHERPRESS_ACTIVITYPUB_PATH ), basename( GATHERPRESS_ACTIVITYPUB_FILE ) ),
				'priority' => 10,
				'callback' => array( $instance, 'filter_plugin_action_links' ),
			),
		);

		$this->assert_hooks( $hooks, $instance );
	}
}
