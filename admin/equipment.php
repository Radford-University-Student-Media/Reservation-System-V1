<?php

if(!issetSessionVariable('user_level') || getSessionVariable('user_level') < RES_USERLEVEL_ADMIN){

	die("You don't have permission to access this page!");

}else{

	$displayhome = true;
	$equipmessage = "";

	if(isset($_POST['myaction']) && $_POST['myaction'] == "new"){

		if($_POST['form'] == "equipment"){

			$displayhome = false;
			require 'newequip.php';
				
		}
			

	}
	else if(isset($_POST['myaction']) && $_POST['myaction'] == "delete"){

		if($_POST['form'] == "equipment"){
				
			deleteEquipmentByID($_POST['selector']);
				
		}
			

	}
	else if(isset($_POST['myaction']) && $_POST['myaction'] == "edit"){

		if($_POST['form'] == "equipment"){
				
			$displayhome = false;
			require 'editequip.php';
				
		}

	}

	if($displayhome){

		$equipment = getAllEquipment();
		$equipoptions = "";
			
		while($row = mysql_fetch_assoc($equipment)){
				
			$equipoptions = $equipoptions . "<option value=\"".$row['equip_id']."\">".$row['name']."</option>";
				
		}
			

		echo "<script language=\"JavaScript\">
			
				// setNameBox is called by an 'onchange' event on the
				// select list for the category editing section. It takes
				// the selected option and sets the 'name' textbox to it.
				
				function setNameBox(){
				
					var selected = document.forms[\"category\"][\"selector\"].selectedIndex;
					var text = document.forms[\"category\"][\"selector\"].options[selected].text;
					document.forms[\"category\"][\"name\"].value = text;
				
				}

				// setMyCatAction(a) is called by an 'onclick' event on
				// each of the submit buttons for the category form. It
				// sets the value of the hidden input 'myaction' to the
				// provided variable 'a'. The variable 'a' is a string
				// representing the action to be performed with the
				// information in this form.
				
				
				function setMyCatAction(a){
				
					document.forms[\"category\"][\"myaction\"].value = a;
				
				}
			
				// setMyEquipAction(a) is called by an 'onclick' event on
				// each of the submit buttons for the equipment form. It
				// sets the value of the hidden input 'myaction' to the
				// provided variable 'a'. The variable 'a' is a string
				// representing the action to be performed with the
				// information in this form.
			
				function setMyEquipAction(a){
				
					document.forms[\"equipment\"][\"myaction\"].value = a;
				
				}
			
				// confirmEquipForm() is called by an 'onsubmit' event on
				// the 'equipment' form. This function checks the value of
				// the hidden input 'myaction' to determine how it should
				// validate the form.
				//
				// If the action is 'delete' then the form will first check
				// to make sure there is an equipment selected. If so it will
				// then popup a confirmation dialog to make sure the user
				// really wants to delete the selected equipment. Otherwise
				// it pops up a dialog stating some equipment must be selected
				// and returns false.
			
				function confirmEquipForm(){
				
					if(document.forms[\"equipment\"][\"myaction\"].value == \"delete\"){
					
						var selected = document.forms[\"equipment\"][\"selector\"].selectedIndex;
						
						if(selected >= 0){
						
							var text = document.forms[\"equipment\"][\"selector\"].options[selected].text;
							return confirm(\"Are you sure you want to delete '\"+text+\"'?\");
						
						}else{
						
							alert(\"You must select a piece of equipment.\");
						
							return false;
						
						}
						
					}								
					else{
						return true;
					}
				
				}
			
				// confirmCatForm() is called by an 'onsubmit' event on
				// the 'category' form. This function checks the value of
				// the hidden input 'myaction' to determine how it should
				// validate the form.
				//
				// If the action is 'delete' then the form will first check
				// to make sure there is a category selected. If so it will
				// then popup a confirmation dialog to make sure the user
				// really wants to delete the selected category. Otherwise
				// it pops up a dialog stating a category must be selected
				// and returns false.
				//
				// If the action is 'rename' then the form will first check
				// to make sure there is a category selected. If so it will
				// return as true, otherwise it pops up a dialog stating 
				// that a category must be selected and returns false.
			
				function confirmCatForm(){
				
					var action = document.forms[\"category\"][\"myaction\"].value
				
					if(action == \"delete\"){
					
						var selected = document.forms[\"category\"][\"selector\"].selectedIndex;
						
						if(selected >= 0){
						
							var text = document.forms[\"category\"][\"selector\"].options[selected].text;
							return confirm(\"Are you sure you want to delete '\"+text+\"'?\");
						
						}else{
						
							alert(\"You must select a category.\");
						
							return false;
						
						}
						
					}
					else if(acton == \"rename\"){
					
						var selected = document.forms[\"category\"][\"selector\"].selectedIndex;
						
						if(selected >= 0){
						
							var text = document.forms[\"category\"][\"selector\"].options[selected].text;
							
							if(text == document.forms[\"category\"][\"name\"].value){
							
								alert(\"No change to category name.\");
								
								return false;
							
							}else{
							
								return true;
							
							}
						
						}else{
						
							alert(\"You must select a category.\");
						
							return false;
						
						}
					
					}
					else{
						return true;
					}
					
				}
			
			</script>
			
			<h3>Manage Equipment</h3>
			
			<table style=\"width: 80%; margin-right: auto; margin-left: auto; text-align: center;\">
			
				<tr>
				
					<td style=\"width: 50%; text-align: center;\">
						<h4>Equipment</h4>
						<form action=\"./index.php?pageid=manageequip\" onSubmit=\"return confirmEquipForm()\" name=\"equipment\" method=\"post\">
							<input type=\"hidden\" value=\"equipment\" name=\"form\">
							<input type=\"hidden\" value=\"\" name=\"myaction\">
							<select size=\"7\" name=\"selector\">
							
								".$equipoptions."
							
							</select>
							<br /><input type=\"submit\" value=\"New\" onclick=\"setMyEquipAction('new')\" />
							<input type=\"submit\" value=\"Edit\" onclick=\"setMyEquipAction('edit')\" />
							<input type=\"submit\" value=\"Delete\" onclick=\"setMyEquipAction('delete')\" />
						
						</form>
					
					</td>
					<!--<td style=\"width: 50%; text-align: center;\">
						<h4>Categories</h4>
						<form onSubmit=\"return confirmCatForm()\" name=\"category\">
							<input type=\"hidden\" value=\"category\" name=\"form\">
							<input type=\"hidden\" value=\"\" name=\"myaction\">
							<select size=\"5\" name=\"selector\" onchange=\"setNameBox()\">
							
								<option>Video Camera</option>
								<option>Still Camera</option>
								<option>Audio</option>
								<option>Tripods</option>
								<option>Miscellaneous</option>
							
							</select>
							<br /><input type=\"text\" size=\"25\" value=\"\" name=\"name\" />
							<br /><input type=\"submit\" value=\"New\" onclick=\"setMyCatAction('new')\" />
							<input type=\"submit\" value=\"Rename\" onclick=\"setMyCatAction('rename')\" />
							<input type=\"submit\" value=\"Delete\" onclick=\"setMyCatAction('delete')\" />
						
						</form>
					
					</td>-->
					
				</tr>
			
			</table>";
			
	}

}


?>