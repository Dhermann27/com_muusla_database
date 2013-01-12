<?php defined('_JEXEC') or die('Restricted access'); ?>
<div id="ja-content">
<div class="componentheading">MUUSA Rooms</div>
<div>This table is for creating rooms either as housing or event
locations.</div>
<form
	action="index.php?option=com_muusla_database&task=save&view=rooms&Itemid=58"
	method="post">
<table class="blog" cellpadding="0" cellspacing="0">
<?php
foreach($this->rooms as $roomgroup) {
   echo "      <tr>\n";
   echo "         <td valign='top'>\n";
   echo "            <div>\n";
   echo "            <div class='contentpaneopen'>\n";
   echo "               <h2 class='contentheading'>$roomgroup->buildingname</h2>\n";
   echo "            </div>\n";
   foreach($roomgroup->rooms as $room) {
      echo "            <div class='article-content'>\n";
      echo "               Room: <input type='text' name='roomnbr-$room->roomid' size='10' value='$room->roomnbr' />\n";
      echo "               Capacity: <input type='text' name='roomcapacity-$room->roomid' size='5' value='$room->capacity' />\n";
      if($room->is_workshop == "1") {
         $chked = "checked";
      } else {
         $chked = "";
      }
      echo "               Workshop? <input type='checkbox' name='roomworkshop-$room->roomid' value='1' $chked />\n";
      if($room->is_handicap == "1") {
         $chked = "checked";
      } else {
         $chked = "";
      }
      echo "               Handicap? <input type='checkbox' name='roomhandicap-$room->roomid' value='1' $chked />\n";
      echo "               Delete Room <input type='checkbox' name='roomdelete-$room->roomid' value='delete' />\n";
      echo "            </div>\n";
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
		<h2 class="contentheading">New Room</h2>
		</div>
		<div class="article-content"><?php
		echo "               Building: <select name='roombuilding-0'>\n";
		foreach($this->buildings as $building) {
		   echo "                  <option value='$building->buildingid'>$building->name</option>\n";
		}
		echo "               </select>\n";
		?> New Number: <input type='text' name='roomnbr-0' size='10' />
		Capacity: <input type='text' name='roomcapacity-0' size='5' />
		Workshop? <input type='checkbox' name='roomworkshop-0' value='1' />
		Handicap? <input type='checkbox' name='roomhandicap-0' value='1' /></div>
		<span class="article_separator">&nbsp;</span></div>
		</td>
	</tr>
</table>
<input type="submit" value="Save" class="Button" /></form>
</div>
