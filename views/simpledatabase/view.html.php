<?php
/**
 * @package		muusla_database
 * @license		GNU/GPL, see LICENSE.php
 */

jimport( 'joomla.application.component.view');

/**
 * HTML View class for the muusla_database buildings Component
 *
 * @package		muusla_database
 */
class muusla_databaseViewsimpledatabase extends JView
{
   
   function display($tpl = null) {
      $model =& $this->getModel();
      $muusla = new stdClass;
      
      $params = &JComponentHelper::getParams( 'com_muusla_database' );
      $muusla->name = $params->get( 'muusla' );
      
      $muusla->items = $model->getItems($params->get( 'muusla' ));
      
      $obj = new stdClass;
      $obj->id = 0;
      $obj->name = "";
      array_push($muusla->items, $obj);
      $this->assignRef('muusla', $muusla);

      parent::display($tpl);
   }

   function save($tpl = null) {
      $model =& $this->getModel();
      
      foreach(JRequest::get() as $key=>$value) {
         if(preg_match('/^name-(\d*)/', $key, $matches)) {
            if($matches[1] == "0") {
               if($value != "") {
                  $model->insert();
               }
            } else {
               if(JRequest::getSafe("delete-$matches[1]") == "delete") {
                  $model->delete($matches[1]);
               } else {
                  $model->update($matches[1]);
               }
            }
         }
      }

      $muusla->name = JRequest::getSafe("table");
      $muusla->items = $model->getItems(JRequest::getSafe("table"));
      
      $obj = new stdClass;
      $obj->id = 0;
      $obj->name = "";
      array_push($muusla->items, $obj);
      $this->assignRef('muusla', $muusla);

      parent::display($tpl);
   }

}
?>
