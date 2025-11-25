<?php
/**
 * Class handles unit tests for gatherpress-activitypub.php
 *
 * @package GatherPress_ActivityPub
 * @since 1.0.0
 * @license AGPL-3.0-or-later
 */

namespace GatherPress_ActivityPub\Tests\Core;

use GatherPress\Tests\Base;

/**
 * Class Test_GatherPress_ActivityPub.
 */
class Test_GatherPress_ActivityPub extends Base {
	/**
	 * Check plugin version.
	 *
	 * @return void
	 */
	public function test_plugin_version(): void {
		$package_json = json_decode(
			file_get_contents( // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
				sprintf( '%s/package.json', GATHERPRESS_ACTIVITYPUB_CORE_PATH . '/../' )
			),
			true
		);

		$this->assertSame(
			$package_json['version'],
			GATHERPRESS_ACTIVITYPUB_VERSION,
			'Failed to assert version in gatherpress-activitypub.php matches version in package.json.'
		);
	}
}
