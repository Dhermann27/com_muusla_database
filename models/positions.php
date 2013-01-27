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
class muusla_databaseModelpositions extends JModel
{
	function getPositions() {
		$db =& JFactory::getDBO();
		$query = "SELECT mp.positionid, mp.name, FORMAT(mp.registration_amount,2) regamount, FORMAT(mp.housing_amount,2) houseamount, mp.is_shown FROM muusa_positions mp, muusa_currentyear my WHERE mp.start_year <= my.year AND mp.end_year > my.year ORDER BY mp.name";
		$db->setQuery($query);
		return $db->loadObjectList();
	}

	function deletePosition($id) {
		$db =& JFactory::getDBO();
		$query = "DELETE FROM muusa_volunteers WHERE positionid = '$id'";
		$db->setQuery($query);
		$db->query();
		if($db->getErrorNum()) {
			JError::raiseError(500, $db->stderr());
		}

		$query = "DELETE FROM muusa_positions WHERE positionid = '$id'";
		$db->setQuery($query);
		$db->query();
		if($db->getErrorNum()) {
			JError::raiseError(500, $db->stderr());
		}
	}

	function updatePosition($id) {
		$db =& JFactory::getDBO();
		$user =& JFactory::getUser();
		$obj = new stdClass;
		$obj->positionid = $id;
		$obj->name = JRequest::getSafe("positionname-$id");
		$obj->registration_amount = JRequest::getSafe("positionregamount-$id");
		$obj->housing_amount = JRequest::getSafe("positionhouseamount-$id");
		if(JRequest::getSafe("positionshown-$id") == "1") {
			$obj->is_shown = 1;
		} else {
			$obj->is_shown = 0;
		}
		$obj->modified_by = $user->username;
		$obj->modified_at = date("Y-m-d H:i:s");
		$db->updateObject("muusa_positions", $obj, "positionid");
		if($db->getErrorNum()) {
			JError::raiseError(500, $db->stderr());
		}

	}

	function insertPosition() {
		$db =& JFactory::getDBO();
		$obj = new stdClass;
		$obj->name = JRequest::getSafe("positionname-0");
		$obj->programid = "1000";
		$obj->registration_amount = JRequest::getSafe("positionregamount-0");
		$obj->housing_amount = JRequest::getSafe("positionhouseamount-0");
		$obj->start_year = "1901";
		$obj->end_year = "2100";
		if(JRequest::getSafe("positionshown-0") == "1") {
			$obj->is_shown = 1;
		} else {
			$obj->is_shown = 0;
		}
		$db->insertObject("muusa_positions", $obj);
		if($db->getErrorNum()) {
			JError::raiseError(500, $db->stderr());
		}

	}

}