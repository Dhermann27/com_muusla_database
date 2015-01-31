<?php
/**
 * @package		muusla_database
 * @license		GNU/GPL, see LICENSE.php
 */

jimport( 'joomla.application.component.view');

/**
 * HTML View class for the muusla_database events Component
 *
 * @package		muusla_database
 */
class Muusla_databaseViewEvents extends JViewLegacy
{
   function display($tpl = null) {
      $model = $this->getModel();
      $this->assignRef('rooms', $model->getRooms());
      $this->assignRef('times', $model->getTimes());
      $this->assignRef('events', $model->getEvents());

      parent::display($tpl);
   }

   function save($tpl = null) {
      $model = $this->getModel();
      $events[] = array();
      foreach(JRequest::get() as $key=>$value) {
         if(preg_match('/^(\w+)-(\d+)$/', $key, $objects)) {
            if($events[$objects[2]] == null) {
               $user = JFactory::getUser();
               $obj = new stdClass;
               $obj->eventid = $objects[2];
               if($objects[2] == 0) {
                  $obj->created_by = $user->username;
                  $obj->created_at = date("Y-m-d H:i:s");
               } else {
                  $obj->modified_by = $user->username;
                  $obj->modified_at = date("Y-m-d H:i:s");
               }
               $events[$objects[2]] = $obj;
            }
            $events[$objects[2]]->$objects[1] = $this->getSafe($value);
         }
      }
      foreach ($events as $eventid => $event) {
         if($event->delete == "delete") {
            $model->deleteEvent($event);
         } else if ($event->name != "") {
            $model->upsertEvent($event);
         }
      }

      $this->assignRef('rooms', $model->getRooms());
      $this->assignRef('times', $model->getTimes());
      $this->assignRef('events', $model->getEvents());

      parent::display($tpl);
   }

   function getSafe($obj)
   {
      return htmlspecialchars(trim($obj), ENT_QUOTES);
   }

}
?>
