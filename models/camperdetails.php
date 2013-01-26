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
class muusla_databaseModelcamperdetails extends JModel
{

	function getYear() {
		$db =& JFactory::getDBO();
		$query = "SELECT year FROM muusa_currentyear";
		$db->setQuery($query);
		return $db->loadResult();
	}

	function getAllCampers() {
		$db =& JFactory::getDBO();
		$query = "SELECT camperid, hohid, firstname, lastname, city, statecd FROM muusa_campers ORDER BY lastname, firstname, statecd, city";
		$db->setQuery($query);
		return $db->loadObjectList();
	}

	function getCamper($camperid) {
		$db =& JFactory::getDBO();
		$user =& JFactory::getUser();
		$query = "SELECT camperid, hohid, CONCAT(firstname, ' ', lastname) fullname, mp.name programname FROM muusa_campers mc, muusa_programs mp WHERE mc.programid=mp.programid AND mc.camperid=$camperid";
		$db->setQuery($query);
		$camper = $db->loadObject();

		$query = "SELECT eventid, choicenbr, is_leader FROM muusa_attendees WHERE camperid=$camperid ORDER BY choicenbr";
		$db->setQuery($query);
		$camper->choices = $db->loadObjectList();
		if($db->getErrorNum()) {
			JError::raiseError(500, $db->stderr());
		}
		if($camper->programname == "Adult" || preg_match("/^Young Adult/", $camper->programname) != 0) {
			$camper->workshop = "0";
		} else {
			$camper->workshop = "Automatically enrolled in " . $camper->programname . " programming.";
		}
		return $camper;
	}

	function getFiscalyears() {
		$db =& JFactory::getDBO();
		$query = "SELECT mf.fiscalyearid, mf.fiscalyear, DATE_FORMAT(mf.postmark, '%m/%d/%Y') postmark, mf.days, mf.roomid, IFNULL(CONCAT(mb.name, ' ', mr.roomnbr),'No Data') roomnbr FROM muusa_fiscalyear mf LEFT JOIN (muusa_rooms mr, muusa_buildings mb) ON mf.roomid=mr.roomid AND mr.buildingid=mb.buildingid WHERE mf.camperid=" . JRequest::getSafe("editcamper") . " ORDER BY fiscalyear";
		$db->setQuery($query);
		return $db->loadObjectList();
	}

	function getRooms() {
		$db =& JFactory::getDBO();
		$query = "SELECT mr.roomid roomid, mb.buildingid buildingid, mb.name buildingname, mr.roomnbr roomnbr, mr.capacity capacity, mr.is_handicap is_handicap, (SELECT COUNT(*) FROM muusa_fiscalyear mf, muusa_currentyear my WHERE mf.roomid = mr.roomid AND mf.fiscalyear=my.year) current FROM muusa_buildings mb LEFT OUTER JOIN muusa_rooms mr ON mr.buildingid=mb.buildingid WHERE mr.is_workshop=0 ORDER BY mb.buildingid, mr.roomnbr";
		$db->setQuery($query);
		$results = $db->loadObjectList();
		$buildings = array();
		$counter = -1;
		foreach($results as $result) {
			if($counter == -1 || $buildings[$counter]->buildingid != $result->buildingid) {
				$building = new stdClass;
				$building->buildingid = $result->buildingid;
				$building->buildingname = $result->buildingname;
				$building->rooms = array();
				$buildings[++$counter] = $building;
			}
			if($result->roomid) {
				$room = new stdClass;
				$room->roomid = $result->roomid;
				$room->roomnbr = $result->roomnbr;
				$room->current = $result->current;
				$room->capacity = $result->capacity;
				$room->is_handicap = $result->is_handicap;
				$buildings[$counter]->rooms[] = $room;
			}
		}
		return $buildings;
	}

	function getPositions($camperid) {
		$db =& JFactory::getDBO();
		$query = "SELECT mp.positionid, mp.name, (IF(mv.camperid,' selected','')) selected FROM muusa_positions mp LEFT JOIN muusa_positions_v mv ON mp.positionid=mv.positionid AND mv.camperid=$camperid ORDER BY name";
		$db->setQuery($query);
		return $db->loadObjectList();
	}

	function getWorkshops() {
		$db =& JFactory::getDBO();
		$query = "SELECT mt.name timename, mt.timeid, CONCAT(IF(me.su,'S',''),IF(me.m,'M',''),IF(me.t,'Tu',''),IF(me.w,'W',''),IF(me.th,'Th',''),IF(me.f,'F',''),IF(me.sa,'S','')) days, me.eventid eventid, me.name shopname FROM muusa_events me, muusa_times mt WHERE me.timeid=mt.timeid AND mt.length > 0 ORDER BY mt.starttime, me.name";
		$db->setQuery($query);
		return $db->loadObjectList();
	}

