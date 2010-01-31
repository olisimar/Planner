<?php
/*
Written by Rickard Lund. 2009-11-03
This file creates a dynamic form based on whether there is a project with a
certain id, and creates an appropriate form: Either a New project or an Edit
of a project.

reworked by Werner, 2009-11-10
*/

function projectForm( $pid ) {
	//Default values
	$submit = 'Create Project';
	$pp_parent = 0;
	$pp_status = 'A';
	$pp_name = 'Unnamed Project';
	$pp_description = 'No descriptive info yet';
	$pp_unit_name = 'hours';
	$pp_unit_value = 1;
	$pp_starts_at = date("Y-m-d");
	$pp_ends_at = date( "Y-m-d", mktime( 0, 0, 0, date("m")+1, date("d"), date("Y") ) );
	$pp_customer = 'The owner of the project. Company/Client.';

	if( $pid > 0 ) {
		//Search for the id in the projects-table
		$query = "SELECT * FROM projects WHERE id = '$pid'";
		$result = queryDB( $query );
		$row = mysql_fetch_assoc( $result );

		//If there is one, replace the default values with those that already have been created.
		$submit = 'Submit Edited Project';
		$pp_parent = $row['parent'];
		$pp_status = $row['status'];
		$pp_name = $row['name'];
		$pp_description = $row['description'];
		$pp_unit_name = $row['unit_name'];
		$pp_unit_value = $row['unit_value'];
		$pp_starts_at = $row['start_at'];
		$pp_ends_at = $row['end_at'];
		$pp_customer = $row['customer'];
	}

	//Return a the dynamic form
	$projectForm = '<div id="create">
						<form action="pp_index.php" method="post">
						<fieldset>
							<legend></legend>
							<label> Project Name: </label> <input type="text" name="pp_name" maxlength="50" size="40" value="'.$pp_name.'" />
							<label> Set Project Status </label>
							<select name="pp_status">
							';
	if( $pp_status == 'A' ) {
		$projectForm .= '	<option value="A" selected="selected"> Active </option>
							';
	} else {
		$projectForm .= '	<option value="A"> Deactivated </option>
							';
	}
	if( $pp_status == 'D' ) {
		$projectForm .= '	<option value="D" selected="selected"> Deactivated </option>
							';
	} else {
		$projectForm .= '	<option value="D"> Deactivated </option>
							';
	}
	if( $pp_status == 'S' ) {
		$projectForm .= '	<option value="S" selected="selected"> Secret </option>
							';
	} else {
		$projectForm .= '	<option value="S"> Secret </option>
							';
	}
	$projectForm .= '</select> <br />
							<label class="textarea_label"> Project Description: </label>
							<textarea name="pp_description">'.$pp_description.'</textarea>
						</fieldset>
						<fieldset>
							<legend></legend>
							<label> Unit Name </label> <input type="text" name="pp_unit_name" maxlength="15" value="'.$pp_unit_name.'" />
							<label> Unit Value in hours: </label>
							<select name="pp_unit_value">
							';
							//Loop out unit values. Min value = 1, Max value = 12.
								for ( $i = 1; $i < 13; $i++ ) {
									$projectForm .= '	<option value="'.$i.'"> '.$i.' </option>
							';
								}
	$projectForm .= '</select>
							</fieldset>
							<fieldset>
								<legend></legend>
								<label> Starting date: </label> <input type="text" name="start_at" maxlength="10" size="9" value="'.$pp_starts_at.'" />
								<label> Ending date: </label> <input type="text" name="end_at" maxlength="10" size="9" value="'.$pp_ends_at.'" />
							</fieldset>
							<fieldset>
								<legend></legend>
								<label class="textarea_label"> Customer info: </label>
								<textarea name="pp_customer">'.$pp_customer.'</textarea>
								<input type="hidden" name="pp_pid" value="'.$pid.'" />
								<input class="submit" type="submit" name="createEditProject" value="'.$submit.'" />
							</fieldset>
						</form>
					</div>
';

return $projectForm;

}

?>