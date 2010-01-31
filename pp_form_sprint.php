<?php
/*
Written by Rickard Lund. 2009-11-09
This file creates a dynamic form for a sprint, associated with
an appropriate project. 

reworked by Werner, 2009-11-10
*/

function sprintForm( $pid, $sid ){

//Set pre-defined variables
	$submit = "Create New Sprint";
	$pp_name = "No Name Defined";
	$pp_description = "No Descriptive Info Yet";
	$time = time();
	$pp_starts_at = date("Y-m-d", $time);
//	$pp_ends_at = date( "Y-m-d" ,mktime( 0, 0, 0, date("Y"), date("m"), date("d")+7 ) ); Rickard... Du skrev båda...
	$pp_ends_at = date( "Y-m-d", mktime( 0, 0, 0, date("m"), date("d")+7, date("Y") ) );

	if ($sid > 0) {
	//Perform query to check for existing sprint
		$sprint_form_query = "SELECT * FROM `sprints` WHERE `id`='$sid'";
		$sprint_form_result = queryDB($sprint_form_query);
		$sprint_form_row = mysql_fetch_assoc($sprint_form_result);

	//If sprint exists, set new varibles
		$submit = "Submit Edited Sprint";
		$pp_name = $sprint_form_row['name'];
		$pp_description = $sprint_form_row['description'];
		$pp_starts_at = $sprint_form_row[ 'start_at' ];
		$pp_ends_at = $sprint_form_row[ 'end_at' ];
			
	//Get the project-title that the sprint will belong to
		$project_title_query = "SELECT `name` FROM `projects` WHERE `id`='$pid'";
		$project_title_result = queryDB($project_title_query);
		$project_title_row = mysql_fetch_assoc($project_title_result);
		$project_title = $project_title_row[ 'name' ];
	}
	else {
		$sid = 0;
	}

//Return the dynamic form
	$sprintForm = '
			<div id="create">
				<h3> Sprint related to project: '.$project_title.' </h3>
				<form action="pp_index.php" method="post">
					<fieldset>
						<legend></legend>
						<label> Name: </label> <input type="text" name="pp_name" maxlength="50" size="40" value="'.$pp_name.'" />
						<br />
						<label> Starting date: </label> <input type="text" name="pp_starts_at" maxlength="10" size="9" value="'.$pp_starts_at.'" />
						<label> Ending date: </label> <input type="text" name="pp_ends_at" maxlength="10" size="9" value="'.$pp_ends_at.'" />
					</fieldset>
					<fieldset>
						</legend></legend>
						<label class="textarea_label"> Sprint Description: </label>
						<textarea name="pp_description">'.$pp_description.'</textarea>
						<input type="hidden" name="pp_pid" value="'.$pid.'" />
						<input type="hidden" name="pp_sid" value="'.$sid.'" />
						<input class="submit" type="submit" name="createEditSprint" value="'.$submit.'" />
					</fieldset>
				</form>
			</div>
		';
	
	return $sprintForm;

}




?>