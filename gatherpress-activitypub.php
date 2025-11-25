<?php
/**
 * Plugin Name:      GatherPress ActivityPub
 * Plugin URI:       https://event-federation.eu/gatherpress-activitypub
 * Description:      Adding ActivityPub support for GatherPress
 * Author:           André Menrath
 * Author URI:       https://graz.social/@linos
 * Version:          1.0.0
 * Requires PHP:     8.1
 * Text Domain:      gatherpress-activitypub
 * Domain Path:      /languages
 * License:          AGPL-3.0-or-later
 * License URI:      https://www.gnu.org/licenses/agpl-3.0.html
 * Requires Plugins: activitypub, gatherpress
 *
 * @package GatherPress_ActivityPub
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit; // @codeCoverageIgnore

// Constants.
define( 'GATHERPRESS_ACTIVITYPUB_VERSION', current( get_file_data( __FILE__, array( 'Version' ), 'plugin' ) ) );
define( 'GATHERPRESS_ACTIVITYPUB_CORE_PATH', __DIR__ );

/**
 * Adds the GatherPress_ActivityPub namespace to the autoloader.
 *
 * This function hooks into the 'gatherpress_autoloader' filter and adds the
 * GatherPress_ActivityPub namespace to the list of namespaces with its core path.
 *
 * @param array $namespace An associative array of namespaces and their paths.
 * @return array Modified array of namespaces and their paths.
 */
function gatherpress_activitypub_autoloader( array $namespace ): array {
	$namespace['GatherPress_ActivityPub'] = GATHERPRESS_ACTIVITYPUB_CORE_PATH;

	return $namespace;
}
add_filter( 'gatherpress_autoloader', 'gatherpress_activitypub_autoloader' );

/**
 * Initializes the GatherPress ActivityPub setup.
 *
 * This function hooks into the 'plugins_loaded' action to ensure that
 * the GatherPress_ActivityPub\Setup instance is created once all plugins are loaded,
 * only if the GatherPress plugin is active.
 *
 * @return void
 */
function gatherpress_activitypub_setup(): void {
	if ( defined( 'GATHERPRESS_VERSION' ) ) {
		GatherPress_ActivityPub\Setup::get_instance();
	}

}
add_action( 'plugins_loaded', 'gatherpress_activitypub_setup' );
