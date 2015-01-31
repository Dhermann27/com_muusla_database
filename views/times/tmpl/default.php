<?php defined('_JEXEC') or die('Restricted access'); ?>
<div id="ja-content">
   <div class="componentheading">MUUSA Timeslots</div>
   <div>
      This table is for the common timeslots of MUUSA events (Morning
      Celebration, Afternoon Workshops, etc.)<br /> <b>NOTE: Start times
         should be listed in HH:MM AM format, and only at 00/15/30/45
         minutes. Length should be duration, in hours (to the nearest
         quarter).</b>
   </div>
   <form
      action="index.php?option=com_muusla_database&task=save&view=times&Itemid=60"
      method="post">
      <table class="blog" cellpadding="0" cellspacing="0">
         <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
         </tr>
         <?php
         foreach($this->times as $time) {
            echo "      <tr>\n";
            echo "         <td valign='top' colspan='4'>\n";
            echo "            <div class='contentpaneopen'>\n";
            echo "               <h2 class='contentheading'>$time->name</h2>\n";
            echo "            </div>\n";
            echo "         </td>\n";
            echo "      </tr>\n";
            echo "      <tr>\n";
            echo "         <td><div class='article-content'>New Name: <input type='text' name='timename-$time->timeid' size='20' value='$time->name' /></div></td>\n";
            echo "         <td><div class='article-content'>Start Time: <input type='text' name='timestart-$time->timeid' size='10' value='$time->starttime' /></div></td>\n";
            echo "         <td><div class='article-content'>Length: <input type='text' name='timelength-$time->timeid' size='5' value='$time->length' /></div></td>\n";
            echo "         <td><div class='article-content'>Delete Timeslot <input type='checkbox' name='timedelete-$time->timeid' value='delete' /></td>\n";
            echo "      </tr>\n";
            echo "      <tr>\n";
            echo "         <td valign='top' colspan='4'><span class='article_separator'>&nbsp;</span></td>\n";
            echo "      </tr>\n";
         }
         ?>
         <tr>
            <td valign="top" colspan="4">
               <div class="contentpaneopen">
                  <h2 class="contentheading">New Timeslot</h2>
               </div>
            </td>
         </tr>
         <tr>
            <td>
               <div class="article-content">
                  New Name: <input type='text' name='timename-0'
                     size='20' />
               </div>
            </td>
            <td>
               <div class="article-content">
                  Start Time: <input type='text' name='timestart-0'
                     size='10' value='9:50 AM' />
               </div>
            </td>
            <td>
               <div class="article-content">
                  Length: <input type='text' name='timelength-0'
                     size='5' value='2' />
               </div>
            </td>
            <td>&nbsp;</td>

         </tr>
         <tr>
      
      </table>
      <input type="submit" value="Save" class="Button" />
   </form>
</div>
