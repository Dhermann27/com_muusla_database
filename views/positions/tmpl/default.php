<?php defined('_JEXEC') or die('Restricted access'); ?>
<div id="ja-content">
   <div class="componentheading">MUUSA Positions</div>
   <div>
      This table is for positions that can be applied to campers for
      duties performed (PC Member, Cratty Counselor, etc.) Use $999.00
      to cover all costs.<br />
      <form
         action="index.php?option=com_muusla_database&task=save&view=positions&Itemid=54"
         method="post">
         <table class="blog" cellpadding="0" cellspacing="0">
            <?php
            foreach($this->positions as $position) {
               echo "      <tr>\n";
               echo "         <td valign='top'>\n";
               echo "            <div>\n";
               echo "            <div class='contentpaneopen'>\n";
               echo "               <h2 class='contentheading'>$position->name</h2>\n";
               echo "            </div>\n";
               echo "            <div class='article-content'>\n";
               echo "               Name: <input type='text' name='positionname-$position->positionid' size='20' value='$position->name' />\n";
               echo "               Registration Amount: $<input type='text' name='positionregamount-$position->positionid' size='10' value='$position->regamount' />\n";
               echo "               Housing Amount: $<input type='text' name='positionhouseamount-$position->positionid' size='10' value='$position->houseamount' />\n";
               if($position->is_shown == "1") {
                  $checked = " checked";
               } else {
                  $checked = "";
               }
               echo "               Shown on Form: <input type='checkbox' name='positionshown-$position->positionid' value='1'$checked />\n";
               echo "               Delete Position <input type='checkbox' name='positiondelete-$position->positionid' value='delete' />\n";
               echo "            </div>\n";
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
                        <h2 class="contentheading">New Position</h2>
                     </div>
                     <div class="article-content">
                        New Name: <input type='text'
                           name='positionname-0' size='20' />
                        Registration Amount: $<input type='text'
                           name='positionregamount-0' size='10'
                           value='0.00' /> Housing Amount: $<input
                           type='text' name='positionhouseamount-0'
                           size='10' value='0.00' /> Shown on Form: <input
                           type='checkbox' name='positionshown-0'
                           value='1' />
                     </div>
                     <span class="article_separator">&nbsp;</span>
                  </div>
               </td>
            </tr>
         </table>
         <input type="submit" value="Save" class="btn" />
      </form>
   </div>
</div>
