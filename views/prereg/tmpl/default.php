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
                     registration fee. Preregistration fees must be have
                     been timestamped before the cutoff of the previous
                     year, usually Sep 30.</h4>
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
                              <td colspan="6"><hr /></td>
                           </tr>
                        </tbody>
                        <tbody>
                           <?php 
                           foreach($this->preregs as $charge) {?>
                           <tr>
                              <td><?php 
                              foreach($this->chargetypes as $chargetype) {
                                 if($charge->chargetypeid == $chargetype->id) {
                                    echo "          $chargetype->name\n";
                                 }
                              }?>
                              </td>
                              <td><?php echo "$charge->lastname, $charge->firstname ($charge->familyname)" ?>
                              </td>
                              <td align="right">$<?php echo number_format($charge->amount, 2);?>
                              </td>
                              <td align="center"><?php echo $charge->timestamp != "" ? $charge->timestamp : date("m/d/Y");?>
                              </td>
                              <td><?php echo $charge->memo;?>
                              </td>
                              <td nowrap="nowrap"><input type="checkbox"
                                 name="charge-delete-<?php echo $charge->id;?>" />
                                 Delete</td>
                           </tr>
                           <?php }
                           if($this->admin) {?>
                           <tr class="prereg">
                              <td><select name="charge-chargetypeid-"
                                 class="ui-corner-all">
                                    <option value="0">Charge Type</option>
                                    <?php foreach($this->chargetypes as $chargetype) {
                                       echo "          <option value='$chargetype->id'>$chargetype->name</option>\n";
                                    }?>
                              </select>
                              </td>
                              <td><input type="text"
                                 class="inputtext camperlist ui-corner-all" /><input
                                 type="hidden" name="charge-camperid-"
                                 class="camperlist-value" />
                              </td>
                              <td align="right"><input type="text"
                                 name="charge-amount-"
                                 class="inputtexttiny onlymoney ui-corner-all" />
                              </td>
                              <td align="center"><input type="text"
                                 name="charge-timestamp-"
                                 class="inputtexttiny birthday validday ui-corner-all"
                                 value="<?php echo date("m/d/Y");?>" /><input
                                 type="hidden" name="charge-year-"
                                 value="<?php echo $this->years[1];?>" />
                              </td>
                              <td><input type="text" name="charge-memo-"
                                 class="inputtext ui-corner-all" />
                              </td>
                              <td>
                                 <button class="add help">Add</button>
                              </td>
                           </tr>
                           <?php }?>
                           <tr>
                              <td colspan="6"><hr /></td>
                           </tr>
                        </tbody>
                        <?php if($this->admin) {?>
                        <tfoot>
                           <tr>
                              <td align="right" colspan="6">
                                 <button id="save">Save Preregistrations</button>
                              </td>
                           </tr>
                        </tfoot>
                        <?php }?>
                     </table>
                     <span class='article_separator'>&nbsp;</span>
                  </form>
               </div>
            </div>
         </td>
      </tr>
   </table>
</div>
