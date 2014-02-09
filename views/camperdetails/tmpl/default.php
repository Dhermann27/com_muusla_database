<?php defined('_JEXEC') or die('Restricted access'); ?>
<link type="text/css"
   href="<?php echo JURI::root(true);?>/components/com_muusla_application/css/application.css"
   rel="stylesheet" />
<script>
jQuery(document).ready(function ($) {
   	   $("#go").button().click(function() {
   		   switch(parseInt(jQuery("#actioncamper").val(), 10)) {
   		   case 0:
   	   		   $("#muusaApp").attr("action", "<?php echo JURI::root(true);?>/index.php/register").submit();
   	   		   break;
   	   	   case 1:
   	   	       $("#editcamper").val("1");
   	   	       $("#muusaApp").attr("action", "<?php echo JURI::root(true);?>/index.php/register").submit();
   	   	       break;
   	   	   case 2:
   	   	   	   $("#muusaApp").attr("action", "<?php echo JURI::root(true);?>/index.php/registration/workshops").submit();
   	   	   	   break;
   	   	   case 3:
   	   	   	   $("#muusaApp").attr("action", "<?php echo JURI::root(true);?>/index.php/rooms").submit();
   	   	   	   break;
   	   	   case 4:
   	   	   	   $("#muusaApp").attr("action", "<?php echo JURI::root(true);?>/index.php/administration/tools/nametags").submit();
   	   	   	   break;
   	   	   }
   	   });
    camperlist = [ <?php
        $campers = array();
        foreach($this->campers as $camper) {
            array_push($campers, "{ label: '$camper->firstname $camper->lastname ($camper->city, $camper->statecd)', ident: '$camper->id'}\n");
        }
        echo implode(",\n", $campers); ?>
    ];
});
   </script>
<div id="ja-content">
   <div class="componentheading">MUUSA Campers</div>
   <form id="muusaApp" method="post">
      <div class="ui-widget" style="font-size: 1.5em;">
         <h4>Camper Name:</h4>
         <input type="text" class="inputtext camperlist ui-corner-all" /><input
            type="hidden" id="editcamper" name="editcamper"
            class="camperlist-value" />
         <h4>Action:</h4>
         <select id="actioncamper" class="ui-corner-all">
            <!-- <option value="3">Assign Room</option> -->
            <option value="0" selected="selected">Camper Details</option>
            <option value="1">Create New Camper</option>
            <!-- <option value="4">Print Nametag</option>
            <option value="2">Workshop Selection</option>-->
         </select> <a id="go">Go</a>
      </div>
   </form>
</div>
