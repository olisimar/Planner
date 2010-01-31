<?php
	/*
		Created: 091015
		Authour: Werner
		For the express purpose to remove things from the database. The exception
		is the project main entity which can only be set to different states.

		Reqs:
		queryDB() -> pp_general_io.php : require_once() in forum_index.php
	*/
	
	/*-----------------------------------------------------------------------*
		This is a util function to deactivate a project despite the name of the
		function it doesn't casade removing entries in the database. This is to
		ensure that forums still work as they should.
	*/
	function deleteProject( $project_id ) {
		if( is_numeric( $project_id ) ) {
			$sql = "UPDATE `project_id` SET (`status`='D') WHERE `project_id`=$project_id";
			return queryDB( $sql );
		}
		else {
			return "Error: project_id was not numeric";
		}
	}
	

	/*-----------------------------------------------------------------------*
		Public: remove project_part
		This won't kill children to the project_part but it will remove the links
		to tasks related to it.
	*/
	function removeProjectPart( $pp_id ) {
		if( is_numeric( $pp_id ) ) {
			$sql = "DELETE FROM `parts_to_tasks` WHERE `project_part_id`=$pp_id";
			queryDB( $sql );
			$sql = "DELETE FROM `project_parts` WHERE `id`=$pp_id LIMIT 1";
			return queryDB( $sql );
		}
		else {
			return "Error: ID sent along wasn't numeric.";
		}
	}
	/*-----------------------------------------------------------------------*
		Public: remove task from project part
		Decouples tasks from a project part. It removes neither entities however.
	*/
	function removeProjectPartTask( $pp_id, $task_id ) {
		if( is_numeric( $pp_id ) && is_numeric( $task_id ) ) {
			$sql = "DELETE FROM `parts_to_tasks` WHERE `project_part_id`=$pp_id AND `task_id` LIMIT 1";
			return queryDB( $sql );
		}
		else {
			return "Error: one or more IDs sent along wasn't numeric.";
		}
	}
	
	
	/*-----------------------------------------------------------------------*
		Public: remove task
		This will remove a task from sprint_planning, parts_to_task as well as
		tasks tables. It will show no regard to the forum as this will have to
		roll without regard to if a task is still there or not. Hopefully people
		won't remove tasks willynilly.
	*/
	function removeTask( $task_id ) {
		if( is_numeric( $task_id ) ) {
			$sql = "DELETE FROM `parts_to_tasks` WHERE `task_id`=$task_id";
			qeuryDB( $sql );
			$sql = "DELETE FROM `sprint_planning` WHERE `task_id`=$task_id";
			queryDB( $sql );
			$sql = "DELETE FROM `tasks` WHERE `id`=$task_id LIMIT 1";
			return queryDB( $sql );
		}
		else {
			return "Error: ID sent along wasn't numeric.";
		}
	}
	
	
	/*-----------------------------------------------------------------------*
		Public: remove sprint
		This will remove a sprint and it's related tasks in the sprint_planning.
		Use with care.
	*/
	function removeSprint( $sprint_id ) {
		if( is_numeric( $sprint_id ) ) {
			$sql = "DELETE FROM `sprint_planning` WHERE `sprint_id`=$sprint_id";
			queryDB( $sql );
			$sql = "DELETE FROM `sprints` WHERE sprint_id=$sprint_id";
			return queryDB( $sql );
		}
		else {
			return "Error: ID sent along wasn't numeric.";
		}
	}
	/*-----------------------------------------------------------------------*
		Public: remove task from sprint
		This decouples a task from a certain sprint. 
	*/	
	function removeSprintTask( $sprint_id, $task_id ) {
		if( is_numeric( $sprint_id ) && is_numeric( $task_id ) ) {
			$sql = "DELETE FROM `sprint_planning` WHERE `sprint_id`=$sprint_id AND `task_id`=$task_id";
			return queryDB( $sql );
		}
		else {
			return "Error: one or more IDs sent along wasn't numeric.";
		}
	}


	/*-----------------------------------------------------------------------*
		Public: remove member from project
		This will decouple a user from a certain project. It does not remove a
		project or a user, just their link.
	*/
	function removeProjectMember( $project_id, $user_id ) {
		if( is_numeric( $project_id ) && is_numeric( $user_id ) ) {
			$sql = "DELETE FROM `project_member` WHERE `project_id`=$project_id AND `user_id`=$user_id";
			return queryDB( $sql );
		}
		else {
			return "Error: one or more IDs sent along wasn't numeric.";
		}
	}
?>