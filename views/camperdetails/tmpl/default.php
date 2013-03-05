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
	           	      array_push($campers, "{ label: '$camper->firstname $camper->lastname ($camper->city, $camper->statecd)', value: '$camper->firstname $camper->lastname ($camper->familyid)'}\n");
	           	   } 
	           	   echo implode(",\n", $campers);
	           	   ?>
	           	   ];
   	   $("#campers").autocomplete( { source: campers }).focus(function() { $(this).val("") });
   	   $("#go").button().click(function() {
   	   	   switch(parseInt($("#actioncamper").val(), 10)) {
   	   		   case 0:
   	   	   		   $("#muusaApp").attr("action", "<?php echo JURI::root(true);?>/index.php/register").submit();
   	   	   		   break;
   	   		   case 1:
   	   	   		   $("#campers").val("(1)");
   	   	   		   $("#muusaApp").attr("action", "<?php echo JURI::root(true);?>/index.php/register").submit();
   	   	   		   break;
   	   	   }
   	   });
   });
   </script>
<div id="ja-content">
   <div class="componentheading">MUUSA Campers</div>
   <form id="muusaApp" method="post">
      <div class="ui-widget" style="font-size: 1.5em;">
         <h4>Camper Name:</h4>
         <input id="campers" name="editcamper"
            class="inputtext ui-corner-all" />
         <h4>Action:</h4>
         <select id="actioncamper" class="ui-corner-all">
            <option value="0">Camper Details</option>
            <option value="1">Create New Camper</option>
         </select> <a id="go">Go</a>
      </div>
   </form>
</div>
