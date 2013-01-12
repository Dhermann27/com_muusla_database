<?php defined('_JEXEC') or die('Restricted access'); ?>
<div id="ja-content">
<div class="componentheading">MUUSA Campers</div>
<div>This table is for the every camper at MUUSA.<br />
</div>
<form
	action="index.php?option=com_muusla_database&task=save&view=campers&Itemid=70"
	method="post">
<table class="blog" cellpadding="0" cellspacing="0">
<?php
if($this->deleted) {
	echo "      <tr>\n";
	echo "         <td valign='top'>\n";
	echo "            <div>\n";
	echo "            <div class='contentpaneopen'>\n";
	echo "               <h2 class='contentheading'>$this->deleted</h2>\n";
	echo "            </div>\n";
	echo "         </td>\n";
	echo "      </tr>\n";
} else {
	if($this->camper) {
		$camper = $this->camper;
		echo "      <input type='hidden' name='hohid' value='$camper->camperid' />\n";
	}
	echo "      <tr>\n";
	echo "         <td valign='top'>\n";
	echo "            <div>\n";
	echo "            <div class='contentpaneopen'>\n";
	echo "               <h2 class='contentheading'>$camper->firstname $camper->lastname\n";
	echo "               (<a href='index.php?option=com_muusla_database&task=save&view=camperdetails&Itemid=71&editcamper=$camper->camperid'>Details</a>)</h2>\n";
	echo "            </div>\n";
	echo "            <div class='article-content'>\n";
	echo "            <input type='hidden' name='campers-camperid-$camper->camperid' value='$camper->camperid' />\n";
	echo "            <table width='100%'>\n";
	echo "               <tr>\n";
	if($camper->sexcd == "M") {
		$sexcdm = "selected";
	} elseif($camper->sexcd == "F") {
		$sexcdf = "selected";
	}
	echo "                  <td><select name='campers-sexcd-$camper->camperid'>\n";
	echo "                     <option value='0'>Gender</option>\n";
	echo "                     <option value='M'$sexcdm>Male</option>\n";
	echo "                     <option value='F'$sexcdf>Female</option>\n";
	echo "                  </select></td>\n";
	echo "                  <td align='right'>First Name: </td>\n";
	echo "                  <td><input type='text' name='campers-firstname-$camper->camperid' value='$camper->firstname' size='25' maxlength='20' /></td>\n";
	echo "                  <td align='right'>Last Name: </td>\n";
	echo "                  <td><input type='text' name='campers-lastname-$camper->camperid' value='$camper->lastname' size='25' maxlength='30' /></td>\n";
	echo "               </tr>\n";
	echo "               <tr>\n";
	echo "                  <td colspan='2' align='right'>Primary Camper:</td>\n";
	echo "                  <td colspan='3'><select name='campers-hohid-$camper->camperid'>\n";
	echo "                        <option value='0'>$camper->lastname, $camper->firstname (Self)</option>\n";
	foreach ($this->hohs as $hoh) {
		if($hoh->camperid == $camper->hohid) {
			$selected = " selected";
		} else {
			$selected = "";
		}
		echo "                       <option value='$hoh->camperid' $selected>$hoh->lastname, $hoh->firstname ($hoh->city, $hoh->statecd)</option>\n";
	}
	echo "                     </select>\n";
	echo "                     <input type='button' value='Camper' onclick='if(document.getElementsByName(\"campers-hohid-$camper->camperid\")[0].selectedIndex != 0) window.location = \"index.php?option=com_muusla_database&task=editcamper&view=campers&Itemid=70&editcamper=\" + document.getElementsByName(\"campers-hohid-$camper->camperid\")[0].options[document.getElementsByName(\"campers-hohid-$camper->camperid\")[0].selectedIndex].value;' />\n";
	echo "                     <input type='button' value='Details' onclick='if(document.getElementsByName(\"campers-hohid-$camper->camperid\")[0].selectedIndex != 0) window.location = \"index.php?option=com_muusla_database&task=save&view=camperdetails&Itemid=71&editcamper=\" + document.getElementsByName(\"campers-hohid-$camper->camperid\")[0].options[document.getElementsByName(\"campers-hohid-$camper->camperid\")[0].selectedIndex].value;' /></td>\n";
	echo "               </tr>\n";
	echo "               <tr>\n";
	echo "                  <td colspan='2' align='right'>Address Line 1: </td>\n";
	echo "                  <td colspan='3'><input type='text' name='campers-address1-$camper->camperid' value='$camper->address1' size='50' maxlength='50' /></td>\n";
	echo "               </tr>\n";
	echo "               <tr>\n";
	echo "                  <td colspan='2' align='right'>Address Line 2: </td>\n";
	echo "                  <td colspan='3'><input type='text' name='campers-address2-$camper->camperid' value='$camper->address2' size='50' maxlength='50' /></td>\n";
	echo "               </tr>\n";
	echo "               <tr>\n";
	echo "                  <td colspan='2' align='right'>City: </td>\n";
	echo "                  <td colspan='3'><input type='text' name='campers-city-$camper->camperid' value='$camper->city' size='50' maxlength='30' /></td>\n";
	echo "               </tr>\n";
	echo "               <tr>\n";
	echo "                  <td colspan='2' align='right'>State: </td>\n";
	echo "                  <td colspan='3'><select name='campers-statecd-$camper->camperid'>\n";
	echo "                     <option value='0'>Select a State</option>\n";
	foreach($this->states as $state) {
		if($camper->statecd == $state->statecd) {
			$selected = " selected";
		} else {
			$selected = "";
		}
		echo "                     <option value='$state->statecd'$selected>$state->name</option>\n";
	}
	echo "                  </select>\n";
	echo "                  Zip: <input type='text' name='campers-zipcd-$camper->camperid' value='$camper->zipcd' size='10' maxlength='10' />\n";
	echo "                  Country: <input type='text' name='campers-country-$camper->camperid' value='$camper->country' ize='10' maxlength='40' /></td>\n";
	echo "               </tr>\n";
	echo "               <tr>\n";
	echo "                  <td colspan='2' align='right'>Email Address: </td>\n";
	echo "                  <td colspan='3'><input type='text' value='$camper->email' name='campers-email-$camper->camperid' size='40' /></td>\n";
	echo "               </tr>\n";
	echo "               <tr>\n";
	echo "                  <td colspan='2' align='right'><input type='checkbox' name='campers-is_ecomm-$camper->camperid' value='1'$camper->is_ecomm/></td>\n";
	echo "                  <td colspan='3'>Electronic communication</td>\n";
	echo "               </tr>\n";
	foreach($this->camper->phonenbrs as $phonenumber) {
		echo "               <tr>\n";
		echo "                  <td colspan='2' align='right'>Phone Number: </td>\n";
		echo "                  <td colspan='3'><select name='phonenumbers-phonetypeid-$phonenumber->phonenbrid'>\n";
		foreach($this->phonetypes as $phonetype) {
			if($phonenumber->phonetypeid == $phonetype->phonetypeid) {
				$selected = " selected";
			} else {
				$selected = "";
			}
			echo "                     <option value='$phonetype->phonetypeid'$selected>$phonetype->name</option>\n";
		}
		echo "                     </select>\n";
		echo "                     <input type='hidden' name='phonenumbers-camperid-$phonenumber->phonenbrid' value='$camper->camperid' />\n";
		echo "                     <input type='text' name='phonenumbers-phonenbr-$phonenumber->phonenbrid' value='$phonenumber->phonenbr' size='20' />\n";
		if($phonenumber->phonenbrid >= 1000) {
			echo "                     <input type='checkbox' name='phonenumbers-delete-$phonenumber->phonenbrid' value='delete' /> Delete this phone number\n";
		}
		echo "                  </td>\n";
		echo "               </tr>\n";
	}
	if($camper->birthdate) {
		$grade = $camper->age + $camper->gradeoffset;
	}
	echo "               <tr>\n";
	echo "                  <td colspan='2' align='right'>Birthdate (MM/DD/YYYY): </td>\n";
	echo "                  <td><input type='text' name='campers-birthdate-$camper->camperid' value='$camper->birthdate' size='20' maxlength='10' /></td>\n";
	echo "                  <td colspan='2'>Grade Entering in Fall 2012: \n";
	echo "                     <select name='campers-grade-$camper->camperid'>\n";
	echo "                        <option value='0'>Not Applicable</option>\n";
	echo "                        <option value='0'>Kindergarten or Earlier</option>\n";
	for($i=1; $i<13; $i++) {
		if($grade == $i) {
			$selected = " selected";
		} else {
			$selected = "";
		}
		echo "                        <option value='$i'$selected>$i</option>\n";
	}
	echo "                     </select>\n";
	echo "                  </td>";
	echo "               </tr>\n";
	if(!$camper->birthdate || $camper->hohid == "0") {
		for($i=1; $i<4; $i++) {
			eval("\$roompref = \$camper->roomprefid$i;");
			echo "               <tr>\n";
			echo "                  <td colspan='2' align='right'>Room Preference #$i:</td>\n";
			echo "                  <td colspan='3'><select name='campers-roomprefid$i-$camper->camperid'>\n";
			echo "                     <option value='0'>No Preference</option>\n";
			foreach ($this->buildings as $building) {
				if($building->buildingid == $roompref) {
					$selected = " selected";
				} else {
					$selected = "";
				}
				echo "                        <option value='$building->buildingid'$selected>$building->name</option>\n";
			}
			echo "                  </select></td>\n";
			echo "               </tr>\n";
		}
		for($i=1; $i<4; $i++) {
			eval("\$matepref = \$camper->matepref$i;");
			echo "               <tr>\n";
			echo "                  <td colspan='2' align='right'>Roommate Preference #$i: </td>\n";
			echo "                  <td colspan='3'><input type='text' name='campers-matepref$i-$camper->camperid' size='50' maxlength='50' value='$matepref' /></td>\n";
			echo "               </tr>\n";
		}
	}
	echo "               <tr>\n";
	echo "                  <td colspan='2' align='right'><input type='checkbox' name='campers-is_handicap-$camper->camperid' value='1'$camper->is_handicap/></td>\n";
	echo "                  <td colspan='3'>Handicap-accessible room</td>\n";
	echo "               </tr>\n";
	echo "               <tr>\n";
	echo "                  <td colspan='2' align='right'><input type='checkbox' name='campers-is_ymca-$camper->camperid' value='1'$camper->is_ymca/> YMCA</td>\n";
	echo "                  <td colspan='3'>Applying for scholarship</td>\n";
	echo "               </tr>\n";
	echo "               <tr>\n";
	echo "                  <td colspan='2' align='right'>Food Option: </td>\n";
	echo "                  <td colspan='3'><select name='campers-foodoptionid-$camper->camperid'>\n";
	foreach ($this->foodoptions as $foodoption) {
		if($foodoption->foodoptionid == $camper->foodoptionid) {
			$selected = " selected";
		} else {
			$selected = "";
		}
		echo "                  <option value='$foodoption->foodoptionid'$selected>$foodoption->name</option>\n";
	}
	echo "                  </select></td>\n";
	echo "               </tr>\n";
	echo "               <tr>\n";
	echo "                  <td colspan='2' align='right'>Sponsor:</td>\n";
	echo "                  <td colspan='3'><input type='text' value='$camper->sponsor' name='campers-sponsor-$camper->camperid' size='40' maxlength='50' />\n";
	echo "               </tr>\n";
	echo "               <tr>\n";
	echo "                  <td colspan='2' align='right'>Church Affiliation: </td>\n";
	echo "                  <td colspan='3'><select name='campers-churchid-$camper->camperid'>\n";
	echo "                     <option value='0'>No Affiliation</option>\n;";
	foreach ($this->churches as $church) {
		if($church->churchid == $camper->churchid) {
			$selected = " selected";
		} else {
			$selected = "";
		}
		echo "                  <option value='$church->churchid'$selected>$church->statecd - $church->city: $church->name</option>\n";
	}
	echo "                  </select></td>\n";
	echo "               </tr>\n";
	for($i=1; $i<7 && (!$camper->birthdate || $camper->hohid == "0") && ($camper->room == null || $i<=count($this->children)); $i++) {
		if($this->children[($i-1)]) {
			$child = $this->children[($i-1)];
			$childid = $child->camperid;
		} else {
			$child = new stdClass;
			$child->phonenbrs = array();
			for($j=0;$j<3;$j++) {
				$emptyNbr = new stdClass;
				$emptyNbr->phonenbrid = $i*10+$j;
				$emptyNbr->phonetypeid = 0;
				$emptyNbr->phonenbr = "";
				array_push($child->phonenbrs, $emptyNbr);
			}
			$childid = $i;
		}
		echo "               <tr>\n";
		echo "                  <td colspan='5'><hr /></td>\n";
		echo "               </tr>\n";
		echo "               <tr>\n";
		echo "                  <td colspan='5'><b>Spouse / Dependent #$i</b>\n";
		echo "                  </td>\n";
		echo "               </tr>\n";
		echo "               <tr>\n";
		if($child->sexcd == "M") {
			$sexcdm = " selected";
			$sexcdf = "";
		} elseif($child->sexcd == "F") {
			$sexcdm = "";
			$sexcdf = " selected";
		} else {
			$sexcdm = "";
			$sexcdf = "";
		}
		echo "                  <td><select name='campers-sexcd-$childid'>\n";
		echo "                     <option value='0'>Gender</option>\n";
		echo "                     <option value='M' $sexcdm>Male</option>\n";
		echo "                     <option value='F' $sexcdf>Female</option>\n";
		echo "                  </select></td>\n";
		echo "                  <td align='right'>First Name: </td>\n";
		echo "                  <td><input type='text' name='campers-firstname-$childid' value='$child->firstname' size='25' maxlength='20' /></td>\n";
		echo "                  <td align='right'>Last Name: </td>\n";
		echo "                  <td><input type='text' name='campers-lastname-$childid' value='$child->lastname' size='25' maxlength='30' />\n";
		echo "                     <input type='hidden' name='campers-hohid-$childid' value='$camper->camperid' /></td>\n";
		echo "               </tr>\n";
		echo "               <tr>\n";
		echo "                  <td colspan='2' align='right'>Email Address:</td>\n";
		echo "                  <td colspan='3'><input type='text' value='$child->email' name='campers-email-$childid' size='50' maxlength='30' />\n";
		echo "               </tr>\n";
		foreach($child->phonenbrs as $phonenumber) {
			echo "               <tr>\n";
			echo "                  <td colspan='2' align='right'>Phone Number: </td>\n";
			echo "                  <td colspan='3'><select name='phonenumbers-phonetypeid-$phonenumber->phonenbrid'>\n";
			foreach($this->phonetypes as $phonetype) {
				if($phonenumber->phonetypeid == $phonetype->phonetypeid) {
					$selected = " selected";
				} else {
					$selected = "";
				}
				echo "                     <option value='$phonetype->phonetypeid'$selected>$phonetype->name</option>\n";
			}
			echo "                     </select>\n";
			echo "                     <input type='hidden' name='phonenumbers-camperid-$phonenumber->phonenbrid' value='$childid' />\n";
			echo "                     <input type='text' name='phonenumbers-phonenbr-$phonenumber->phonenbrid' value='$phonenumber->phonenbr' size='20' />\n";
			if($phonenumber->phonenbrid >= 1000) {
				echo "                     <input type='checkbox' name='phonenumbers-delete-$phonenumber->phonenbrid' value='delete' /> Delete this phone number\n";
			}
			echo "                  </td>\n";
			echo "               </tr>\n";
		}
		echo "               <tr>\n";
		echo "                  <td colspan='2' align='right'>Birthdate (MM/DD/YYYY): </td>\n";
		echo "                  <td><input type='text' name='campers-birthdate-$childid' value='$child->birthdate' size='20' maxlength='10' /></td>\n";
		$grade = "";
		if($child->birthdate) {
			$grade = $child->age + $child->gradeoffset;
		}
		echo "                  <td colspan='2'>Grade Entering in Fall 2012: \n";
		echo "                     <select name='campers-grade-$childid'>\n";
		echo "                        <option value='0'>Not Applicable</option>\n";
		echo "                        <option value='0'>Kindergarten or Earlier</option>\n";
		for($j=1; $j<13; $j++) {
			if($grade == $j) {
				$selected = " selected";
			} else {
				$selected = "";
			}
			echo "                        <option value='$j'$selected>$j</option>\n";
		}
		echo "                     </select>\n";
		echo "                  </td>";
		echo "               </tr>\n";
		echo "               <tr>\n";
		echo "                  <td colspan='2' align='right'><input type='checkbox' name='campers-is_handicap-$childid' value='1'$child->is_handicap/></td>\n";
		echo "                  <td colspan='3'>Handicap-accessible room</td>\n";
		echo "               </tr>\n";
		echo "               <tr>\n";
		echo "                  <td colspan='2' align='right'>Food Option: </td>\n";
		echo "                  <td colspan='3'><select name='campers-foodoptionid-$childid'>\n";
		foreach ($this->foodoptions as $foodoption) {
			if($foodoption->foodoptionid == $child->foodoptionid) {
				$selected = " selected";
			} else {
				$selected = "";
			}
			echo "                  <option value='$foodoption->foodoptionid'$selected>$foodoption->name</option>\n";
		}
		echo "                  </select></td>\n";
		echo "               </tr>\n";
	}
	echo "            </table>\n";
	echo "            </div>\n";
	echo "            <span class='article_separator'>&nbsp;</span>\n";
	echo "            </div>\n";
	echo "            <p align='center'><input type='submit' value='Save' /></form></p>\n";
	echo "            <span class='article_separator'>&nbsp;</span>\n";
	echo "         </td>\n";
	echo "      </tr>\n";
}?>
</table>

</div>
