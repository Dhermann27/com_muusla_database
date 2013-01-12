<?php
/**
 * muusla_database Model for muusla_database Component
 *
 * @package    muusla_database
 * @subpackage Components
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport( 'joomla.application.component.model' );

/**
 * muusla_database Model
 *
 * @package    muusla_database
 * @subpackage Components
 */
class muusla_databaseModelevents extends JModel
{
	function getRooms() {
		$db =& JFactory::getDBO();
		$query = "SELECT mr.roomid roomid, mb.name buildingname, mr.roomnbr roomnbr FROM muusa_rooms mr, muusa_buildings mb WHERE mr.buildingid=mb.buildingid AND mr.is_workshop='1'";
		$db->setQuery($query);
		return $db->loadObjectList();
	}

	function getTimes() {
		$db =& JFactory::getDBO();
		$query = "SELECT timeid, name FROM muusa_times ORDER BY starttime";
		$db->setQuery($query);
		return $db->loadObjectList();
	}

	function getEvents() {
		$db =& JFactory::getDBO();
		$query = "SELECT eventid, roomid, timeid, name, subname, IF(su, ' checked', '') su, IF(m, ' checked', '') m, IF(t, ' checked', '') t, IF(w, ' checked', '') w, IF(th, ' checked', '') th, IF(f, ' checked', '') f, IF(sa, ' checked', '') sa, FORMAT(fee, 2) fee, capacity FROM muusa_events ORDER by timeid, name";
		$db->setQuery($query);
		return $db->loadObjectList();
	}

	function deleteEvent($event) {
		$db =& JFactory::getDBO();
		$query = "DELETE FROM muusa_attendees WHERE eventid = '$event->eventid'";
		$db->setQuery($query);
		$db->query();
		if($db->getErrorNum()) {
			JError::raiseError(500, $db->stderr());
		}

		$query = "DELETE FROM muusa_events WHERE eventid = '$event->eventid'";
		$db->setQuery($query);
		$db->query();
		if($db->getErrorNum()) {
			JError::raiseError(500, $db->stderr());
		}
	}

	function upsertEvent($event) {
		$db =& JFactory::getDBO();
		if($event->eventid < 1000) {
			$db->insertObject("muusa_events", $event);
		} else {
			$db->updateObject("muusa_events", $event, "eventid");
		}
		if($db->getErrorNum()) {
			JError::raiseError(500, $db->stderr());
		}
	}

}