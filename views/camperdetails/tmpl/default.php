<?php defined('_JEXEC') or die('Restricted access'); ?>
<script
	type='text/javascript' language='Javascript'
	src='components/com_muusla_application/js/application.js'></script>
<script type='text/javascript'>
   function clearAll(obj) {
      for(var i=0; i<obj.options.length; i++) {
          obj.options[i].selected = false;
          }
      }
</script>
<div id="ja-content">
<div class="componentheading">MUUSA Camper Detail</div>
<div>This table is for assigning camper attendance, positions,
workshops, and charges made against the account.<br />
Zero days attended means forfeited registration fee.
<form name="application"
	action="index.php?option=com_muusla_database&task=save&view=camperdetails&Itemid=71"
	method="post">
<table class="blog" cellpadding="0" cellspacing="0">
<?php
echo "      <tr>\n";
echo "         <td valign='top'>\n";
echo "            <div>\n";
if($this->camper) {
	$camper = $this->camper;
	echo "            <div class='contentpaneopen'>\n";
	echo "               <h2 class='contentheading'>$camper->fullname</h2>\n";
	echo "            </div>\n";
	echo "            <div class='article-content'>\n";
	echo "            <h3>Fiscal Year Attendance</h3>\n";
	$found = 0;
	if(count($this->fiscalyears) > 0) {
		foreach ($this->fiscalyears as $fiscalyear) {
			echo "            <p>Fiscal Year: <b>$fiscalyear->fiscalyear</b>\n";
			if($fiscalyear->fiscalyear == $this->year) {
				$found = 1;
				echo "            Postmark (MM/DD/YYYY): <input type='text' name='fiscalyear-postmark-$fiscalyear->fiscalyearid' value='$fiscalyear->postmark' size='10' />\n";
				echo "            <br />Room: <select name='fiscalyear-roomid-$fiscalyear->fiscalyearid'>\n";
				echo "               <option value='0'>Unassigned</option>\n";
				foreach ($this->buildings as $building) {
					echo "               <optgroup label='$building->buildingname'>\n";
					foreach($building->rooms as $room) {
						if($room->is_handicap == "1") {
							$ishandicap = " (Handicapped)";
						} else {
							$ishandicap = "";
						}
						if($room->roomid == $fiscalyear->roomid) {
							$selected = " selected";
						} else {
							$selected = "";
						}
						if($room->current == "0") {
							$style = "";
						} else {
							$style = " style='background-color:IndianRed'";
						}
						echo "                  <option value='$room->roomid'$selected$style>$room->roomnbr ($room->current / $room->capacity) $ishandicap</option>\n";
					}
					echo "               </optgroup>\n";
				}
				echo "            </select>\n";
				echo "            Days Attending: <select name='fiscalyear-days-$fiscalyear->fiscalyearid'>\n";
				for($i=9; $i>=0; $i--) {
					if($i == $fiscalyear->days || ($fiscalyear->days == 0 && $i == 6)) {
						$selected = " selected";
					} else {
						$selected = "";
					}
					echo "                  <option value='$i'$selected>$i</option>\n";
				}
				echo "            </select>\n";
				echo "            <input type='hidden' name='fiscalyear-fiscalyear-$fiscalyear->fiscalyearid' value='$this->year' />\n";
				echo "            Camper Not Attending: <input type='checkbox' name='fiscalyear-delete-$fiscalyear->fiscalyearid' value='delete' /></p>\n";
			} else {
				echo "            Postmark: $fiscalyear->postmark\n";
				echo "            Room: $fiscalyear->roomnbr, $fiscalyear->days days</p>\n";
			}
		}
	}
	if($found == 0)
	{
		echo "            <p>Create new attendance record for $this->year? <input type='checkbox' name='fiscalyear-fiscalyear-0' value='$this->year' />\n";
		echo "               <input type='hidden' name='fiscalyear-camperid-0' value='$camper->camperid' />\n";
		echo "               Room: <select name='fiscalyear-roomid-0'>\n";
		echo "                  <option value='0'>Unassigned</option>\n";
		foreach ($this->buildings as $building) {
			echo "                  <optgroup label='$building->buildingname'>\n";
			foreach($building->rooms as $room) {
				if($room->is_handicap == "1") {
					$ishandicap = " (Handicapped)";
				} else {
					$ishandicap = "";
				}
				if($room->roomid == $camper->roomid) {
					$selected = " selected";
				} else {
					$selected = "";
				}
				if($room->current == "0") {
					$style = "";
				} else {
					$style = " style='background-color:IndianRed'";
				}
				echo "                     <option value='$room->roomid'$selected$style>$room->roomnbr ($room->current / $room->capacity) $ishandicap</option>\n";
			}
			echo "                  </optgroup>\n";
		}
		echo "               </select><br />\n";
		echo "            Postmark (MM/DD/YYYY): <input type='text' name='fiscalyear-postmark-0' size='10' /></p>\n";
	}
	echo "            <h3>Positions Held</h3>\n";
	echo "            <input type='hidden' name='volunteers-positionid-$camper->camperid' value='0' />\n";
	echo "            <select name='volunteers-positionid-$camper->camperid[]' size='10' multiple>\n";
	$vol = "";
	foreach($this->positions as $position) {
		echo "            <option value='$position->positionid'$position->selected>" . preg_replace('/^\(/', '(V', $position->name) . "</option>\n";
	}
	echo "            </select> <br />Hold CTRL to select multiple positions. \n";
	echo "            <input type='button' value='Clear All' onclick='clearAll(document.getElementsByName(\"volunteers-positionid-$camper->camperid[]\")[0]);' />\n";
	echo "            <div align='right'><input type='button' value='Save' class='Button' onclick='selectSubmit()' /></div>\n";
	echo "            <table width='100%'>\n";
	echo "               <tr>\n";
	echo "                  <td colspan='4'><h3>Workshop Preference</h3></td>\n";
	echo "               </tr>\n";
	$choicenbrs = array();
	if($camper->choices) {
		foreach($camper->choices as $choice) {
			array_push($choicenbrs, $choice->eventid);
		}
	}
	if($camper->workshop) {
		echo "               <tr>\n";
		echo "                  <td colspan='4'><i>$camper->workshop</i>\n";
		echo "                  <input type='hidden' name='selected-0-$camper->camperid' value='LEAD' /></td>\n";
		echo "               </tr>\n";
	} else {
		$count = 1;
		foreach($this->workshops as $timename => $shops) {
			$available = "";
			$leader = " ";
			$selected = array();
			foreach($shops as $shop) {
				if(!$camper->choices || array_search($shop->eventid, $choicenbrs) === false) {
					$available .= "                     <option value='$shop->eventid'>$shop->shopname ($shop->days)</option>\n";
				} else {
					$eventlead = $shop->eventid;
					if($camper->choices[array_search($shop->eventid, $choicenbrs)]->is_leader) {
						$shop->shopname .= " (Leader)";
						$leader = " checked";
						$eventlead = "LEAD";
					}
					$selected[$shop->eventid] = "                     <option value='$eventlead'>$shop->shopname ($shop->days)</option>\n";
				}
			}
			echo "               <tr>\n";
			echo "                  <td colspan='4'>$timename</td>\n";
			echo "               </tr>\n";
			echo "               <tr valign='top'>\n";
			echo "                  <td width='40%'><select name='available-$count-$camper->camperid[]' multiple='multiple' size='" . max(min(count($shops),5),2) . "' style='width: 100%;'>\n";
			echo $available;
			echo "                     </select></td>\n";
			echo "                  <td width='10%' valign='middle'><input type='button' value='Add >>' onclick='listbox_moveto(\"$count-$camper->camperid\")' ondblclick='listbox_moveto(\"$count-$camper->camperid\")' style='width: 100%; font-size: x-small;' /><br />\n";
			echo "                     <input type='button' value='<< Remove' onclick='listbox_movefrom(\"$count-$camper->camperid\")' ondblclick='listbox_movefrom(\"$count-$camper->camperid\")' style='width: 100%; font-size: x-small;' /></td>\n";
			echo "                  <td width='40%'><select name='selected-$count-$camper->camperid[]' multiple='multiple' size='" . max(min(count($shops),5),2) . "' style='width: 100%;'>\n";
			if($camper->choices) {
				foreach($camper->choices as $choice) {
					echo $selected[$choice->eventid];
				}
			}
			echo "                     </select><br />\n";
			echo "                     <input type='checkbox' name='leader-$count-$camper->camperid' value='1'$leader/> Leader of selected workshops?</td>\n";
			echo "                  <td width='10%' valign='middle'><input type='button' value='Move Up' onclick='listbox_up(\"$count-$camper->camperid\")' style='width: 100%; font-size: x-small;' /><br />\n";
			echo "                     <input type='button' value='Move Down' onclick='listbox_down(\"" . $count++ . "-$camper->camperid\")' style='width: 100%; font-size: x-small;' /></td>\n";
			echo "               </tr>\n";
		}
	}
	$checked = "";
	if(array_search(1028, $choicenbrs) !== false) {
		$checked = " checked";
	}
	echo "               <tr>\n";
	echo "                  <td colspan='4'><input type='checkbox' name='selected-997-$camper->camperid' value='1028'$checked/>\n";
	echo "                  St. Louis City Museum, Saturday June 30th 2:00 PM - Midnight ($6).</td>\n";
	echo "               </tr>\n";
	$checked = "";
	if(array_search(1029, $choicenbrs) !== false) {
		$checked = " checked";
	}
	echo "               <tr>\n";
	echo "                  <td colspan='2'><input type='checkbox' name='selected-998-$camper->camperid' value='1029'$checked/>\n";
	echo "                  River Float Trip, Tuesday 9:50 AM - 5:00 PM ($35).</td>\n";
	$checked = "";
	if(array_search(1030, $choicenbrs) !== false) {
		$checked = " checked";
	}
	echo "                  <td colspan='2'><input type='checkbox' name='selected-999-$camper->camperid' value='1030'$checked/>\n";
	echo "                  Onondaga Cave State Park, Wednesday 1:00 PM - 5:00 PM ($25).</td>\n";
	echo "               </tr>\n";
	echo "            </table>\n";
	echo "            <div align='right'><input type='button' value='Save' class='Button' onclick='selectSubmit()' /></div>\n";
	echo "            <h3>Camper Balance Sheet</h3>\n";
	if($camper->hohid != "0") {
		echo "            <b>To see this camper's charges, view the <a href='index.php?option=com_muusla_database&task=save&view=camperdetails&Itemid=71&editcamper=$camper->hohid'>Primary Camper Details</a>.</b>\n";
	} else {
		echo "            <table width='98%' align='center'>\n";
		echo "               <tr align='center'>\n";
		echo "                  <td>Charge Type</td>\n";
		echo "                  <td>Amount</td>\n";
		echo "                  <td>Date</td>\n";
		echo "                  <td>Memo</td>\n";
		echo "                  <td>Delete</td>\n";
		echo "               </tr>\n";
		echo "               <tr><td colspan='5'><h3>$this->year</h3></td></tr>\n";
		if(count($this->charges[$this->year]) > 0) {
			$yearcharges = $this->charges[$this->year];
			$total = 0.0;
			foreach($yearcharges as $charge) {
				echo "           <tr>\n";
				if($charge->memo != "Super 8 (S8)" ) {
					echo "                   <td>$charge->name</td>\n";
				} else {
					echo "                   <td>Commuter Fees</td>\n";
				}
				echo "                   <td align='right'>\$" . number_format($charge->amount,2) . "</td>\n";
				echo "                   <td align='center'>$charge->timestamp</td>\n";
				$total += (float)preg_replace("/,/", "",  $charge->amount);
				echo "                   <td><i>$charge->memo</i></td>\n";
				echo "                   <td align='center'>\n";
				echo "                      <input type='checkbox' name='charges-delete-$charge->chargeid' value='delete' />\n";
				echo "                   </td>\n";
				echo "                </tr>\n";
			}
			if(count($this->credits[$this->year]) > 0) {
				foreach($this->credits[$this->year] as $credit) {
					echo "           <tr>\n";
					echo "                  <td>Credit</td>\n";
					$total -= $credit->registration_amount + $credit->housing_amount;
					echo "                   <td align='right'>\$-" . number_format($credit->registration_amount + $credit->housing_amount, 2) . "</td>\n";
					echo "                   <td>&nbsp;</td>\n";
					echo "                   <td><i>$credit->name</i></td>\n";
					echo "                   <td align='center'>&nbsp;</td>\n";
					echo "                </tr>\n";
				}
			}
		}
		echo "                 <tr>\n";
		echo "                    <td><select name='charges-chargetypeid-0' style='font-size: .9em;'>\n";
		foreach($this->chargetypes as $chargetype) {
			echo "                         <option value='$chargetype->chargetypeid'>$chargetype->name</option>\n";
		}
		echo "                    </select></td>\n";
		echo "                    <td align='center' nowrap='nowrap'>\$<input type='text' name='charges-amount-0' value='0.00' size='5' />\n";
		echo "                       <input type='hidden' name='charges-fiscalyear-0' value='$this->year' />\n";
		echo "                       <input type='hidden' name='charges-camperid-0' value='$camper->camperid' /></td>\n";
		echo "                    <td><input type='text' name='charges-timestamp-0' size='10' /></td>\n";
		echo "                    <td colspan='2'><input type='text' name='charges-memo-0' size='20' /></td>\n";
		echo "                 </tr>\n";
		echo "                 <tr>\n";
		echo "                    <td colspan='5' align='right'>Erase current charges and recalculate initial fees? <input type='checkbox' name='charges-recalc-$camper->camperid' value='recalc' /></td>\n";
		echo "                 </tr>\n";
		echo "                    <tr>\n";
		echo "                       <td align='right'>Amount Due From Camper:</td>\n";
		echo "                       <td><h4>$" . number_format($total, 2, '.', '') . "</h4></td>\n";
		echo "                       <td colspan='3' align='right'>\n";
		echo "                       </td>\n";
		echo "                    </tr>\n";
		echo "                 <tr><td colspan='5' align='right'><input type='button' value='Save' class='Button' onclick='selectSubmit()' /></td></tr>\n";
		unset($this->charges[$this->year]);
		unset($this->credits[$this->year]);
		if(count($this->charges) > 0) {
			$mykeys = array_keys($this->charges);
			rsort($mykeys);
			foreach($mykeys as $fiscalyear) {
				$yearcharges = $this->charges[$fiscalyear];
				$total = 0.0;
				$currentyear = ($fiscalyear == $this->year);
				echo "           <tr><td colspan='5'><h3>$fiscalyear</h3></td></tr>\n";
				foreach($yearcharges as $charge) {
					echo "           <tr>\n";
					if($charge->memo != "Super 8 (S8)" ) {
						echo "                   <td>$charge->name</td>\n";
					} else {
						echo "                   <td>Commuter Fees</td>\n";
					}
					echo "                   <td align='right'>\$" . number_format($charge->amount,2) . "</td>\n";
					echo "                   <td align='center'>$charge->timestamp</td>\n";
					$total += (float)preg_replace("/,/", "",  $charge->amount);
					echo "                   <td><i>$charge->memo</i></td>\n";
					echo "                   <td align='center'>\n";
					if($currentyear) {
						echo "                      <input type='checkbox' name='charges-delete-$charge->chargeid' value='delete' />\n";
					}
					echo "                   </td>\n";
					echo "                </tr>\n";
				}
				if(count($this->credits[$fiscalyear]) > 0) {
					foreach($this->credits[$fiscalyear] as $credit) {
						echo "           <tr>\n";
						echo "                  <td>Credit</td>\n";
						$total -= $credit->registration_amount + $credit->housing_amount;
						echo "                   <td align='right'>\$-" . number_format($credit->registration_amount + $credit->housing_amount, 2) . "</td>\n";
						echo "                   <td>&nbsp;</td>\n";
						echo "                   <td><i>$credit->name</i></td>\n";
						echo "                   <td align='center'>&nbsp;</td>\n";
						echo "                </tr>\n";
					}
				}
				echo "                    <tr>\n";
				echo "                       <td align='right'>Amount Due From Camper:</td>\n";
				echo "                       <td><h4>$" . number_format(max($total,0), 2, '.', '') . "</h4></td>\n";
				echo "                       <td colspan='3' align='right'>\n";
				echo "                       </td>\n";
				echo "                    </tr>\n";
			}
		}
		echo "                 </table>\n";
	}
	echo "            <input type='hidden' name='editcamper' value='$camper->camperid' />\n";
	echo "            </div>\n";
	echo "            <span class='article_separator'>&nbsp;</span>\n";
	echo "            </div>\n";
} else {
	echo "            <div class='contentpaneopen'>\n";
	echo "               <h2 class='contentheading'>Choose Camper</h2>\n";
	echo "            </div>\n";
	echo "            <div class='article-content'>\n";
	echo "               <select name='editcamper'>\n";
	echo "                  <option value='0' selected>Select a Camper</option>\n";
	foreach ($this->campers as $camper) {
		echo "                  <option value='$camper->camperid'>$camper->lastname, $camper->firstname";
		if($camper->hohid == 0) {
			echo " ($camper->city, $camper->statecd)";
		}
		echo "</option>\n";
	}
	echo "               </select>\n";
	echo "            </div>\n";
	echo "            <span class='article_separator'>&nbsp;</span>\n";
	echo "            </div>\n";
	echo "            <div align='right'><input type='submit' value='Continue' class='Button' /></div>\n";
}
echo "         </td>\n";
echo "      </tr>\n";
?>
</table>
</form>
</div>
</div>
