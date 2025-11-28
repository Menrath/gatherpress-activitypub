<?php
/**
 * ActivityPub Transformer for the GatherPress events.
 *
 * @package GatherPress_ActivityPub
 * @since 1.0.0
 * @license AGPL-3.0-or-later
 */

namespace GatherPress_ActivityPub\ActivityPub\Transformer;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit; // @codeCoverageIgnore

use Activitypub\Activity\Base_Object;
use Activitypub\Activity\Extended_Object\Place;
use Activitypub\Transformer\Post;
use GatherPress\Core\Event;
use GatherPress_ActivityPub\ActivityPub\Event_FEP_8a8e;
use WP_Error;

use function Activitypub\get_content_warning;

/**
 * Class GatherPress Event Transformer.
 *
 * Manages the transformation of a GatherPress event post to ActivityStreams.
 */
class Event_Transformer extends Post {
	/**
	 * The current GatherPress Event object.
	 *
	 * @var Event
	 */
	protected $gp_event;

	/**
	 * The current GatherPress Venue.
	 *
	 * @var array
	 */
	protected $gp_venue;

	/**
	 * Extend the constructor to also set the GatherPress objects.
	 *
	 * @param \WP_Post $item The WordPress object.
	 */
	public function __construct( $item ) {
		$this->gp_event = new Event( $item->ID );
		$this->gp_venue = $this->gp_event->get_venue_information();
	}

	/**
	 * Get the event location.
	 *
	 * @return ?Place The place objector null if not place set.
	 */
	public function get_location(): ?Place {
		$address = $this->gp_venue['full_address'];
		if ( $address ) {
			$place = new Place();
			$place->set_type( 'Place' );
			$place->set_name( $address );
			$place->set_address( $address );
			return $place;
		} else {
			return null;
		}
	}

	/**
	 * Get the end time from the event object.
	 */
	public function get_end_time(): string {
		return $this->gp_event->get_datetime_end( 'Y-m-d\TH:i:sP' );
	}

	/**
	 * Get the end time from the event object.
	 */
	public function get_start_time(): string {
		return $this->gp_event->get_datetime_start( 'Y-m-d\TH:i:sP' );
	}

	/**
	 * Get the event link from the events metadata.
	 */
	private function get_event_link(): array {
		$event_link = get_post_meta( $this->item->ID, 'event-link', true );
		if ( $event_link ) {
			return array(
				'type'      => 'Link',
				'name'      => 'Website',
				'href'      => \esc_url( $event_link ),
				'mediaType' => 'text/html',
			);
		}

		return array();
	}

	/**
	 * Overrides/extends the get_attachments function to also add the event Link.
	 */
	protected function get_attachment(): array {
		$attachments = parent::get_attachment();
		if ( count( $attachments ) ) {
			$attachments[0]['type'] = 'Document';
			$attachments[0]['name'] = 'Banner';
		}

		$event_link = $this->get_event_link();
		if ( $event_link ) {
			$attachments[] = $this->get_event_link();
		}

		return $attachments;
	}

	/**
	 * Determine whether the event is online.
	 *
	 * @return bool
	 */
	public function get_is_online(): bool {
		return $this->gp_event->maybe_get_online_event_link() ? true : false;
	}

	/**
	 * Prevents gatherpress blocks from being rendered for the content.
	 *
	 * @param mixed $block_content The blocks content.
	 * @param mixed $block         The block.
	 */
	public static function filter_gatherpress_blocks( $block_content, $block ) {
		// Check if the block name starts with 'gatherpress'.
		if ( isset( $block['blockName'] ) && 0 === strpos( $block['blockName'], 'gatherpress/' ) ) {
			return ''; // Skip rendering this block.
		}

		return $block_content; // Return the content for other blocks.
	}

	/**
	 * Transform to an the Event object.
	 *
	 * @return Base_Object|WP_Error
	 */
	public function to_object(): Base_Object|WP_Error {
		// Apply the filter for preventing the rendering off gatherpress blocks just in time.
		add_filter( 'render_block', array( self::class, 'filter_gatherpress_blocks' ), 10, 2 );

		// Transform all properties via getter functions.
		$object = new Event_FEP_8a8e();
		$object = $this->transform_object_properties( $object );

		if ( \is_wp_error( $object ) ) {
			return $object;
		}

		$this->set_audience( $object );

		remove_filter( 'render_block', array( self::class, 'filter_gatherpress_blocks' ) );

		// Maybe modify object for content warning.
		$content_warning = get_content_warning( $this->item );
		if ( ! empty( $content_warning ) ) {
			$object->set_sensitive( true );
			$object->set_summary( $content_warning );
			$object->set_summary_map( null );
			$object->set_dcterms( array( 'subject' => $content_warning ) );
		}

		return $object;
	}
}