	function upsertVolunteers($id, $obj) {
		$db =& JFactory::getDBO();
		$user =& JFactory::getUser();
		$query = "DELETE FROM muusa_volunteers WHERE camperid=$id AND fiscalyear=(SELECT year FROM muusa_currentyear)";
		if($obj->positionid != 0 && count($obj->positionid) > 0) {
			$query .= " AND positionid NOT IN (" . implode(",", $obj->positionid) . ")";
		}
		$db->setQuery($query);
		$db->query();
		if($db->getErrorNum()) {
			JError::raiseError(500, $db->stderr());
		}

		if($obj->positionid != 0 && count($obj->positionid) > 0) {
			$query = "INSERT INTO muusa_volunteers (camperid, positionid, fiscalyear, created_by, created_at) ";
			$query .= "SELECT $id, positionid, (SELECT year FROM muusa_currentyear), '$obj->created_by', '$obj->created_at' FROM muusa_positions ";
			$query .= "WHERE positionid NOT IN (SELECT positionid FROM muusa_positions_v WHERE camperid=$id) ";
			$query .= "AND positionid IN (" . implode(",", $obj->positionid) . ")";
			$db->setQuery($query);
			$db->query();
			if($db->getErrorNum()) {
				JError::raiseError(500, $db->stderr());
			}
		}
	}

	function upsertAttendees($camperid, $events) {
		$db =& JFactory::getDBO();
		$user =& JFactory::getUser();
		$query = "DELETE FROM muusa_attendees WHERE camperid=$camperid AND is_leader=0";
		$db->setQuery($query);
		$db->query();
		if($db->getErrorNum()) {
			JError::raiseError(500, $db->stderr());
		}

		foreach($events as $event) {
			for($i=0; $i<count($event->shops); $i++) {
				if($event->shops[$i] != "LEAD") {
					$obj = new stdClass;
					$obj->eventid = $event->shops[$i];
					$obj->camperid = $camperid;
					$obj->choicenbr = $i+1;
					$obj->is_leader = $event->leader;
					$obj->created_by = $user->username;
					$obj->created_at = date("Y-m-d H:i:s");
					$db->insertObject("muusa_attendees", $obj);
					if($db->getErrorNum()) {
						JError::raiseError(500, $db->stderr());
					}
				}
			}
		}
	}

	function getCharges($camperid) {
		$db =& JFactory::getDBO();
		$query = "SELECT mc.chargeid, mc.camperid camperid, mt.name name, mc.amount, mc.fiscalyear, DATE_FORMAT(mc.timestamp, '%m/%d/%Y') timestamp, mc.chargetypeid chargetypeid, mc.memo memo FROM muusa_charges mc, muusa_chargetypes mt WHERE mc.chargetypeid=mt.chargetypeid AND (mc.camperid=$camperid OR mc.camperid IN (SELECT camperid FROM muusa_campers mh WHERE mh.hohid=$camperid)) ORDER BY mc.timestamp, mc.chargetypeid, mc.camperid";
		$db->setQuery($query);
		return $db->loadObjectList();
	}

	function getCredits($camperid) {
		$db =& JFactory::getDBO();
		$query = "SELECT mv.camperid, IF(COUNT(mp.housing_amount)=1,mp.name,'Multiple Credits') name, mv.fiscalyear, IF(SUM(mp.registration_amount)>mr.amount, mr.amount, SUM(mp.registration_amount)) registration_amount, IF(mh.amount>0,IF(SUM(mp.housing_amount)>mh.amount,mh.amount,SUM(mp.housing_amount)),IF(SUM(mp.housing_amount)>50,50,SUM(mp.housing_amount))) housing_amount FROM (muusa_positions mp, muusa_volunteers mv) LEFT JOIN muusa_charges mr ON mv.camperid=mr.camperid AND mr.chargetypeid=1003 AND mv.fiscalyear=mr.fiscalyear LEFT JOIN muusa_charges mh ON mv.camperid = mh.camperid AND mh.chargetypeid=1000 AND mv.fiscalyear=mh.fiscalyear WHERE (mp.housing_amount>0 OR mp.registration_amount>0) AND mp.positionid = mv.positionid AND (mv.camperid=$camperid OR mv.camperid IN (SELECT camperid FROM muusa_campers mh WHERE mh.hohid=$camperid)) GROUP BY mv.fiscalyear, mv.camperid";
		$db->setQuery($query);
		return $db->loadObjectList();
	}

