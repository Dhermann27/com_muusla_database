<?php
/**
 * muusla_database Model for muusla_database Component
 *
 * @package    muusla_database
 * @subpackage Components
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport( 'joomla.application.component.model' );

/**
 * muusla_database Model
 *
 * @package    muusla_database
 * @subpackage Components
 */
class muusla_databaseModeltimes extends JModelItem
{
   function getTimeslots() {
      $db = JFactory::getDBO();
      $query = "SELECT timeid, name, DATE_FORMAT(starttime, '%l:%i %p') starttime, length FROM muusa_times";
      $db->setQuery($query);
      return $db->loadObjectList();
   }

   function deleteTimeslot($id) {
      $db = JFactory::getDBO();
      $user = JFactory::getUser();
      $query = "UPDATE muusa_events SET timeid = NULL, modified_by='$user->username', modified_at=CURRENT_TIMESTAMP WHERE timeid = '$id'";
      $db->setQuery($query);
      $db->query();
      if($db->getErrorNum()) {
         JError::raiseError(500, $db->stderr());
      }

      $query = "DELETE FROM muusa_times WHERE timeid = '$id'";
      $db->setQuery($query);
      $db->query();
      if($db->getErrorNum()) {
         JError::raiseError(500, $db->stderr());
      }
   }

   function updateTimeslot($id) {
      $db = JFactory::getDBO();
      $user = JFactory::getUser();
      $query = "UPDATE muusa_times SET name='" . JRequest::getSafe("timename-$id") . "', starttime=STR_TO_DATE('" . JRequest::getSafe("timestart-$id") . "', '%l:%i %p'), length=" . JRequest::getSafe("timelength-$id") . ", modified_by='$user->username', modified_at=CURRENT_TIMESTAMP WHERE timeid = '$id'";
      $db->setQuery($query);
      $db->query();
      if($db->getErrorNum()) {
         JError::raiseError(500, $db->stderr());
      }
   }

   function insertTimeslot() {
      $db = JFactory::getDBO();
      $user = JFactory::getUser();
      $query = "INSERT INTO muusa_times (name, starttime, length, created_by, created_at) VALUES ('" . JRequest::getSafe("timename-0") . "', STR_TO_DATE('" . JRequest::getSafe("timestart-0") . "', '%l:%i %p'), " . JRequest::getSafe("timelength-0") . ", '$user->username', CURRENT_TIMESTAMP)";
      $db->setQuery($query);
      $db->query();
      if($db->getErrorNum()) {
         JError::raiseError(500, $db->stderr());
      }
   }

}