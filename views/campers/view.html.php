<?php
/**
 * @package		muusla_database
 * @license		GNU/GPL, see LICENSE.php
 */

jimport( 'joomla.application.component.view');

/**
 * HTML View class for the muusla_database campers Component
 *
 * @package		muusla_database
 */
class muusla_databaseViewcampers extends JView
{
	function display($tpl = null) {
		$model =& $this->getModel();
		$this->assignRef('campers', $model->getAllCampers());

		parent::display($tpl);
	}

	function editcamper($tpl = null) {
		$model =& $this->getModel();
		if(JRequest::getSafe("camperdelete") == "delete") {
			$val = "Camper deleted successfully.";
			$this->assignRef('deleted', $val);
			$model->deletecamper(JRequest::getSafe("editcamper"));
		} else {
			$phonenbrcount = 0;
			$camper = $model->getCamper(JRequest::getSafe("editcamper"));
			$camper->phonenbrs = $model->getPhonenumbers($camper->camperid);
			for($i=count($camper->phonenbrs);$i<3;$i++) {
				$emptyNbr = new stdClass;
				$emptyNbr->phonenbrid = $phonenbrcount++;
				$emptyNbr->phonetypeid = 0;
				$emptyNbr->phonenbr = "";
				array_push($camper->phonenbrs, $emptyNbr);
			}
			if($camper->camperid) {
				$children = $model->getChildren($camper->camperid);
				if(count($children) > 0) {
					foreach($children as $child) {
						$child->phonenbrs = $model->getPhonenumbers($child->camperid);
						for($i=count($child->phonenbrs);$i<3;$i++) {
							$emptyNbr = new stdClass;
							$emptyNbr->phonenbrid = $phonenbrcount++;
							$emptyNbr->phonetypeid = 0;
							$emptyNbr->phonenbr = "";
							array_push($child->phonenbrs, $emptyNbr);
						}
					}
				}
				$this->assignRef('children', $children);
			} else {
				$camper->camperid = 0;
			}
			$this->assignRef('camper', $camper);
			$this->assignRef('hohs', $model->getHeads());
			$this->assignRef('buildings', $model->getBuildings());
			$this->assignRef('states', $model->getStates());
			$this->assignRef('foodoptions', $model->getFoodoptions());
			$this->assignRef('churches', $model->getChurches());
			$this->assignRef('phonetypes', $model->getPhonetypes());
		}

		parent::display($tpl);
	}

	function save($tpl = null) {
		$model =& $this->getModel();
		$user =& JFactory::getUser();
		$calls[][] = array();
		$phonenumbers[] = array();
		foreach(JRequest::get() as $key=>$value) {
			if(preg_match('/^(\w+)-(\w+)-(\d+)$/', $key, $objects)) {
				if(!is_array($value)) {
					if($calls[$objects[1]][$objects[3]] == null) {
						$obj = new stdClass;
						if($objects[1] == "campers") {
							$obj->is_ecomm = 0;
							$obj->is_handicap = 0;
							$obj->is_ymca = 0;
							$obj->is_ecomm = 0;
						}
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
					$calls[$objects[1]][$objects[3]] = $value;
				}
			}
		}
		if($calls["campers"][0]) {
			$hohid = $model->upsertCamper($calls["campers"][0]);
			foreach($calls[phonenumbers] as $id => $number) {
				if($number->camperid == 0 && $number->phonenbr != "") {
					$number->camperid = $hohid;
					$number->phonenbrid = $phonenbrid;
					$model->upsertPhonenumber($number);
				}
			}
			unset($calls["campers"][0]);
		} else {
			$hohid = JRequest::getSafe("hohid");
		}
		foreach($calls["campers"] as $id => $camper) {
			if($camper->firstname != "") {
				if($id < 1000) {
					$camper->hohid = $hohid;
				} else {
					$camper->camperid = $id;
				}
				$camperid = $model->upsertCamper($camper);
				if($calls[phonenumbers]) {
					foreach($calls[phonenumbers] as $phonenbrid => $number) {
						if($number->camperid == $id && $number->phonenbr != "") {
							if($number->delete == "delete") {
								$model->deletePhonenumber($phonenbrid);
							} else {
								$number->phonenbrid = $phonenbrid;
								$number->camperid = $camperid;
								$model->upsertPhonenumber($number);
							}
						}
					}
				}
			}
		}

		$this->assignRef('campers', $model->getAllCampers());
		parent::display($tpl);
	}

	function getSafe($obj)
	{
		return htmlspecialchars(trim($obj), ENT_QUOTES);
	}
}
?>
