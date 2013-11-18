<?php defined('_JEXEC') or die('Restricted access'); ?>
<link type="text/css"
   href="<?php echo JURI::root(true);?>/components/com_muusla_application/css/application.css"
   rel="stylesheet" />
<script type="text/javascript">
   jQuery(document).ready(function ($) {
	    $("#carryheader").button().click(function (event) {
	        $("#carryovers").toggle();
	        event.preventDefault();
	    });
	    $("#save").button().click(function (event) {
		    submit($);
	        event.preventDefault();
	        return false;
	    });
	    camperlist = [ <?php
	        $campers = array();
	        foreach($this->campers as $camper) {
	            array_push($campers, "{ label: '$camper->firstname $camper->lastname ($camper->city, $camper->statecd)', ident: '$camper->id'}\n");
	        }
	        echo implode(",\n", $campers); ?>
	    ];
	});
	
	function submit($) {
        var count = 0;
	    $("#muusaApp tr:visible")
	        .filter(
	            function () {
	                return pFloat($("input[name*='charge-amount']", $(this)).val()) > 0;
	            })
	        .each(function () {
	            $("select,input", $(this)).each(
	                function () {
	                    incName($(this),
	                        count);
	                });
                count++;
	        });
    	$("#muusaApp").submit();
	}
</script>
<script
   src='<?php echo JURI::root(true);?>/libraries/muusla/js/common.js'></script>
<script
   src='<?php echo JURI::root(true);?>/libraries/muusla/js/jquery.inputmask.bundle.min.js'></script>
<div id="ja-content">
   <div class="componentheading">Preregistrations Workbook</div>
   <table class="blog">
      <tr>
         <td valign='top'>
            <div>
               <div class='contentpaneopen'>
                  <h2 class='contentheading'>Preregistrations Workbook</h2>
               </div>
               <div class='article-content'>
                  <h4>Important: each preregistered camper must have a
                     preregistration charge greater or equal to their
                     registration fee.</h4>
                  <form id="muusaApp" method="post">
                     <button id="carryheader">
                        View Carryover Charges from
                        <?php echo $this->years[0]?>
                     </button>
                     <table>
                        <thead>
                           <tr>
                              <th>Chargetype</th>
                              <th>Name</th>
                              <th>Amount</th>
                              <th>Date</th>
                              <th>Memo</th>
                              <th>&nbsp;</th>
                           </tr>
                        </thead>
                        <tbody id="carryovers" style="display: none;">
                           <?php foreach($this->carrys as $carry) {
                              echo "                  <tr>\n";
                              echo "                      <td>Carryover</td>\n";
                              echo "                      <td>$carry->firstname $carry->lastname</td>\n";
                              echo "                      <td>$" . number_format($carry->amount, 2) . "</td>\n";
                              echo "                      <td>$carry->timestamp</td>\n";
                              echo "                      <td>$carry->memo</td>\n";
                              echo "                      <td>&nbsp;</td>\n";
                              echo "                  </tr>\n";
                           }?>
                           <tr>
                              <td colspan="5"><hr /></td>
                           </tr>
                        </tbody>
                        <tbody>
                           <?php 
                           foreach($this->preregs as $charge) {
                              include 'blocks/charge.php';
                           }
                           $charge = new stdClass;
                           $charge->id = 0;
                           include 'blocks/charge.php';
                           $charge = new stdClass;
                           $charge->id = -1;
                           include 'blocks/charge.php';
                           ?>
                        </tbody>
                     </table>
                     <span class='article_separator'>&nbsp;</span>
                     <div align="right">
                        <button id="save">Save Preregistrations</button>
                     </div>
                  </form>
               </div>
            </div>
         </td>
      </tr>
   </table>
</div>
