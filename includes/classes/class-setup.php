<?php
/**
 * Manages plugin setup for GatherPress ActivityPub.
 *
 * @package GatherPress_ActivityPub
 * @since 1.0.0
 * @license AGPL-3.0-or-later
 */

namespace GatherPress_ActivityPub;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit; // @codeCoverageIgnore

use GatherPress\Core\Traits\Singleton;
use GatherPress_ActivityPub\ActivityPub\Transformer\Event_Transformer;

/**
 * Class Setup.
 *
 * Manages plugin setup and initialization.
 */
class Setup {
	/**
	 * Enforces a single instance of this class.
	 */
	use Singleton;

	/**
	 * Constructor for the Setup class.
	 *
	 * Initializes and sets up various components of the plugin.
	 */
	protected function __construct() {
		$this->setup_hooks();
	}

	/**
	 * Set up hooks for various purposes.
	 *
	 * This method adds hooks for different purposes as needed.
	 *
	 * @return void
	 */
	protected function setup_hooks(): void {
		add_filter( 'gatherpress_sub_pages', array( $this, 'setup_sub_page' ) );
		add_filter( 'activitypub_transformer', array( $this, 'register_activitypub_transformer' ), 10, 3 );
		add_filter(
			sprintf(
				'plugin_action_links_%s/%s',
				basename( GATHERPRESS_ACTIVITYPUB_PATH ),
				basename( GATHERPRESS_ACTIVITYPUB_FILE )
			),
			array( $this, 'filter_plugin_action_links' )
		);
	}

	/**
	 * Adds a sub-page for GatherPress Alpha to the existing sub-pages array.
	 *
	 * This function modifies the provided sub-pages array to include a new sub-page
	 * for GatherPress Alpha with specified details such as name, priority, and sections.
	 *
	 * @param array $sub_pages An associative array of existing sub-pages.
	 * @return array Modified array of sub-pages including the GatherPress Alpha sub-page.
	 */
	public function setup_sub_page( array $sub_pages ): array {
		$sub_pages['activitypub'] = array(
			'name'     => __( 'ActivityPub', 'gatherpress-activitypub' ),
			'priority' => 10,
			'sections' => array(
				'awesome_it_works' => array(
					'name'        => __( 'ActivityPub', 'gatherpress-activitypub' ),
					'description' => __( 'GatherPress ActivityPub works!', 'gatherpress-activitypub' ),
				),
			),
		);

		return $sub_pages;
	}

	/**
	 * Add the custom transformers for the events and locations of several WordPress event plugins.
	 *
	 * @param \Activitypub\Transformer\Base $transformer  The transformer to use.
	 * @param mixed                         $data         The data to transform.
	 * @param string                        $object_class The class of the object to transform.
	 *
	 * @return \Activitypub\Transformer\Base|null|\WP_Error
	 */
	public function register_activitypub_transformer( $transformer, $data, $object_class ) {
		// If the current data object is a GatherPress WordPress event post.
		if ( 'WP_Post' === $object_class && \GatherPress\Core\Event::POST_TYPE === $data->post_type ) {
			return new Event_Transformer( $data );
		}

		// Return the default transformer.
		return $transformer;
	}

	/**
	 * Add custom links to the plugin action links in the WordPress plugins list.
	 *
	 * This method adds a 'Settings' link to the plugin's action links in the WordPress plugins list.
	 *
	 * @since 1.0.0
	 *
	 * @param array $actions An array of existing action links.
	 * @return array An updated array of action links, including the 'Settings' link.
	 */
	public function filter_plugin_action_links( array $actions ): array {
		return array_merge(
			array(
				'settings' => '<a href="' . esc_url( admin_url( 'edit.php?post_type=gatherpress_event&page=gatherpress_activitypub' ) ) . '">'
					. esc_html__( 'Settings', 'gatherpress-activitypub' ) . '</a>',
			),
			$actions
		);
	}
}
