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
class muusla_databaseModelcampers extends JModel
{

	function getAllCampers() {
		$db =& JFactory::getDBO();
		$query = "SELECT camperid, hohid, firstname, lastname, city, statecd FROM muusa_campers ORDER BY lastname, firstname, statecd, city";
		$db->setQuery($query);
		return $db->loadObjectList();
	}

	function getHeads() {
		$db =& JFactory::getDBO();
		$query = "SELECT camperid, firstname, lastname, city, statecd FROM muusa_campers WHERE hohid=0 ORDER BY lastname, firstname, statecd, city";
		$db->setQuery($query);
		return $db->loadObjectList();
	}

	function getBuildings() {
		$db =& JFactory::getDBO();
		$query = "SELECT buildingid, name FROM muusa_buildings ORDER BY name";
		$db->setQuery($query);
		return $db->loadObjectList();
	}

	function getStates() {
		$db =& JFactory::getDBO();
		$query = "SELECT statecd, name FROM muusa_states ORDER BY name";
		$db->setQuery($query);
		return $db->loadObjectList();
	}

	function getFoodoptions() {
		$db =& JFactory::getDBO();
		$query = "SELECT foodoptionid, name FROM muusa_foodoptions ORDER BY foodoptionid";
		$db->setQuery($query);
		return $db->loadObjectList();
	}

	function getChurches() {
		$db =& JFactory::getDBO();
		$query = "SELECT churchid, name, city, statecd FROM muusa_churches ORDER BY statecd, city, name";
		$db->setQuery($query);
		return $db->loadObjectList();
	}

	function getPhonetypes() {
		$db =& JFactory::getDBO();
		$query = "SELECT phonetypeid, name FROM muusa_phonetypes";
		$db->setQuery($query);
		return $db->loadObjectList();
	}

	function getCamper($camperid) {
		$db =& JFactory::getDBO();
		$query = "SELECT mc.camperid, mc.hohid, mc.firstname, mc.lastname, mc.sexcd, mc.address1, mc.address2, mc.city, mc.statecd, mc.zipcd, mc.country, ";
		$query .= "mc.email, DATE_FORMAT(mc.birthdate, '%m/%d/%Y') birthdate, muusa_age_f(mc.birthdate) age, mc.gradeoffset, ";
		$query .= "mc.roomprefid1, mc.roomprefid2, mc.roomprefid3, mc.matepref1, mc.matepref2, mc.matepref3, mc.sponsor, IF(mc.is_handicap, ' checked', '') is_handicap, IF(mc.is_ymca,' checked', '') is_ymca, IF(mc.is_ecomm,' checked', '') is_ecomm, ";
		$query .= "mc.foodoptionid, mc.churchid, mc.programid, CONCAT(mb.name, ' ', mv.roomnbr) room ";
		$query .= "FROM muusa_campers mc LEFT JOIN (muusa_campers_v mv, muusa_rooms mr, muusa_buildings mb) ON mc.camperid=mv.camperid AND mv.roomid=mr.roomid AND mr.buildingid=mb.buildingid WHERE mc.camperid=$camperid";
		$db->setQuery($query);
		return $db->loadObject();
	}

	function getChildren($hohid) {
		$db =& JFactory::getDBO();
		$query = "SELECT mc.camperid, mc.firstname, mc.lastname, mc.sexcd, mc.email, DATE_FORMAT(mc.birthdate, '%m/%d/%Y') birthdate, mc.gradeoffset, muusa_age_f(mc.birthdate) age, ";
		$query .= "IF(mc.is_handicap, ' checked', '') is_handicap, mc.foodoptionid FROM muusa_campers mc WHERE mc.hohid=$hohid ORDER BY birthdate DESC";
		$db->setQuery($query);
		return $db->loadObjectList();
	}

