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
class muusla_databaseModelcamperdetails extends JModel
{
   function getAllCampers() {
      $db =& JFactory::getDBO();
      $query = "SELECT mf.familyid, mc.firstname, mc.lastname, mf.city, mf.statecd FROM muusa_campers mc, muusa_family mf WHERE mc.familyid=mf.familyid ORDER BY mf.familyname, mc.birthdate";
      $db->setQuery($query);
      return $db->loadObjectList();
   }
}