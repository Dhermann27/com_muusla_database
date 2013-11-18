<?php defined('_JEXEC') or die('Restricted access');
/**
 * charge.php
 * XHTML Block containing the editable charge block
 *
 * @param   object  $charge The database charge object
 *
 */
$chargeid = $charge->id > 0 ? $charge->id : "";
?>
<tr <?echo $charge->id == -1 ? "class='hidden'" : "";?>>
   <td><select name="charge-chargetypeid-<? echo $chargeid;?>"
      class="ui-corner-all">
         <option value="0">Charge Type</option>
         <?php if($charge->id > 0) {
            echo "          <option value='delete' style='background-color: indianred'>Delete Preregistration</option>\n";
         }
         foreach($this->chargetypes as $chargetype) {
            $selected = $charge->chargetypeid == $chargetype->id ? " selected" : "";
            echo "          <option value='$chargetype->id'$selected>$chargetype->name</option>\n";
         }?>
   </select><input type="hidden"
      name="charge-id-<?php echo $chargeid;?>"
      value="<?php echo $chargeid;?>" />
   </td>
   <td><input type="text"
      class="inputtextshort camperlist ui-corner-all"
      <?php echo $charge->lastname ? "value='$charge->firstname $charge->lastname ($charge->city, $charge->statecd)' " : "";?> /><input
      type="hidden" name="charge-camperid-<? echo $chargeid;?>"
      class="camperlist-value" value="<?php echo $charge->camperid;?>" />
   </td>
   <td align="right"><input type="text"
      name="charge-amount-<? echo $chargeid;?>"
      class="inputtexttiny onlymoney ui-corner-all"
      value="<?php echo number_format($charge->amount, 2);?>" />
   </td>
   <td align="center"><input type="text"
      name="charge-timestamp-<? echo $chargeid;?>"
      class="inputtexttiny birthday validday ui-corner-all"
      value="<?php echo $charge->timestamp != "" ? $charge->timestamp : date("m/d/Y");?>" /><input
      type="hidden" name="charge-year-<?php echo $chargeid;?>"
      value="<?php echo $this->years[1];?>" />
   </td>
   <td><input type="text" name="charge-memo-<? echo $chargeid;?>"
      class="inputtext ui-corner-all"
      value="<?php echo $charge->memo;?>" />
   </td>
   <td><?php if($charge->id < 1) {?>
      <button
         class="<?php echo $charge->id == 0 ? "add" :"delete";?> help">
         <?php echo $charge->id == 0 ? "Add Roommate" : "Delete Roommate";?>
      </button> <?php }?>
      <input type="hidden" name="charge-changed-<?php echo $chargeid?>" />
   </td>
</tr>