	function getPhonenumbers($id) {
		$db =& JFactory::getDBO();
		if($id != 0) {
			$query = "SELECT mp.phonenbrid, mt.phonetypeid phonetypeid, mt.name phonetypename, CONCAT(left(mp.phonenbr,3) , '-' , mid(mp.phonenbr,4,3) , '-', right(mp.phonenbr,4)) phonenbr FROM muusa_phonenumbers mp, muusa_phonetypes mt WHERE mp.phonetypeid=mt.phonetypeid AND camperid=$id ORDER BY phonenbrid";
			$db->setQuery($query);
			return $db->loadObjectList();
		} else {
			return array();
		}
	}

	function getVolunteers() {
		$db =& JFactory::getDBO();
		$query = "SELECT camperid, positionid FROM muusa_positions_v WHERE camperid=$camperid OR hohid=$camperid";
		$db->setQuery($query);
		return $db->loadObjectList();
	}

	function upsertCamper($obj) {
		$db =& JFactory::getDBO();
		$obj->gradeoffset = "&&$obj->grade-muusa_age_f(STR_TO_DATE('$obj->birthdate', '%m/%d/%Y'))";
		$obj->programid = "&&muusa_programs_id_f(STR_TO_DATE('$obj->birthdate', '%m/%d/%Y'), ($obj->grade-muusa_age_f(STR_TO_DATE('$obj->birthdate', '%m/%d/%Y'))))";
		$obj->birthdate = "&&STR_TO_DATE('$obj->birthdate', '%m/%d/%Y')";
		unset($obj->grade);
		if($obj->camperid < 1000) {
			unset($obj->camperid);
			$db->insertObject("muusa_campers", $obj, "camperid");
		} else {
			$db->updateObject("muusa_campers", $obj, "camperid");
		}
		return $obj->camperid;
	}

	function deletePhonenumber($id) {
		$db =& JFactory::getDBO();
		$query = "DELETE FROM muusa_phonenumbers WHERE phonenbrid=$id";
		$db->setQuery($query);
		$db->query();
		if($db->getErrorNum()) {
			JError::raiseError(500, $db->stderr());
		}
	}

	function upsertPhonenumber($obj) {
		$phone = array('/\\(/', '/ /', '/\\)/', '/-/');
		$db =& JFactory::getDBO();
		$obj->phonenbr = preg_replace($phone, "", $obj->phonenbr);
		if($obj->phonenbrid < 1000) {
			unset($obj->phonenbrid);
			$db->insertObject("muusa_phonenumbers", $obj);
		} else {
			$db->updateObject("muusa_phonenumbers", $obj, "phonenbrid");
		}
	}

	function deleteCamper($id) {
		$db =& JFactory::getDBO();
		$query = "DELETE FROM muusa_attendees WHERE camperid = $id";
		$db->setQuery($query);
		$db->query();
		if($db->getErrorNum()) {
			JError::raiseError(500, $db->stderr());
		}

		$query = "DELETE FROM muusa_volunteers WHERE camperid = $id";
		$db->setQuery($query);
		$db->query();
		if($db->getErrorNum()) {
			JError::raiseError(500, $db->stderr());
		}
			
		$query = "DELETE FROM muusa_fiscalyear WHERE camperid = $id";
		$db->setQuery($query);
		$db->query();
		if($db->getErrorNum()) {
			JError::raiseError(500, $db->stderr());
		}

		$query = "DELETE FROM muusa_charges WHERE camperid = $id";
		$db->setQuery($query);
		$db->query();
		if($db->getErrorNum()) {
			JError::raiseError(500, $db->stderr());
		}

		$user =& JFactory::getUser();
		$query = "UPDATE muusa_campers set hohid = NULL, modified_by='$user->username', modified_at=CURRENT_TIMESTAMP WHERE hohid = $id";
		$db->setQuery($query);
		$db->query();
		if($db->getErrorNum()) {
			JError::raiseError(500, $db->stderr());
		}

		$query = "DELETE FROM muusa_phonenumbers WHERE camperid = $id";
		$db->setQuery($query);
		$db->query();
		if($db->getErrorNum()) {
			JError::raiseError(500, $db->stderr());
		}

		$query = "DELETE FROM muusa_campers WHERE camperid = $id";
		$db->setQuery($query);
		$db->query();
		if($db->getErrorNum()) {
			JError::raiseError(500, $db->stderr());
		}
	}
}
