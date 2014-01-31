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
 * muusla_tools Model
 *
 * @package    muusla_database
 * @subpackage Components
 */
class muusla_databaseModelstaff extends JModel
{
   function getCampers() {
      $db =& JFactory::getDBO();
      $query = "SELECT c.id, c.firstname, c.lastname, f.city, f.statecd FROM muusa_camper c, muusa_family f WHERE c.familyid=f.id ORDER BY f.name, c.birthdate";
      $db->setQuery($query);
      return $db->loadObjectList();
   }

   function getPrograms() {
      $db =& JFactory::getDBO();
      $query = "SELECT id, name FROM muusa_program p, muusa_year y WHERE y.year>=p.start_year AND y.year<=p.end_year AND y.is_current=1 ORDER BY name";
      $db->setQuery($query);
      return $db->loadObjectList();
   }

   function getPositions($id) {
      $db =& JFactory::getDBO();
      $query = "SELECT sp.id, sp.name FROM muusa_staffposition sp, muusa_year y WHERE sp.start_year<=y.year AND sp.end_year>y.year AND y.is_current=1 AND sp.programid=$id ORDER BY name";
      $db->setQuery($query);
      return $db->loadObjectList();
   }

   function getStaff($id) {
      $db =& JFactory::getDBO();
      $query = "SELECT * FROM (SELECT c.id camperid, 0 yearattendingid, f.name familyname, c.firstname, c.lastname, cs.staffpositionid, sp.name staffpositionname FROM muusa_family f, muusa_camper c, muusa_camperid__staff cs, muusa_staffposition sp WHERE f.id=c.familyid AND c.id=cs.camperid AND cs.staffpositionid=sp.id AND sp.programid=$id UNION ALL SELECT c.id, ya.id, f.name, c.firstname, c.lastname, sp.id, sp.name FROM muusa_family f, muusa_camper c, muusa_yearattending ya, muusa_yearattending__staff tsp, muusa_staffposition sp, muusa_year y WHERE f.id=c.familyid AND c.id=ya.camperid AND ya.year=y.year AND y.is_current=1 AND ya.id=tsp.yearattendingid AND tsp.staffpositionid=sp.id AND sp.programid=$id) s1 ORDER BY familyname, lastname, firstname";
      $db->setQuery($query);
      return $db->loadObjectList();
   }

   function deleteCamperStaff($obj) {
      $db =& JFactory::getDBO();
      $user =& JFactory::getUser();
      $query = "DELETE FROM muusa_camperid__staff WHERE camperid=$obj->camperid AND staffpositionid=$obj->staffpositionid";
      $db->setQuery($query);
      $db->query();
      if($db->getErrorNum()) {
         JError::raiseError(500, $db->stderr());
      }
   }

   function deleteYearStaff($obj) {
      $db =& JFactory::getDBO();
      $user =& JFactory::getUser();
      $query = "DELETE FROM muusa_yearattending__staff WHERE yearattendingid=$obj->yearattendingid AND staffpositionid=$obj->staffpositionid";
      $db->setQuery($query);
      $db->query();
      if($db->getErrorNum()) {
         JError::raiseError(500, $db->stderr());
      }
   }

   function upsertStaff($obj) {
      $db =& JFactory::getDBO();
      $user =& JFactory::getUser();
      $query = "SELECT ya.id FROM muusa_yearattending ya, muusa_year y WHERE ya.camperid=$obj->camperid AND ya.year=y.year AND y.is_current=1";
      $db->setQuery($query);
      $yaid = $db->loadResult();
      if($yaid) {
         $query = "SELECT yearattendingid FROM muusa_yearattending__staff WHERE yearattendingid=$yaid AND staffpositionid=$obj->staffpositionid";
         $db->setQuery($query);
         $yearattendingid = $db->loadResult();
         if($yaid != $yearattendingid) {
            $obj->yearattendingid = $yaid;
            unset($obj->camperid);
            $db->insertObject("muusa_yearattending__staff", $obj);
         }
      } else {
         $query = "SELECT camperid FROM muusa_camperid__staff WHERE camperid=$obj->camperid AND staffpositionid=$obj->staffpositionid";
         $db->setQuery($query);
         $camperid = $db->loadResult();
         if($obj->camperid != $camperid) {
            $db->insertObject("muusa_camperid__staff", $obj);
         }
      }
      if($db->getErrorNum()) {
         JError::raiseError(500, $db->stderr());
      }
   }
}