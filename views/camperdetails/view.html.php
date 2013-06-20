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

}