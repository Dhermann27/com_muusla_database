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
class muusla_databaseModelcamperdetails extends JModelItem
{
   function getAllCampers() {
      $db = JFactory::getDBO();
      $query = "SELECT f.id, c.firstname, c.lastname, f.city, f.statecd FROM muusa_camper c, muusa_family f WHERE c.familyid=f.id ORDER BY f.name, c.birthdate";
      $db->setQuery($query);
      return $db->loadObjectList();
   }
}