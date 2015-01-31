<?php
/**
 * @package		muusla_database
 * @license		GNU/GPL, see LICENSE.php
 */

jimport( 'joomla.application.component.view');

/**
 * HTML View class for the muusla_database rates Component
 *
 * @package		muusla_database
 */
class muusla_databaseViewrates extends JViewLegacy
{
   function display($tpl = null) {
      $model = $this->getModel();
      $this->assignRef('buildings', $model->getBuildings());
      $this->assignRef('programs', $model->getPrograms());
      $this->assignRef('rategroups', $model->getRates());

      parent::display($tpl);
   }

   function save($tpl = null) {
      $model = $this->getModel();
      foreach(JRequest::get() as $key=>$value) {
         if(preg_match('/^rateamount-(\d*)/', $key, $matches) && $value > 0.0) {
            if($matches[1] == "0") {
               $model->insertRate();
            } else {
               $model->updateRate($matches[1]);
            }
         } elseif(preg_match('/^ratedelete-(\d*)/', $key, $matches) && $value == "delete") {
            $model->deleteRate($matches[1]);
         }
      }

      $this->assignRef('buildings', $model->getBuildings());
      $this->assignRef('programs', $model->getPrograms());
      $this->assignRef('rategroups', $model->getRates());

      parent::display($tpl);
   }

}
?>
