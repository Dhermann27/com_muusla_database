<?php
/**
 * @package		muusla_database
 * @license		GNU/GPL, see LICENSE.php
 */

jimport( 'joomla.application.component.view');

/**
 * HTML View class for the muusla_database times Component
 *
 * @package		muusla_database
 */
class muusla_databaseViewtimes extends JView
{


	function display($tpl = null) {
		$model =& $this->getModel();
		$this->assignRef('times', $model->getTimeslots());

		parent::display($tpl);
	}

	function save($tpl = null) {
		$model =& $this->getModel();
		foreach(JRequest::get() as $key=>$value) {
			if(preg_match('/^timename-(\d*)/', $key, $matches)) {
				if($matches[1] == "0") {
					if($value != "") {
						$model->insertTimeslot();
					}
				} else {
					if(JRequest::getSafe("timedelete-$matches[1]") == "delete") {
						$model->deleteTimeslot($matches[1]);
					} else {
						$model->updateTimeslot($matches[1]);
					}
				}
			}
		}
		$this->assignRef('times', $model->getTimeslots());

		parent::display($tpl);
	}

}
?>
