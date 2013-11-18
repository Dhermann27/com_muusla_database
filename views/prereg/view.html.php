<?php
/**
 * @package		muusla_tools
 * @license		GNU/GPL, see LICENSE.php
 */

jimport( 'joomla.application.component.view');

/**
 * HTML View class for the muusla_tools Component
 *
 * @package		muusla_tools
 */
class muusla_databaseViewprereg extends JView
{
   function display($tpl = null) {
      $model =& $this->getModel();
      $user =& JFactory::getUser();
      $admin = $editcamper && (in_array("8", $user->groups) || in_array("10", $user->groups));
      $calls[][] = array();
      foreach(JRequest::get() as $key=>$value) {
         if(preg_match('/^(\w+)-(\w+)-(\d+)$/', $key, $objects)) {
            $table = $this->getSafe($objects[1]);
            $column = $this->getSafe($objects[2]);
            $id = $this->getSafe($objects[3]);
            if($calls[$table][$id] == null) {
               $obj = new stdClass;
               $obj->created_by = $user->username;
               $calls[$table][$id] = $obj;
            }
            $calls[$table][$id]->$column = $this->getSafe($value);
         }
      }
      if(count($calls["charge"])) {
         foreach($calls["charge"] as $charge) {
            if($charge->amount != 0 && $charge->changed == "1") {
               unset($charge->changed);
               $model->upsertCharge($charge);
            }
         }
      }

      $this->assignRef('campers', $model->getCampers());
      $this->assignRef('chargetypes', $model->getChargetypes());
      $this->assignRef('years', $model->getYears());
      $this->assignRef('carrys', $model->getCarryovers());
      $this->assignRef('preregs', $model->getPrereg());
      parent::display($tpl);
   }

   function getSafe($obj)
   {
      return htmlspecialchars(trim($obj), ENT_QUOTES);
   }

}
?>
