<?php
	/*
		This file contains pre-defined database selections for the project related
		part of the database. There a number of these around. Since some work in
		conjunction of each other all are located here.
		Most functions will work with or without an id as a base selector. All will
		require a PROJECT_ID for selection.
	*/


	/*-----------------------------------------------------------------------*
		This shows all the projects available.
	*/
	function showProjects( $project_id=0, type='sql' ) {
		if( $project_id != 0 && ( is_numeric( $project_id ) ) ) { // Singular project
			$sql = "SELECT * FROM `projects` WHERE `id`='$project_id'";
			return queryDB( $sql, $type );
		}
		else if( $project_id == 0 && ( is_numeric( $project_id ) ) ) {
			$sql = "SELECT * FROM `projects`"; // All projects
			return queryDB( $sql, $type );
		}
		else {
			return 'Error: You had non-numeric IDs. Please correct';
		}
	}


/*-----------------------------------------------------------------------*
		This shows all the tasks connected to a certain project. If type isn't
		included it's preset to sql to get the sql resultset back.
	*/
	function showTasks( $project_id, $type='sql', $task_id=0 ) {
		if( ( is_numeric( $project_id ) ) && ( $task_id != 0 ) && ( is_numeric( $task_id ) ) ) { // Show all tasks
			$sql = "SELECT * FROM `tasks` WHERE `project_id`='$project_id'";
			return query_db( $sql, $type );
		}
		
		else if( ( is_numeric( $project_id ) ) && ( $task_id == 0 ) && ( is_numeric( $task_id ) ) ) { // Show singular task
			$sql = "SELECT * FROM `tasks` WHERE `project_id`='$project_id' AND `id`='$task_id'";
			return query_db( $sql, $type );
		}

		else {
			return 'Error: You had non-numeric IDs. Please correct';
		}
	}

	
?>

