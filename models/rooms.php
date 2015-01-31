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
class muusla_databaseModelrooms extends JModelItem
{
   function getBuildings() {
      $db = JFactory::getDBO();
      $query = "SELECT buildingid, name FROM muusa_buildings";
      $db->setQuery($query);
      return $db->loadObjectList();
   }

   function getRooms() {
      $db = JFactory::getDBO();
      $query = "SELECT mr.roomid roomid, mr.buildingid buildingid, mb.name buildingname, mr.roomnbr roomnbr, mr.capacity capacity, mr.is_workshop is_workshop, mr.is_handicap is_handicap FROM muusa_rooms mr, muusa_buildings mb WHERE mr.buildingid=mb.buildingid ORDER BY mr.buildingid, mr.roomnbr";
      $db->setQuery($query);
      $results = $db->loadObjectList();
      $buildings = array();
      $counter = -1;
      foreach($results as $result) {
         if($counter == -1 || $buildings[$counter]->buildingid != $result->buildingid) {
            $building = new stdClass;
            $building->buildingid = $result->buildingid;
            $building->buildingname = $result->buildingname;
            $building->rooms = array();
            $buildings[++$counter] = $building;
         }
         $room = new stdClass;
         $room->roomid = $result->roomid;
         $room->roomnbr = $result->roomnbr;
         $room->capacity = $result->capacity;
         $room->is_workshop = $result->is_workshop;
         $room->is_handicap = $result->is_handicap;
         $buildings[$counter]->rooms[] = $room;
      }
      return $buildings;
   }

   function deleteRoom($id) {
      $db = JFactory::getDBO();
      $user = JFactory::getUser();
      $query = "UPDATE muusa_fiscalyear SET roomid = NULL, modified_by='$user->username', modified_at=CURRENT_TIMESTAMP WHERE roomid = '$id'";
      $db->setQuery($query);
      $db->query();
      if($db->getErrorNum()) {
         JError::raiseError(500, $db->stderr());
      }

      $query = "UPDATE muusa_events SET roomid = NULL, modified_by='$user->username', modified_at=CURRENT_TIMESTAMP WHERE roomid = '$id'";
      $db->setQuery($query);
      $db->query();
      if($db->getErrorNum()) {
         JError::raiseError(500, $db->stderr());
      }

      $query = "DELETE FROM muusa_rooms WHERE roomid = '$id'";
      $db->setQuery($query);
      $db->query();
      if($db->getErrorNum()) {
         JError::raiseError(500, $db->stderr());
      }
   }

   function updateRoom($id) {
      $db = JFactory::getDBO();
      $user = JFactory::getUser();
      $obj = new stdClass;
      $obj->roomid = $id;
      $obj->roomnbr = JRequest::getSafe("roomnbr-$id");
      $obj->capacity  = JRequest::getSafe("roomcapacity-$id");
      $obj->is_workshop = JRequest::getSafe("roomworkshop-$id");
      $obj->is_handicap = JRequest::getSafe("roomhandicap-$id");
      $obj->modified_by = $user->username;
      $obj->modified_at = date("Y-m-d H:i:s");
      $db->updateObject("muusa_rooms", $obj, "roomid");
      if($db->getErrorNum()) {
         JError::raiseError(500, $db->stderr());
      }

   }

   function insertRoom($nbr, $buildingid, $capacity, $isworkshop, $ishandicap) {
      $db = JFactory::getDBO();
      $obj = new stdClass;
      $obj->roomnbr = JRequest::getSafe("roomnbr-0");
      $obj->buildingid = JRequest::getSafe("roombuilding-0");
      $obj->capacity  = JRequest::getSafe("roomcapacity-0");
      $obj->is_workshop = JRequest::getSafe("roomworkshop-0");
      $obj->is_handicap = JRequest::getSafe("roomhandicap-0");
      $db->insertObject("muusa_rooms", $obj);
      if($db->getErrorNum()) {
         JError::raiseError(500, $db->stderr());
      }

   }

}