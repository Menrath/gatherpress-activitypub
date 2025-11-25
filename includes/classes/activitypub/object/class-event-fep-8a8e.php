<?php
/**
 * FEP-8a8e compatible representation of an Event in ActivityStreams.
 *
 * @package GatherPress_ActivityPub
 * @since 1.0.0
 * @license AGPL-3.0-or-later
 */

namespace GatherPress_ActivityPub\ActivityPub\Object;

use Activitypub\Activity\Base_Object;

/**
 * Event is an implementation of Activity Streams Event object type.
 *
 * This class contains extensions from FEP-8a8e: A common approach to using the Event object type.
 *
 * @see https://www.w3.org/TR/activitystreams-vocabulary/#dfn-event
 * @see https://w3id.org/fep/8a8ae
 *
 * @method int|null    get_maximum_attendee_capacity()       Gets how many places there can be for an event.
 * @method string|null get_name()                            Gets the title of the event.
 * @method string|null get_event_status()                    Gets the event's status.
 * @method string|null get_timezone()                        Gets the timezone of the event.
 *
 * @method Event set_actor( string $actor )                      Sets which actor created the event.
 * @method Event set_maximum_attendee_capacity( int $capacity )  Sets how many places there can be for an event.
 * @method Event set_name( string $name )                        Sets the title of the event.
 * @method Event set_event_status( string $status )              Sets the event's status.
 * @method Event set_timezone( string $timezone )                Sets the timezone of the event.
 */
class Event_FEP_8a8e extends Base_Object {
	/**
	 * The JSON-LD Context.
	 *
	 * @var array
	 */
	const JSON_LD_CONTEXT = array(
		'https://schema.org/',                   // The base context is schema.org, because it is used a lot.
        'https://w3id.org/fep/8a8e',             // FEP-8a8e context.
		'https://www.w3.org/ns/activitystreams', // The ActivityStreams context overrides everything also defined in schema.org.
	);

	/**
	 * Event is an implementation of one of the Activity Streams.
	 *
	 * @var string
	 */
	protected $type = 'Event';

	/**
	 * The title of the event.
	 *
	 * @var string
	 */
	protected $name;

	/**
	 * Timezone of the event.
	 *
	 * @context https://w3id.org/fep/8a8e/timezone
	 * @var string
	 */
	protected $timezone;

	/**
	 * The event's status.
	 *
	 * @context https://w3id.org/fep/8a8e/eventStatus
	 * @var string
	 */
	protected $event_status = 'EventScheduled';

	/**
	 * How many places there can be for an event.
	 *
	 * @context https://schema.org/maximumAttendeeCapacity
	 * @var int
	 */
	protected $maximum_attendee_capacity;
}
