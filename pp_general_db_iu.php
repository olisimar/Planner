<?php
	/*
		This file should only contain insert/update code for the database as well
		what specific util functions they need.
	*/

	/*** Methods that insert into the major entities ***/
	/*-----------------------------------------------------------------------*
		Public: insert & update entry
		Explicit create with id=0 else it's an update. Returns SQL message.
		inData is an array with the keyes containing the exact wording of the
		DB column names. The values are checked for type and not to be null.
	*/
	function iuProject( $inData ,$id=0 ) {
		if( $id != 0 ) { // Update a project
			$kv = "";

			foreach( $inData as $key => $value ) {
				$allow = checkKeyValue( "project", $key, $value );

				if( $allow == TRUE ) {
					$kv .= "`$key`='$value',";
				}
				else {
					echo 'Key: '.$key.' with value: '.$value.' FAILED <br />';
				}
			}

			$sql = "UPDATE `projects` SET ".sub_str( $kv, 0, -1 )." WHERE `id`='$id'";
			return queryDB( $sql );
		} //End of: if( update )

		else { //Create a new project
			$k = "";
			$v = "";
			foreach( $inData as $key => $value ) {
				$allow = checkKeyValue( "project", $key, $value );

				if( $allow == TRUE ) {
					$k .= "`$key`,";
					$v .= "'$value',";
				}
				else {
					echo 'Key: '.$key.' with value: '.$value.' FAILED <br />';
				}
			}

			$sql = "INSERT INTO `projects` (".sub_str( $k, 0, -1 ).") VALUES (".sub_str( $v, 0, -1 ).")";
			return queryDB( $sql );
		} // End  of: createProject()
	} // End of: iu_project()

	/*-----------------------------------------------------------------------*
		Public: insert & update entry
		Explicit create with id=0 else it's an update. Returns SQL message.
		inData is an array with the keyes containing the exact wording of the
		DB column names. The values are checked for type and not to be null.
	*/
	function iuSprint( $inData ,$id=0 ) {
		if( $id != 0 ) { // Update a sprint
			$kv = "";

			foreach( $inData as $key => $value ) {
				$allow = checkKeyValue( "sprint", $key, $value );

				if( $allow == TRUE ) {
					$kv .= "`$key`='$value',";
				}
				else {
					echo 'Key: '.$key.' with value: '.$value.' FAILED <br />';
				}
			}

			$sql = "UPDATE `sprints` SET ".sub_str( $kv, 0, -1 )." WHERE `id`='$id'";
			return queryDB( $sql );
		} //End of: if( update )

		else { //Create a new project
			$k = "";
			$v = "";
			
			foreach( $inData as $key => $value ) {
				$allow = checkKeyValue( "sprint", $key, $value );

				if( $allow == TRUE ) {
					$k .= "`$key`,";
					$v .= "'$value',";
				}
				else {
					echo 'Key: '.$key.' with value: '.$value.' FAILED <br />';
				}
			}

			$sql = "INSERT INTO `sprints` (".sub_str( $k, 0, -1 ).") VALUES (".sub_str( $v, 0, -1 ).")";
			return queryDB( $sql );
		} // End  of: createProject()
	} // End of: iu_project()

	/*-----------------------------------------------------------------------*
		Explicit create with id=0 else it's an update. Returns SQL message.
		inData is an array with the keyes containing the exact wording of the
		DB column names. The values are checked for type and not to be null.
	*/
	function iuTask( $inData ,$id=0 ) {
		if( $id != 0 ) { // Update a project
			$kv = "";

			foreach( $inData as $key => $value ) {
				$allow = checkKeyValue( "task", $key, $value );

				if( $allow == TRUE ) {
					$kv .= "`$key`='$value',";
				}
				else {
					echo 'Key: '.$key.' with value: '.$value.' FAILED <br />';
				}
			}

			$sql = "UPDATE `tasks` SET ".sub_str( $kv, 0, -1 )." WHERE `id`='$id'";
			return queryDB( $sql );
		} //End of: if( update )

		else { //Create a new project
			$v = "";
			$k = "";

			foreach( $inData as $key => $value ) {
				$allow = checkKeyValue( "task", $key, $value );

				if( $allow == TRUE ) {
					$k ,= "`$key`,";
					$v .= "'$value',";
				}

				else {
					echo 'Key: '.$key.' with value: '.$value.' FAILED <br />';
				}
			}

			$sql = "INSERT INTO `tasks` (".sub_str( $k, 0, -1 ).") VALUES (".sub_str( $v, 0, -1 ).")";
			return queryDB( $sql );
		} // End  of: createProject()
	} // End of: iu_project()


	/*-----------------------------------------------------------------------*
		Explicit create with id=0 else it's an update. Returns SQL message.
		inData is an array with the keyes containing the exact wording of the
		DB column names. The values are checked for type and not to be null.
	*/
	function iuProjectPart( $inData ,$id=0 ) {
		if( $id != 0 ) { // Update a project
			$kv = "";

			foreach( $inData as $key => $value ) {
				$allow = checkKeyValue( "project_part", $key, $value );

				if( $allow == TRUE ) {
					$kv .= "`$key`='$value',";
				}
				else {
					echo 'Key: '.$key.' with value: '.$value.' FAILED <br />';
				}
			}

			$sql = "UPDATE `project_parts` SET ".sub_str( $kv, 0, -1 )." WHERE `id`='$id'";
			return queryDB( $sql );
		} //End of: if( update )

		else { //Create a new project
			$k = "";
			$v = "";

			foreach( $inData as $key => $value ) {
				$allow = checkKeyValue( "project_part", $key, $value );

				if( $allow == TRUE ) {
					$k ,= "`$key`,";
					$v .= "'$value',";
				}

				else {
					echo 'Key: '.$key.' with value: '.$value.' FAILED <br />';
				}
			}

			$sql = "INSERT INTO `project_parts` (".sub_str( $k, 0, -1 ).") VALUES (".sub_str( $v, 0, -1 ).")";
			return queryDB( $sql );
		} // End  of: createProject()
	} // End of: iu_project()


	/*** Methods that insert into the relational entities ***/
	/*-----------------------------------------------------------------------*
		This is for building up relational bindings between projects, sprints,
		tasks and users. Perfect for JOINS to get out all nice data in one go.
		user_id is set 0 as a rule since there doesn't have to be a responisble one.
	*/
	function iuSprintPlanning( $pro_id, $task_id, $sprint_id, $user_id=0 ) {
		// Making sure we get only numericals, hopefully proper IDs
		if( ( is_numeric( $pro_id ) ) && ( is_numeric( $sprint_id ) ) && ( is_numeric( $task_id ) ) && ( is_numeric( $user_id ) ) ) {
			$sql = "SELECT * FROM `sprint_planning` WHERE `sprint_id`='$sprint_id' AND `project_id`='$pro_id' AND `task_id`='$task_id'";
			$result = queryDB( $sql );
			
			if( mysql_num_rows( $result ) != 0 ) { // Updating an existing row.
				$sql = "UPDATE `sprint_planning` SET `user_id='$user_id' WHERE `sprint_id`='$sprint_id' AND `project_id`='$pro_id' AND `task_id`='$task_id'";
				return queryDB( $sql );
			}
			else { // New row insertion
				$sql = "INSERT INTO `sprint_planning` (`sprint_id`,`project_id`,`task_id`,`user_id`) VALUES ('$sprint_id','$pro_id','$task_id','$user_id')";
				return queryDB( $sql );
			}
		}
		else {
			return 'Error: one or more values were not considered numeric.';
		}
	}

	/*
		This method is for the relationship between projectparts and tasks that
		are coupled with them. There is a check if they are already related and
		if the IDs are numerical. Minimum security.
		There is an update if an id	for the relationship is updated. If it is 0
		it's an insert with check otherwise it's an update. ID for the relationship
		is preset to 0.
	*/
	function iuPartsToTask( $part_id, $task_id, $id=0 ) {
		if( ( is_numeric( $part_id ) ) && ( is_numeric( $task_id ) ) && ( is_numeric( $id ) ) ) {
			if( $id == 0 ) { // Insertation of a new entry.
				$sql = "SELECT * FROM `parts_to_tasks WHERE `task_id`='$task_id' AND `project_part_id`='$part_id'";
				$result = queryDB( $sql );
				if( mysql_num_rows( $result ) != 0 )
					$sql = "INSERT INTO 'parts_to_tasks' (`task_id`,`project_part_id`) VALUES ('$task_id','$part_id')";
					return queryDB( $sql );
				}
				else {
					return 'Warning: This relationship already exists.';
				}
			}
			else { // Updating an existing entry.
				$sql = "UPDATE `parts_to_tasks` SET `task_id`='$task_id',`project_part_id`='$part_id' WHERE `$id`='$id'";
				return queryDB( $sql );
			}
		}
		else {
			return 'Error: one or more values were not considered numeric.';
		}


	}

	/*** Util methods for these functions, do not alter without thinking ***/
	/*-----------------------------------------------------------------------*
		Private: util
		Checking if key and value are correct for the insert or not. If they do
		not match up correctly a FALSE will be returned.
	*/
	function checkKeyValue( $type, $key, $value ) {
		// General checks.
		if( $key == 'start_at' || $key == 'end_at' ) { // Checking time
			if( ( strtotime( $value ) != FALSE ) || ( strtotime( $value ) != -1 ) ) { // php version checking, older was -1, newer are FASLE
				return TRUE;
			}
			else {
				return FALSE;
			}
		}
		else if( $key == 'project_id' || $key == 'parent' || $key == 'fk_user_id' || $key == 'project_id' ) {
			return is_numeric( $value );
		}

		// Specific parts
		else if( $type == "project" ) {
			if( $key == 'forum_id' || $key == 'unit_value' ) { //Needs int to work.
				return is_numeric( $value );
			}
			else if( $key == 'name' || $key == 'description' || $key == 'unit_name' || $key == 'customer' ) { // Just making sure its seperate from null.
				return ( !is_null( $value ) );
			}
			else {
				return TRUE;
			}
		}
		
		else if( $type == "sprint" ) {
			if( $key == 'project_id' ) {
				return is_numeric( $value );
			}
			else if( $key == 'name' || $key == 'description' || $key == 'customer' ){
				return ( !is_null( $value ) );
			}
			else {
				return TRUE;
			}
		}
		
		else if( $type == "project_part" ) {
			if( $key == 'project_id' ) {
				return is_numeric( $value );
			}
			else if( $key == 'name' || $key == 'description' || $key == 'customer' ){
				return ( !is_null( $value ) );
			}
			else {
				return TRUE;
			}
		}
		
		else if( $type == "task" ) {
			if( $key == 'estimated_units' || $key == 'start_user_id' || $key == 'close_user_id' ) {
				return is_numeric( $value );
			}
			else if( $key == 'name' || $key == 'description' ){
				return ( !is_null( $value ) );
			}
			else {
				return TRUE;
			}
		}
		
		else {
			echo 'Bad coder, bad bad coder! :: '.$key.'=>'.$value;
			return FALSE; // Automatic fail, what was sent here anyway?
		}
	}
?>
