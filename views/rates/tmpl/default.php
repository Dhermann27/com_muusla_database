<?php defined('_JEXEC') or die('Restricted access'); ?>
<div id="ja-content">
   <div class="componentheading">MUUSA Rates</div>
   <div>This table is to create rates for each building, program, and
      occupancy level. 999 occupancy denotes 1 or more of adults or
      children in the room.</div>
   <form
      action="index.php?option=com_muusla_database&task=save&view=rates&Itemid=69"
      method="post">
      <table class="blog" cellpadding="0" cellspacing="0">
         <?php
         foreach($this->rategroups as $rategroup) {
            echo "      <tr>\n";
            echo "         <td valign='top'>\n";
            echo "            <div>\n";
            echo "            <div class='contentpaneopen'>\n";
            echo "               <h2 class='contentheading'>$rategroup->buildingname</h2>\n";
            echo "            </div>\n";
            foreach($rategroup->programs as $program) {
               echo "            <h3>$program->programname</h3>\n";
               foreach($program->rates as $rate) {
                  echo "            <div class='article-content'>\n";
                  echo "               # Adults: <input type='text' name='rateoccadult-$rate->rateid' size='5' value='$rate->occadult' />\n";
                  echo "               # Children: <input type='text' name='rateoccchild-$rate->rateid' size='5' value='$rate->occchild' />\n";
                  echo "               Amount: $<input type='text' name='rateamount-$rate->rateid' size='10' value='$rate->amount' />\n";
                  echo "               Delete Rate <input type='checkbox' name='ratedelete-$rate->rateid' value='delete' />\n";
                  echo "            </div>\n";
               }
            }
            echo "            <span class='article_separator'>&nbsp;</span>\n";
            echo "            </div>\n";
            echo "         </td>\n";
            echo "      </tr>\n";
         }
         ?>
         <tr>
            <td valign="top">
               <div>
                  <div class="contentpaneopen">
                     <h2 class="contentheading">New Rate</h2>
                  </div>
                  <div class="article-content">
                     <select name="ratebuildingid-0">
                        <?php
                        foreach($this->buildings as $building) {
                           echo "                  <option value='$building->buildingid'>$building->name</option>\n";
                        }
                        ?>
                     </select> <select name="rateprogramid-0">
                        <?php
		foreach($this->programs as $program) {
		   echo "                  <option value='$program->programid'>$program->name</option>\n";
		}
		?>
                     </select> # Adults: <input type='text'
                        name='rateoccadult-0' size='5' /> # Children: <input
                        type='text' name='rateoccchild-0' size='5' />
                     Amount: $<input type='text' name='rateamount-0'
                        size='10' value='0.00' />
                  </div>
                  <span class="article_separator">&nbsp;</span>
               </div>
            </td>
         </tr>
      </table>
      <input type="submit" value="Save" class="Button" />
   </form>
</div>
