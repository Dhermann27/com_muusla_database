<?php
/**
 * @package		muusla_database
 * @license		GNU/GPL, see LICENSE.php
 */

jimport( 'joomla.application.component.view');

/**
 * HTML View class for the muusla_database rooms Component
 *
 * @package		muusla_database
 */
class muusla_databaseViewrooms extends JView
{
   function display($tpl = null) {
      $model =& $this->getModel();
      $this->assignRef('buildings', $model->getBuildings());
      $this->assignRef('rooms', $model->getRooms());

      parent::display($tpl);
   }

   function save($tpl = null) {
      $model =& $this->getModel();
      foreach(JRequest::get() as $key=>$value) {
         if(preg_match('/^roomnbr-(\d*)/', $key, $matches)) {
            if($matches[1] == "0") {
               if($value != "") {
                  $model->insertRoom();
               }
            } else {
               if(JRequest::getSafe("roomdelete-$matches[1]") == "delete") {
                  $model->deleteRoom($matches[1]);
               } else {
                  $model->updateRoom($matches[1]);
               }
            }
         }
      }

      $this->assignRef('buildings', $model->getBuildings());
      $this->assignRef('rooms', $model->getRooms());

      parent::display($tpl);
   }

}
?>
