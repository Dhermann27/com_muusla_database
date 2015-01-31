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
class muusla_databaseModelrates extends JModelItem
{
   function getBuildings() {
      $db = JFactory::getDBO();
      $query = "SELECT buildingid, name FROM muusa_buildings";
      $db->setQuery($query);
      return $db->loadObjectList();
   }

   function getPrograms() {
      $db = JFactory::getDBO();
      $query = "SELECT p.id, p.name FROM muusa_program p, muusa_year y WHERE y.year>=p.start_year AND y.year<=p.end_year";
      $db->setQuery($query);
      return $db->loadObjectList();
   }

   function getRates() {
      $db = JFactory::getDBO();
      $query = "SELECT mr.rateid, mr.buildingid buildingid, mb.name buildingname, mr.programid, mp.name programname, mr.occupancy_adult occadult, mr.occupancy_children occchild, mr.amount FROM muusa_rates mr, muusa_buildings mb WHERE mr.buildingid=mb.buildingid AND mr.programid=mp.programid ORDER BY mr.buildingid, mr.programid, mr.occupancy_adult, mr.occupancy_children";
      $db->setQuery($query);
      $results = $db->loadObjectList();
      $buildings = array();
      $bcounter = -1;
      $pcounter = -1;
      foreach($results as $result) {
         if($bcounter == -1 || $buildings[$bcounter]->buildingid != $result->buildingid) {
            $building = new stdClass;
            $building->buildingid = $result->buildingid;
            $building->buildingname = $result->buildingname;
            $building->programs = array();
            $buildings[++$bcounter] = $building;
         }
         if($pcounter == -1 || $buildings[$bcounter]->programs[$pcounter]->programid != $result->programid) {
            $program = new stdClass;
            $program->programid = $result->programid;
            $program->programname = $result->programname;
            $program->rates = array();
            $buildings[$bcounter]->programs[++$pcounter] = $program;
         }
         $rate = new stdClass;
         $rate->rateid = $result->rateid;
         $rate->occadult = $result->occadult;
         $rate->occchild = $result->occchild;
         $rate->amount = $result->amount;
         $buildings[$bcounter]->programs[$pcounter]->rates[] = $rate;
      }
      return $buildings;
   }

   function deleteRate($id) {
      $db = JFactory::getDBO();
      $query = "DELETE FROM muusa_rates WHERE rateid = '$id'";
      $db->setQuery($query);
      $db->query();
      if($db->getErrorNum()) {
         JError::raiseError(500, $db->stderr());
      }
   }

   function updateRate($id) {
      $db = JFactory::getDBO();
      $user = JFactory::getUser();
      $obj = new stdClass;
      $obj->rateid = $id;
      $obj->occupancy_adult = JRequest::getSafe("rateoccadult-$id");
      $obj->occupancy_children = JRequest::getSafe("rateoccchild-$id");
      $obj->amount = JRequest::getSafe("rateamount-$id");
      $obj->modified_by = $user->username;
      $obj->modified_at = date("Y-m-d H:i:s");
      $db->updateObject("muusa_rates", $obj, "rateid", false);
      if($db->getErrorNum()) {
         JError::raiseError(500, $db->stderr());
      }
   }

   function insertRate() {
      $db = JFactory::getDBO();
      $obj = new stdClass;
      $obj->occupancy_adult = JRequest::getSafe("rateoccadult-0");
      $obj->occupancy_children = JRequest::getSafe("rateoccchild-0");
      $obj->buildingid = JRequest::getSafe("ratebuildingid-0");
      $obj->programid = JRequest::getSafe("rateprogramid-0");
      $obj->amount = JRequest::getSafe("rateamount-0");
      $db->insertObject("muusa_rates", $obj);
      if($db->getErrorNum()) {
         JError::raiseError(500, $db->stderr());
      }
   }

}