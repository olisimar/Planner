<?php

/*
Written by Rickard Lund, 09-11-2009
Creates a form for adding project-parts, with dynamic values.

reworked by Werner, 2009-11-10
*/

function projectPartForm( $pid, $ppid ) {

	//Default variables
	$submit = "New Project Part";
	$pp_name = "No Name Defined";
	$pp_description = "No Descriptive Info Yet";
	$pp_customer = "";

	//Find the project customer and use as default
		if ( $pid > 0 ) {
			$project_customer_query = "SELECT * FROM projects WHERE id=$pid LIMIT 1";
			$project_customer_result = queryDB($project_customer_query);
			$project_customer_row = mysql_fetch_assoc($project_customer_result);
			$project_title = $project_customer_row[ 'name' ];
			
			$pp_customer = $project_customer_row['customer'];
		}
	
	//Query for existing project part and replace default values
		if( $ppid > 0 ) {
			$project_part_query = "SELECT * FROM project_parts WHERE id = '$ppid' LIMIT 1";
			$project_part_result = queryDB($project_part_query);
			$project_part_row = mysql_fetch_assoc($project_part_result);
			
			$submit = "Submit Edited Part";
			$pp_name = $project_part_row['name'];
			$pp_description = $project_part_row['description'];
		}
		
		//Return dynamic form
		$project_part_form = '
						<div id="create">
							<h3> Project part of project: '.$project_name.' </h3>
								<form action="pp_index.php" method="post">
									<fieldset>
										<legend></legend>
										<label> Name: </label> <input type="text" name="pp_name" maxlength="50" size="40" value="'.$pp_name.'" />
										<label class="textarea_label"> Part Description: </label>
										<textarea name="pp_description">'.$pp_description.'</textarea>
										<label class="textarea_label"> Project Part Customer: </label>
										<textarea name="pp_customer">'.$pp_customer.'</textarea>
										<input type="hidden" name="pp_pid" value="'.$pid.'" />
										<input type="hidden" name="pp_ppid" value="'.$ppid.'" />
										<input class="submit" type="submit" name="createEditProjectPart" value="'.$submit.'" />
									</fieldset>
								</form>
						</div>
';
		
		return $project_part_form;
	}
	
?>