	function calculateCharges($camperid) {
		$db =& JFactory::getDBO();
		$user =& JFactory::getUser();
		$query = "SELECT mc.camperid, mc.firstname, mc.lastname, mc.hohid, DATE_FORMAT(mc.birthdate, '%m/%d/%Y') birthdate, muusa_age_f(mc.birthdate) age, mc.gradeoffset, IFNULL(mv.roomid,0) roomid FROM muusa_campers mc LEFT JOIN muusa_campers_v mv ON mc.camperid=mv.camperid WHERE mc.camperid=$camperid";
		$db->setQuery($query);
		$camper = $db->loadObject();
		if($db->getErrorNum()) {
			JError::raiseError(500, $db->stderr());
		}

		if($camper->camperid != 0 && $camper->hohid == 0) {
			$query = "DELETE FROM muusa_charges WHERE fiscalyear=(SELECT year FROM muusa_currentyear) AND camperid IN (SELECT camperid FROM muusa_campers_v WHERE camperid=$camper->camperid OR hohid=$camper->camperid) AND chargetypeid IN (1003, 1004, 1011)"; // 1012
			$db->setQuery($query);
			$db->query();
			if($db->getErrorNum()) {
				JError::raiseError(500, $db->stderr());
			}

			$obj = new stdClass;
			$obj->camperid = $camper->camperid;
			$obj->amount = "&&muusa_programs_fee_f(STR_TO_DATE('$camper->birthdate', '%m/%d/%Y'), $camper->gradeoffset)";
			$obj->memo = $camper->firstname . " " . $camper->lastname;
			$obj->chargetypeid = "&&(SELECT chargetypeid FROM muusa_chargetypes WHERE name LIKE 'Registration%')";
				$obj->timestamp = date("Y-m-d");
			$obj->fiscalyear = "&&(SELECT year FROM muusa_currentyear)";
			$obj->created_by = $user->username;
			$obj->created_at = date("Y-m-d H:i:s");
			$db->insertObject("muusa_charges", $obj);
			if($db->getErrorNum()) {
				JError::raiseError(500, $db->stderr());
			}
			$housingdepo = 50;

			$query = "SELECT camperid, firstname, lastname, birthdate, age, gradeoffset FROM muusa_campers_v WHERE hohid=$camper->camperid";
			$db->setQuery($query);
			$children = $db->loadObjectList();
			if($db->getErrorNum()) {
				JError::raiseError(500, $db->stderr());
			}

			foreach($children as $child) {
				$obj = new stdClass;
				$obj->camperid = $child->camperid;
				$obj->amount = "&&muusa_programs_fee_f(STR_TO_DATE('$child->birthdate', '%m/%d/%Y'), $child->gradeoffset)";
				$obj->memo = $child->firstname . " " . $child->lastname;
				$obj->chargetypeid = "&&(SELECT chargetypeid FROM muusa_chargetypes WHERE name LIKE 'Registration%')";
				$obj->timestamp = date("Y-m-d");
				$obj->fiscalyear = "&&(SELECT year FROM muusa_currentyear)";
				$obj->created_by = $user->username;
				$obj->created_at = date("Y-m-d H:i:s");
				$db->insertObject("muusa_charges", $obj);
				if($db->getErrorNum()) {
					JError::raiseError(500, $db->stderr());
				}
				if($child->age > 16 || ($child->age+$child->gradeoffset) > 6) {
					$housingdepo += 50;
				}
			}

			if($camper->roomid == 0) {
				$obj = new stdClass;
				$obj->camperid = $camper->camperid;
				$obj->amount = $housingdepo;
				$obj->memo = "Housing Deposit";
				$obj->chargetypeid = "&&(SELECT chargetypeid FROM muusa_chargetypes WHERE name LIKE 'Housing Depo%')";
				$obj->timestamp = date("Y-m-d");
				$obj->fiscalyear = "&&(SELECT year FROM muusa_currentyear)";
				$obj->created_by = $user->username;
				$obj->created_at = date("Y-m-d H:i:s");
				$db->insertObject("muusa_charges", $obj);
				if($db->getErrorNum()) {
					JError::raiseError(500, $db->stderr());
				}
			}
		}
		return $camper;
	}

	function getAttendees() {
		$db =& JFactory::getDBO();
		$query = "SELECT eventid, choicenbr, is_leader FROM muusa_attendees WHERE camperid=" . JRequest::getSafe("editcamper");
		$db->setQuery($query);
		return $db->loadObjectList();
	}

	function getChargetypes() {
		$db =& JFactory::getDBO();
		$query = "SELECT chargetypeid, name FROM muusa_chargetypes ORDER BY name";
		$db->setQuery($query);
		return $db->loadObjectList();
	}

