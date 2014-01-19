<?php defined('_JEXEC') or die('Restricted access'); ?>
<link type="text/css"
   href="<?php echo JURI::root(true);?>/components/com_muusla_application/css/application.css"
   rel="stylesheet" />
<script type="text/javascript">
   jQuery(document).ready(function ($) {
	    $("#muusaApp .save").button().click(function (event) {
		    submit($);
	        event.preventDefault();
	        return false;
	    });
	    camperlist = [ <?php
	        $campers = array();
	        foreach($this->campers as $camper) {
	            array_push($campers, "{ label: '$camper->firstname $camper->lastname ($camper->city, $camper->statecd)', ident: '$camper->id'}\n");
	        }
	        echo implode(",\n", $campers); ?>
	    ];
	});
	
	function submit($) {
        var count = 0;
	    $("#muusaApp tr:visible")
	        .filter(
	            function () {
	                return $("input[name*='camperid__staff-camperid']", $(this)).val() != "";
	            })
	        .each(function () {
	            $("select,input", $(this)).each(
	                function () {
	                    incName($(this),
	                        count);
	                });
                count++;
	        });
    	$("#muusaApp").submit();
	}
</script>
<div id="ja-content">
   <div class="componentheading">Program Staff Workbook</div>
   <table class="blog">
      <tr>
         <td valign='top'>
            <div>
               <div class='contentpaneopen'>
                  <h2 class='contentheading'>Program Staff Workbook</h2>
               </div>
               <div class='article-content'>
                  <form id="muusaApp" method="post">
                     <table>
                        <thead>
                           <tr>
                              <th>Name</th>
                              <th>Position</th>
                              <th>Registered?</th>
                              <th>&nbsp;</th>
                           </tr>
                        </thead>
                        <?php 
                        foreach($this->programs as $program) {?>
                        <tbody>
                           <tr>
                              <td colspan="4"><h4>
                                    <?php echo $program->name;?>
                                 </h4></td>
                           </tr>
                           <?php 
                           if(count($program->staff) > 0) {
                              foreach($program->staff as $staff) {?>
                           <tr>
                              <td><?php echo "$staff->lastname, $staff->firstname ($staff->familyname)" ?>
                                 <input type="hidden"
                                 name="<?php echo $staff->yearattendingid!=0 ? "yearattending__staff-yearattendingid-$staff->yearattendingid" : "camperid__staff-camperid-$staff->camperid"?>"
                                 value="<?php echo $staff->yearattendingid!=0 ? $staff->yearattendingid : $staff->camperid?>" />
                              </td>
                              <td><?php echo $staff->staffpositionname;?>
                                 <input type="hidden"
                                 name="<?php echo $staff->yearattendingid!=0 ? "yearattending__staff-staffpositionid-$staff->yearattendingid" : "camperid__staff-staffpositionid-$staff->camperid"?>"
                                 value="<?php echo $staff->staffpositionid;?>" />
                              </td>
                              <td align="center"><?php echo $staff->yearattendingid!=0 ? "Yes" : "No";?>
                              </td>
                              <td nowrap="nowrap"><input type="checkbox"
                                 name="<?php echo $staff->yearattendingid!=0 ? "yearattending__staff-delete-$staff->yearattendingid" : "camperid__staff-delete-$staff->camperid"?>" />
                                 Delete</td>
                           </tr>
                           <?php }
                           }?>
                           <tr>
                              <td><input type="text"
                                 class="inputtext camperlist ui-corner-all" /><input
                                 type="hidden"
                                 name="camperid__staff-camperid-"
                                 class="camperlist-value" />
                              </td>
                              <td><select
                                 name="camperid__staff-staffpositionid-"
                                 class="ui-corner-all">
                                    <option value="0">Staff Position</option>
                                    <?php foreach($program->positions as $position) {
                                       echo "          <option value='$position->id'>$position->name</option>\n";
                                    }?>
                              </select>
                              </td>
                              <td>&nbsp;</td>
                              <td>
                                 <button class="add help">Add Staff</button>
                              </td>
                           </tr>
                        </tbody>
                        <?php
                        }?>
                        <tfoot>
                           <tr>
                              <td align="right" colspan="4">
                                 <button class="save">Save Staff</button>
                              </td>
                           </tr>
                        </tfoot>
                     </table>
                     <span class='article_separator'>&nbsp;</span>
                  </form>
               </div>
            </div>
         </td>
      </tr>
   </table>
</div>
