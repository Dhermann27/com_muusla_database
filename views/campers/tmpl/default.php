<?php defined('_JEXEC') or die('Restricted access'); ?>
<link type="text/css"
   href="<?php echo JURI::root(true);?>/components/com_muusla_application/css/application.css"
   rel="stylesheet" />
<link type="text/css"
   href="<?php echo JURI::root(true);?>/components/com_muusla_application/css/jquery-ui-1.10.0.custom.min.css"
   rel="stylesheet" />
<script
   src="<?php echo JURI::root(true);?>/components/com_muusla_application/js/jquery-1.9.1.min.js"></script>
<script
   src="<?php echo JURI::root(true);?>/components/com_muusla_application/js/jquery-ui-1.10.0.custom.min.js"></script>
<script>
   $(function() {
	   var campers = [
	           	   <?php
	           	   $campers = array();
	           	   foreach ($this->campers as $camper) {
	           	      array_push($campers, "{ label: '$camper->firstname $camper->lastname ($camper->city, $camper->statecd)', value: 'familyid'}\n");
	           	   } 
	           	   echo implode(",\n", $campers);
	           	   ?>
	           	   ];
   	   $("#campers").autocomplete( { source: campers });
   });
   </script>
<div id="ja-content">
   <div class="componentheading">MUUSA Campers</div>
   <div>
      This table is for the every camper at MUUSA. Primary campers have
      their city/state listed.<br />
   </div>
   <form method="post">
      <table class="blog" cellpadding="0" cellspacing="0">
         <div class="ui-widget">
            Campers: <input id="campers" />
         </div>
      </table>
   </form>
</div>
