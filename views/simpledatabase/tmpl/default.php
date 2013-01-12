<?php defined('_JEXEC') or die('Restricted access'); ?>
<div id="ja-content"><?php
echo "<div class='componentheading'>MUUSA " . ucfirst($this->muusla->name) . "</div>\n";
echo "<form action='index.php?option=com_muusla_database&task=save&view=simpledatabase&Itemid=59' method='post'>\n";
echo "<table class='blog' cellpadding='0' cellspacing='0'>\n";
echo "<input type='hidden' name='table' value='" . $this->muusla->name . "' />\n";
foreach($this->muusla->items as $item) {
   echo "      <tr>\n";
   echo "         <td valign='top'>\n";
   echo "            <div>\n";
   echo "            <div class='contentpaneopen'>\n";
   if($item->id == 0) {
      echo "               <h2 class='contentheading'>Add New</h2>\n";
   } else {
      echo "               <h2 class='contentheading'>$item->name</h2>\n";
   }
   echo "            </div>\n";
   echo "            <div class='article-content'>\n";
   echo "               Name: <input type='text' name='name-$item->id' size='50' value='$item->name' />\n";
   echo "               Delete <input type='checkbox' name='delete-$item->id' value='delete' />\n";
   echo "            </div>\n";
   echo "            <span class='article_separator'>&nbsp;</span>\n";
   echo "            </div>\n";
   echo "         </td>\n";
   echo "      </tr>\n";
}
echo "</table>\n";
echo "<input type='submit' value='Save' class='Button' />\n";
echo "</form>\n";
?></div>