	function upsertFiscalyear($id, $obj) {
		$db =& JFactory::getDBO();

		$query = "SELECT roomid FROM muusa_fiscalyear WHERE fiscalyearid=$id";
		$db->setQuery($query);
		$oldroom = $db->loadResult();
		if($db->getErrorNum()) {
			JError::raiseError(500, $db->stderr());
		}

		$obj->postmark = ($obj->postmark) ? "&&STR_TO_DATE('$obj->postmark', '%m/%d/%Y')" : "&&CURRENT_DATE";
		if($id == 0) {
			$db->insertObject("muusa_fiscalyear", $obj, "fiscalyearid");
		} else {
			$obj->fiscalyearid = $id;
			$db->updateObject("muusa_fiscalyear", $obj, "fiscalyearid");
		}

		if($oldroom != 0) {
			$this->updateHousing($oldroom);
		}
		if($obj->roomid != 0) {
			$this->updateHousing($obj->roomid);
		} else {
			$query = "DELETE FROM muusa_charges WHERE camperid=(SELECT camperid FROM muusa_fiscalyear WHERE fiscalyearid=$obj->fiscalyearid) AND fiscalyear=(SELECT year FROM muusa_currentyear) AND chargetypeid IN (1000,1011)";
			$db->setQuery($query);
			$db->query();
			if($db->getErrorNum()) {
				JError::raiseError(500, $db->stderr());
			}
		}
	}

	function deleteFiscalyear($id) {
		$db =& JFactory::getDBO();
		$query = "DELETE FROM muusa_charges WHERE camperid=(SELECT camperid FROM muusa_fiscalyear WHERE fiscalyearid=$id) AND fiscalyear=(SELECT year FROM muusa_currentyear) AND amount>=0";
		$db->setQuery($query);
		$db->query();
		if($db->getErrorNum()) {
			JError::raiseError(500, $db->stderr());
		}

		$query = "DELETE FROM muusa_fiscalyear WHERE fiscalyearid=$id";
		$db->setQuery($query);
		$db->query();
		if($db->getErrorNum()) {
			JError::raiseError(500, $db->stderr());
		}
	}

	function updateHousing($roomid) {
		$db =& JFactory::getDBO();
		$query = "SELECT mc.camperid FROM muusa_campers mc, muusa_fiscalyear mf, muusa_currentyear my WHERE mc.camperid=mf.camperid AND muusa_age_f(birthdate)>4 AND mf.roomid=$roomid AND mf.fiscalyear=my.year";
		$db->setQuery($query);
		$roommates = $db->loadResultArray();

		foreach($roommates as $camperid) {
			$query = "SELECT mr.chargeid FROM muusa_charges mr, muusa_currentyear my WHERE mr.camperid=$camperid AND mr.chargetypeid=1000 AND mr.fiscalyear=my.year";
			$db->setQuery($query);
			$chargeid = $db->loadResult();
			$user =& JFactory::getUser();

			if($chargeid != "") {
				$query = "UPDATE muusa_charges SET ";
				$query .= "amount=muusa_rates_f($camperid), ";
				$query .= "memo=(SELECT mb.name FROM muusa_buildings mb, muusa_rooms mr WHERE mb.buildingid=mr.buildingid AND mr.roomid=$roomid), timestamp='" . date("Y-m-d");
				$query .= "', modified_by='$user->username', modified_at=CURRENT_TIMESTAMP WHERE chargeid=$chargeid";
			} else {
				$query = "INSERT INTO muusa_charges (camperid, amount, memo, chargetypeid, timestamp, fiscalyear, created_by, created_at) VALUES ($camperid, ";
				$query .= "muusa_rates_f($camperid), ";
				$query .= "(SELECT mb.name FROM muusa_buildings mb, muusa_rooms mr WHERE mb.buildingid=mr.buildingid AND mr.roomid=$roomid), ";
				$query .= "1000, '" . date("Y-m-d") . "', (SELECT year FROM muusa_currentyear), '$user->username', CURRENT_TIMESTAMP)";
			}
			$db->setQuery($query);
			$db->query();
			if($db->getErrorNum()) {
				JError::raiseError(500, $db->stderr());
			}

			$query = "DELETE FROM muusa_charges WHERE camperid=$camperid AND fiscalyear=(SELECT year FROM muusa_currentyear) AND chargetypeid=1004";
			$db->setQuery($query);
			$db->query();
			if($db->getErrorNum()) {
				JError::raiseError(500, $db->stderr());
			}
		}
	}

	function insertCharge($obj) {
		$db =& JFactory::getDBO();
		$obj->timestamp = ($obj->timestamp) ? "&&STR_TO_DATE('$obj->timestamp', '%m/%d/%Y')" : date("Y-m-d");
		$db->insertObject("muusa_charges", $obj);
		if($db->getErrorNum()) {
			JError::raiseError(500, $db->stderr());
		}
	}

	function deleteCharge($id) {
		$db =& JFactory::getDBO();
		$query = "DELETE FROM muusa_charges WHERE chargeid=" . $id;
		$db->setQuery($query);
		$db->query();
		if($db->getErrorNum()) {
			JError::raiseError(500, $db->stderr());
		}
	}
}