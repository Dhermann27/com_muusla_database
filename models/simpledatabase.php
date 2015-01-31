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
class muusla_databaseModelsimpledatabase extends JModelItem
{
   function getItems($table) {
      $db = JFactory::getDBO();
      $query = "SELECT " . substr($table, 0, -1) . "id id, name FROM muusa_" . $table;
      $db->setQuery($query);
      return $db->loadObjectList();
   }

   function delete($id) {
      $db = JFactory::getDBO();
      $query = "DELETE FROM muusa_" . JRequest::getSafe("table") . " WHERE buildingid = '$id'";
      $db->setQuery($query);
      $db->query();
      if($db->getErrorNum()) {
         JError::raiseError(500, $db->stderr());
      }
   }

   function update($id) {
      $user = JFactory::getUser();
      $db = JFactory::getDBO();
      $query = "UPDATE muusa_" . JRequest::getSafe("table") . " SET name='" . JRequest::getSafe("name-$id") . "', modified_by='$user->username', modified_at=CURRENT_TIMESTAMP WHERE " . substr(JRequest::getSafe("table"), 0, -1) . "id = $id";
      $db->setQuery($query);
      $db->query();
      if($db->getErrorNum()) {
         JError::raiseError(500, $db->stderr());
      }

   }

   function insert() {
      $db = JFactory::getDBO();
      $obj = new stdClass;
      $obj->name = JRequest::getSafe("name-0");
      $db->insertObject("muusa_" . JRequest::getSafe("table"), $obj);
      if($db->getErrorNum()) {
         JError::raiseError(500, $db->stderr());
      }

   }

}