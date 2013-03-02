<?php
/**
 * @package		muusla_database
 * @license		GNU/GPL, see LICENSE.php
 */

jimport('joomla.application.component.controller');

/**
 * muusla_database Component Controller
 *
 * @package		muusla_database
 */
class muusla_databaseController extends JController
{
   
   function muuslaControl($myLayout, $myView) {
      $document =& JFactory::getDocument();

      $viewType = $document->getType();
      $viewName = JRequest::getCmd( 'view', $this->getName() );
      $viewLayout = JRequest::getCmd( 'layout', $myLayout );

      $view =& $this->getView($viewName, $viewType, '', array( 'base_path'=>$this->_basePath));

      // Get/Create the model
      if ($model = & $this->getModel($viewName)) {
         // Push the model into the view (as default)
         $view->setModel($model, true);
      }

      // Set the layout
      $view->setLayout($viewLayout);

      // Display the view
      if ($cachable && $viewType != 'feed') {
         global $option;
         $cache =& JFactory::getCache($option, 'view');
         $cache->get($view, $myView);
      } else {
         $view->$myView();
      }
   }

}
?>
