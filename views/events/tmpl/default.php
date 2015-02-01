<?php defined('_JEXEC') or die('Restricted access'); ?>
<div id="ja-content">
   <div class="componentheading">MUUSA Events</div>
   <div>
      This table is for creating events.<br /> <b>NOTE: Deleting a event
         will also delete all attendee records for that event!</b>
   </div>
   <form
      action="index.php?option=com_muusla_database&task=save&view=events&Itemid=68"
      method="post">
      <table class="blog" cellpadding="0" cellspacing="0">
         <?php
         foreach($this->events as $event) {
            echo "      <tr>\n";
            echo "         <td valign='top'>\n";
            echo "            <div>\n";
            echo "            <div class='contentpaneopen'>\n";
            echo "               <h2 class='contentheading'>$event->name</h2>\n";
            echo "            </div>\n";
            echo "            <div class='article-content'>\n";
            echo "               New Name: <input type='text' name='name-$event->eventid' size='10' value='$event->name' />\n";
            echo "               <select name='roomid-$event->eventid'>\n";
            $selected = "";
            foreach($this->rooms as $room) {
               if($room->roomid == $event->roomid) {
                  $selected = " selected";
               } else {
                  $selected = "";
               }
               echo "                  <option value='$room->roomid'$selected>$room->buildingname $room->roomnbr</option>\n";
            }
            echo "               </select><br />\n";
            echo "               Description: <input type='text' name='subname-$event->eventid' size='25' value='$event->subname' />\n";
            echo "               Capacity: <input type='text' name='capacity-$event->eventid' size='5' value='$event->capacity' />\n";
            echo "               Fee: $<input type='text' name='fee-$event->eventid' size='5' value='$event->fee' /><br />\n";
            echo "               <select name='timeid-$event->eventid'>\n";
            foreach($this->times as $time) {
               if($time->timeid == $event->timeid) {
                  $selected = " selected";
               } else {
                  $selected = "";
               }
               echo "                  <option value='$time->timeid'$selected>$time->name</option>\n";
            }
            echo "               </select>\n";
            echo "               Days: \n";
            echo "                  Su <input type='checkbox' name='su-$event->eventid' value='1'$event->su/>";
            echo "                  M <input type='checkbox' name='m-$event->eventid' value='1'$event->m/>";
            echo "                  T <input type='checkbox' name='t-$event->eventid' value='1'$event->t/>";
            echo "                  W <input type='checkbox' name='w-$event->eventid' value='1'$event->w/>";
            echo "                  Th <input type='checkbox' name='th-$event->eventid' value='1'$event->th/>";
            echo "                  F <input type='checkbox' name='f-$event->eventid' value='1'$event->f/>";
            echo "                  Sa <input type='checkbox' name='sa-$event->eventid' value='1'$event->sa/><br />";
            echo "               Delete Event <input type='checkbox' name='delete-$event->eventid' value='delete' />\n";
            echo "            </div>\n";
            echo "            <span class='article_separator'>&nbsp;</span>\n";
            echo "            </div>\n";
            echo "         </td>\n";
            echo "      </tr>\n";
         }
         ?>
         <tr>
            <td valign='top'>
               <div>
                  <div class='contentpaneopen'>
                     <h2 class='contentheading'>New Event</h2>
                  </div>
                  <div class='article-content'>
                     New Name: <input type='text' name='name-0'
                        size='10' /> <select name='roomid-0'>
                        <?php
                        foreach($this->rooms as $room) {
                           echo "                  <option value='$room->roomid'>$room->buildingname $room->roomnbr</option>\n";
                        }
                        ?>
                     </select><br /> Description: <input type='text'
                        name='subname-0' size='25' /> Capacity: <input
                        type='text' name='capacity-0' size='5' /> Fee: $<input
                        type='text' name='fee-0' size='5' value='0.00' /><br />
                     <select name='timeid-0'>
                        <?php
		foreach($this->times as $time) {
			echo "                  <option value='$time->timeid'>$time->name</option>\n";
		}
		?>
                     </select> Days: Su <input type='checkbox'
                        name='su-0' value='1' /> M <input
                        type='checkbox' name='m-0' value='1' /> T <input
                        type='checkbox' name='t-0' value='1' /> W <input
                        type='checkbox' name='w-0' value='1' /> Th <input
                        type='checkbox' name='th-0' value='1' /> F <input
                        type='checkbox' name='f-0' value='1' /> Sa <input
                        type='checkbox' name='sa-0' value='1' />
                  </div>
                  <span class='article_separator'>&nbsp;</span>
               </div>
            </td>
         </tr>
      </table>
      <input type="submit" value="Save" class="btn" />
   </form>
</div>
