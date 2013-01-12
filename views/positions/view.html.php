<?php
/**
 * @package		muusla_database
 * @license		GNU/GPL, see LICENSE.php
 */

jimport( 'joomla.application.component.view');

/**
 * HTML View class for the muusla_database positions Component
 *
 * @package		muusla_database
 */
class muusla_databaseViewpositions extends JView
{
   function display($tpl = null) {
      $model =& $this->getModel();
      $this->assignRef('positions', $model->getPositions());

      parent::display($tpl);
   }

   function save($tpl = null) {
      $model =& $this->getModel();
      foreach(JRequest::get() as $key=>$value) {
         if(preg_match('/^positionname-(\d*)/', $key, $matches)) {
            if($matches[1] == "0") {
               if($value != "") {
                  $model->insertPosition();
               }
            } else {
               if(JRequest::getSafe("positiondelete-$matches[1]") == "delete") {
                  $model->deletePosition($matches[1]);
               } else {
                  $model->updatePosition($matches[1]);
               }
            }
         }
      }

      $this->assignRef('positions', $model->getPositions());

      parent::display($tpl);
   }

}
?>
