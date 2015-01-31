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
class muusla_databaseViewstaff extends JViewLegacy
{
   function display($tpl = null) {
      $model = $this->getModel();
      $user = JFactory::getUser();
      $admin = in_array("8", $user->groups) || in_array("9", $user->groups) || in_array("10", $user->groups);
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
      if($admin && count($calls["camperid__staff"]) > 0) {
         foreach($calls["camperid__staff"] as $id => $staff) {
            if($staff->delete == "on") {
               $model->deleteCamperStaff($staff);
            }
            else if($id < 1000 && $staff->camperid > 0 && $staff->staffpositionid > 0) {
               $model->upsertStaff($staff);
            }
         }
      }
      if($admin && count($calls["yearattending__staff"]) > 0) {
         foreach($calls["yearattending__staff"] as $id => $staff) {
            if($staff->delete == "on") {
               $model->deleteYearStaff($staff);
            } else {
               $model->upsertPaid($staff);
            }
         }
      }

      $this->assignRef('admin', $admin);
      $this->assignRef('campers', $model->getCampers());
      $programs = $model->getPrograms();
      foreach($programs as $program) {
         $program->positions = $model->getPositions($program->id);
         $program->staff = $model->getStaff($program->id);
      }
      $this->assignRef('programs', $programs);
      parent::display($tpl);
   }

   function getSafe($obj)
   {
      return htmlspecialchars(trim($obj), ENT_QUOTES);
   }

}
?>
