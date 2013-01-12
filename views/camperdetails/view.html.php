<?php
/**
 * @package		muusla_database
 * @license		GNU/GPL, see LICENSE.php
 */

jimport( 'joomla.application.component.view');

/**
 * HTML View class for the muusla_database assignments Component
 *
 * @package		muusla_database
 */
class muusla_databaseViewcamperdetails extends JView
{

	function display($tpl = null) {
		$model =& $this->getModel();
		$this->assignRef('campers', $model->getAllCampers());

		parent::display($tpl);
	}

	function save($tpl = null) {
		$model =& $this->getModel();
		$user =& JFactory::getUser();
		$calls[][] = array();
		$events = array();
		foreach(JRequest::get() as $key=>$value) {
			if(preg_match('/^(\w+)-(\w+)-(\d+)$/', $key, $objects)) {
				if($objects[1] == "selected") {
					$events[$objects[3]][$objects[2]]->shops = is_array($value) ? $value : array($value);
				} else if($objects[1] == "leader") {
					$events[$objects[3]][$objects[2]]->leader = true;
				} else if(!is_array($value)) {
					if($calls[$objects[1]][$objects[3]] == null) {
						$obj = new stdClass;
						if($objects[3] < 1000) {
							$obj->created_by = $user->username;
							$obj->created_at = date("Y-m-d H:i:s");
						} else {
							$obj->modified_by = $user->username;
							$obj->modified_at = date("Y-m-d H:i:s");
						}
						$calls[$objects[1]][$objects[3]] = $obj;
					}
					$calls[$objects[1]][$objects[3]]->$objects[2] = $this->getSafe($value);
				} else {
					$obj = new stdClass;
					$obj->created_by = $user->username;
					$obj->created_at = date("Y-m-d H:i:s");
					$calls[$objects[1]][$objects[3]]->$objects[2] = $value;
				}
			}
		}
		if(count($calls[fiscalyear]) > 0) {
			foreach($calls[fiscalyear] as $id => $obj) {
				if($obj->delete) {
					$model->deleteFiscalyear($id);
				} else if($obj->fiscalyear) {
					$model->upsertFiscalyear($id, $obj);
				}
			}
		}
		if(count($calls[volunteers]) > 0) {
			foreach($calls[volunteers] as $id => $obj) {
				$model->upsertVolunteers($id, $obj);
			}
		}
		if(count($events) > 0) {
			foreach($events as $camperid => $event) {
				$model->upsertAttendees($camperid, $event);
			}
		}
		if(count($calls[charges]) > 0) {
			foreach($calls[charges] as $id => $obj) {
				if($obj->delete) {
					$model->deleteCharge($id);
				} else if($obj->recalc) {
					$model->calculateCharges($id);
				} else if($obj->amount != 0) {
					$model->insertCharge($obj);
				}
			}
		}
		$this->assignRef('year', $model->getYear());
		$this->assignRef('camper', $model->getCamper(JRequest::getSafe("editcamper")));
		$this->assignRef('buildings', $model->getRooms());
		$this->assignRef('fiscalyears', $model->getFiscalyears());
		$this->assignRef('positions', $model->getPositions(JRequest::getSafe("editcamper")));
		$workshops = array();
		foreach($model->getWorkshops() as $workshop) {
			if($workshop->days == 'MTuWThF') {
				$workshop->days = '5 days';
			}
			if($workshops[$workshop->timename] == null) {
				$workshops[$workshop->timename] = array($workshop);
			} else {
				array_push($workshops[$workshop->timename], $workshop);
			}
		}
		$this->assignRef('workshops', $workshops);
		$this->assignRef('chargetypes', $model->getChargetypes());
		foreach($model->getCharges(JRequest::getSafe("editcamper")) as $charge) {
			if($charges[$charge->fiscalyear] == null) {
				$charges[$charge->fiscalyear] = array($charge);
			} else {
				array_push($charges[$charge->fiscalyear], $charge);
			}
		}
		$this->assignRef('charges', $charges);

		foreach($model->getCredits(JRequest::getSafe("editcamper")) as $credit) {
			if($credits[$credit->fiscalyear] == null) {
				$credits[$credit->fiscalyear] = array($credit);
			} else {
				array_push($credits[$credit->fiscalyear], $credit);
			}
		}
		$this->assignRef('credits', $credits);

		parent::display($tpl);
	}

	function getSafe($obj)
	{
		return htmlspecialchars(trim($obj), ENT_QUOTES);
	}

}