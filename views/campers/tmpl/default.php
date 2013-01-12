<?php defined('_JEXEC') or die('Restricted access'); ?>
<div id="ja-content">
<div class="componentheading">MUUSA Campers</div>
<div>This table is for the every camper at MUUSA. Primary campers
have their city/state listed.<br />
</div>
<form
	action="index.php?option=com_muusla_database&task=editcamper&view=campers&Itemid=70"
	method="post" onsubmit="return !document.getElementById('cdel').checked || confirm('Are you sure you want to delete this camper?');">
<table class="blog" cellpadding="0" cellspacing="0">
<?php
echo "      <tr>\n";
echo "         <td valign='top'>\n";
echo "            <div>\n";
echo "            <div class='contentpaneopen'>\n";
echo "               <h2 class='contentheading'>Create/Edit Camper</h2>\n";
echo "            </div>\n";
echo "            <div class='article-content'>\n";
echo "               <select name='editcamper'>\n";
echo "                  <option value='0' selected>Create New Camper</option>\n";
foreach ($this->campers as $camper) {
	echo "                  <option value='$camper->camperid'>$camper->lastname, $camper->firstname";
	if($camper->hohid == 0) {
		echo " ($camper->city, $camper->statecd)";
	}
	echo "</option>\n";
}
echo "               </select>\n";
echo "               Delete Camper <input type='checkbox' id='cdel' name='camperdelete' value='delete' />\n";
echo "            </div>\n";
echo "            <input type='submit' value='Continue' class='Button' /></form>\n";
echo "            </div>\n";
echo "            <span class='article_separator'>&nbsp;</span>\n";
echo "         </td>\n";
echo "      </tr>\n";
?>
</table>
</form>
</div>
