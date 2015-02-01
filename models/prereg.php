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
class muusla_databaseModelprereg extends JModelItem
{
   function getCampers() {
      $db = JFactory::getDBO();
      $query = "SELECT c.id, c.firstname, c.lastname, f.city, f.statecd FROM muusa_camper c, muusa_family f WHERE c.familyid=f.id ORDER BY f.name, c.birthdate";
      $db->setQuery($query);
      return $db->loadObjectList();
   }

   function getChargetypes() {
      $db = JFactory::getDBO();
      $query = "SELECT id, name FROM muusa_chargetype WHERE id IN (1001, 1016, 1022)";
      $db->setQuery($query);
      return $db->loadObjectList();
   }

   function getYears() {
      $db = JFactory::getDBO();
      $query = "SELECT year-1, year FROM muusa_year WHERE is_current=1";
      $db->setQuery($query);
      return $db->loadRow();
   }

   function getCarryovers() {
      $db = JFactory::getDBO();
      $query = "SELECT c.firstname, c.lastname, h.amount, h.timestamp, h.memo FROM muusa_family f, muusa_camper c, muusa_charge h, muusa_year y WHERE f.id=c.familyid AND c.id=h.camperid AND h.chargetypeid=1015 AND h.year=y.year-1 AND y.is_current=1 ORDER BY f.name, c.birthdate";
      $db->setQuery($query);
      return $db->loadObjectList();
   }

   function getPrereg() {
      $db = JFactory::getDBO();
      $query = "SELECT th.id, f.name familyname, c.firstname, c.lastname, th.camperid, th.chargetypeid, ABS(th.amount) amount, DATE_FORMAT(th.timestamp, '%m/%d/%Y') timestamp, th.memo FROM muusa_family f, muusa_camper c, muusa_thisyear_charge th WHERE f.id=c.familyid AND c.id=th.camperid AND th.chargetypeid IN (1001,1016,1022) ORDER BY f.name, c.birthdate";
      $db->setQuery($query);
      return $db->loadObjectList();
   }

   function deleteCharge($id) {
      $db = JFactory::getDBO();
      $user = JFactory::getUser();
      $query = "DELETE FROM muusa_charge WHERE id=$id";
      $db->setQuery($query);
      $db->query();
      if($db->getErrorNum()) {
         JError::raiseError(500, $db->stderr());
      }
   }

   function upsertCharge($obj) {
      $db = JFactory::getDBO();
      $user = JFactory::getUser();
      $query = "SELECT id FROM muusa_charge WHERE camperid=$obj->camperid AND timestamp='$obj->timestamp' AND amount=ABS($obj->amount) AND chargetypeid=$obj->chargetypeid AND memo='$obj->memo'";
      $obj->amount = preg_replace("/,/", "", -$obj->amount);
      $obj->timestamp = "&&STR_TO_DATE('$obj->timestamp', '%m/%d/%Y')";
      if($obj->id < 1000) {
         $db->setQuery($query);
         $chargeid = $db->loadResult();
         if($chargeid > 0) {
            $obj->id = $chargeid;
            $db->updateObject("muusa_charge", $obj, "id");
         } else {
            unset($charge->id);
            $db->insertObject("muusa_charge", $obj);
         }
      } else {
         $db->updateObject("muusa_charge", $obj, "id");
      }
      if($db->getErrorNum()) {
         JError::raiseError(500, $db->stderr());
      }
   }
}