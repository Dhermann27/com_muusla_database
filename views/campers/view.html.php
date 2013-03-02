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
/*		if(JRequest::getSafe("camperdelete") == "delete") {
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
*/
		parent::display($tpl);
	}

	function getSafe($obj)
	{
		return htmlspecialchars(trim($obj), ENT_QUOTES);
	}
}
?>
