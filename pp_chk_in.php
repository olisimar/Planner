<?php
	/*
		Authour; Werner
		Created: 091021
		
		This is a pure script file. It handles checking what is coming in and
		dealing with it appropiately. The rest of the file will be looking towards
		certain $_SESSION[] markers to determine their behaviour.
		These are:
		action 	= will set up for a form to be created or similar.
		type		= What type is targeted, project, task or other?
		pid			= pid, project_id
		sid			= sid, sprint_id
		tid			= tid, task_id
		ppid		= ppid, project_part_id

		<THIS IS A THING TO DO IF TIME PERMITS>
		last_action = if true went well, else failed.
		error_code	=	A code for the error that occured, a form that improperly filled can be reshown with the data filled in etc.
		error_mess	= A message passed along for the user to see if wanted.
	*/

	//Reset of $_SESSION[] to prevent bad input.
	$_SESSION[ 'pp_action' ] = 'show'; // (show|create|delete|edit|close|open|remove|add)
	$_SESSION[ 'pp_type' ] = 'none'; // (project|sprint|task|part|member)
	$_SESSION[ 'pp_pid' ] = 0; // project_id
	$_SESSION[ 'pp_sid' ] = 0; // sprint_id
	$_SESSION[ 'pp_tid' ] = 0; // task_id
	$_SESSION[ 'pp_ppid'] = 0; // project_part_id
	$_SESSION[ 'pp_member_id' ] = 0; // The user requested.

	if( isset( $_SESSION[ 'user_id' ] ) ) { // logged in?

		if( isset( $_REQUEST[ 'action' ] ) ) {
			$action = $_REQUEST[ 'action' ];

			//--- Picking up id, used a lot in a lot of places ---//
			if( isset( $_REQUEST[ 'pid' ] ) ) { // Possible we have an pid, used generally hence picked up here.
				$pid = $_REQUEST[ 'pid' ];
				if( is_numeric( $pid ) ) { // Proper numeric id?
					$_SESSION[ 'pp_pid' ] = $pid;
				}
			}

			if( isset( $_REQUEST[ 'sid' ] ) ) { // Possible we have an pid, used generally hence picked up here.
				$sid = $_REQUEST[ 'sid' ];
				if( is_numeric( $sid ) ) { // Proper numeric id?
					$_SESSION[ 'pp_sid' ] = $sid;
				}
			}

			if( isset( $_REQUEST[ 'tid' ] ) ) { // Possible we have an pid, used generally hence picked up here.
				$tid = $_REQUEST[ 'tid' ];
				if( is_numeric( $tid ) ) { // Proper numeric id?
					$_SESSION[ 'pp_tid' ] = $tid;
				}
			}

			if( isset( $_REQUEST[ 'ppid' ] ) ) { // Possible we have an pid, used generally hence picked up here.
				$ppid = $_REQUEST[ 'ppid' ];
				if( is_numeric( $ppid ) ) { // Proper numeric id?
					$_SESSION[ 'pp_ppid' ] = $ppid;
				}
			}

			if( isset( $_REQUEST[ 'member' ] ) ) { // Possible we have an pid, used generally hence picked up here.
				$member_id = $_REQUEST[ 'member' ];
				if( is_numeric( $member_id ) ) { // Proper numeric id?
					$_SESSION[ 'pp_member_id' ] = $member_id;
				}
			}

			//--- What actions are taken ---//
			if( $action == 'show' ) { // All we do is show
				$_SESSION[ 'pp_action' ] = $action;
				if( isset( $_REQUEST[ 'type' ] ) ) {
					$type = $_REQUEST[ 'type' ];
					if( $type == 'project' || $type == 'sprint' || $type == 'task' || $type == 'part' || $type='member' ) {
						$_SESSION[ 'pp_type' ] = $type;
					}
				}
			} // EoI( show )

			else if( $action == 'create' ) {
				$_SESSION[ 'pp_action' ] = $action;
				if( isset( $_REQUEST[ 'type' ] ) ) {
					$type = $_REQUEST[ 'type' ];
					if( $type == 'project' || $type == 'sprint' || $type == 'task' || $type == 'part' || $type='member' ) {
						$_SESSION[ 'pp_type' ] = $type;
					}
				}
			} // EoI( create )

			else if( $action == 'delete' ) {
				$_SESSION[ 'pp_action' ] = $action;
				if( isset( $_REQUEST[ 'type' ] ) ) {
					$type = $_REQUEST[ 'type' ];
					if( $type == 'project' || $type == 'sprint' || $type == 'task' || $type == 'part' || $type='member' ) {
						$_SESSION[ 'pp_type' ] = $type;
					}
				}
			} // EoI( delete )

			else if( $action == 'edit' ) {
				$_SESSION[ 'pp_action' ] = $action;
				if( isset( $_REQUEST[ 'type' ] ) ) {
					$type = $_REQUEST[ 'type' ];
					if( $type == 'project' || $type == 'sprint' || $type == 'task' || $type == 'part' ) {
						$_SESSION[ 'pp_type' ] = $type;
					}
				}
			} // EoI( edit )

			else if( $action == 'close' ) {
				$_SESSION[ 'pp_action' ] = $action;
				if( isset( $_REQUEST[ 'type' ] ) ) {
					$type = $_REQUEST[ 'type' ];
					if( $type == 'task' ) {
						$user_id = $_SESSION[ 'user_id' ];
						$tid = $_SESSION[ 'pp_tid' ];
						$now = date( "YmdHms", time() );
						$sql = "UPDATE `tasks` SET `close_user_id`=$user_id,`end_at`=$now WHERE `id`=$tid LIMIT 1";
						queryDB( $sql );
						$sql = "DELETE FROM `tasks_to_members` WHERE `task_id`=$tid LIMIT 1";
						queryDB( $sql );
						$_SESSION[ 'pp_action' ] = 'show';
					}
					else {
					}
				}
			} // EoI( close )
			
			else if( $action == 'open' ) {
				$_SESSION[ 'pp_action' ] = $action;
				if( isset( $_REQUEST[ 'type' ] ) ) {
					$type = $_REQUEST[ 'type' ];
					if( $type == 'task' ) {
						$user_id = $_SESSION[ 'user_id' ];
						$tid = $_SESSION[ 'pp_tid' ];
						$sql = "UPDATE `tasks` SET `close_user_id`=0,`end_at`=null WHERE `id`=$tid LIMIT 1";
						queryDB( $sql );
						$sql = "INSERT INTO `tasks_to_members` (`task_id`,`user_id`) VALUES ( $tid, $user_id )";
						queryDB( $sql );
						$_SESSION[ 'pp_action' ] = 'show';
					}
					else if( $type == 'project' ) {
					}
					else {
					}
				}
			} // EoI( close )

			else if( $action == 'remove' ) {
				$_SESSION[ 'pp_action' ] = $action;
				if( isset( $_REQUEST[ 'type' ] ) ) {
					$type = $_REQUEST[ 'type' ];
					if( $type == 'project' || $type == 'sprint' || $type == 'task' || $type == 'part' || $type='member' ) {
						$_SESSION[ 'pp_type' ] = $type;
					}
				}
			} // EoI( remove )

			else if( $action == 'add' ) {
				$_SESSION[ 'pp_action' ] = $action;
				if( isset( $_REQUEST[ 'type' ] ) ) {
					$type = $_REQUEST[ 'type' ];
					if( $type == 'project' || $type == 'sprint' || $type == 'task' || $type == 'part' || $type='member' ) {
						$_SESSION[ 'pp_type' ] = $type;
					}
				}
			} // EoI( close )
	//------------------------------------------------------------------------//

			else {
				echo 'Bad Action... <br />';
			}
		} // EoI( action )
		else if( isset( $_POST[ 'createEditProject' ] ) ) {
			if( isset( $_POST[ 'pp_pid' ] ) ) {
				$pid = $_POST[ 'pp_pid' ];
				require_once( 'pp_iu_project.php' );
				if( $pid == 0 ) { // Creating project
					$pid = createProject();
				} else { // Editing project
					editProject( $pid );
				}
				$_SESSION[ 'pp_action' ] = 'show';
				$_SESSION[ 'pp_pid' ] = $pid;
			}
			else {
				echo 'Failed to deliver an id of some sort. <br />';
			}
		} // EoI( createEditProject )

/*
		else if( isset( $_POST[ 'createEditSprint' ] ) ) {
		} // EoEI(  )
		else if( isset( $_POST[ 'createEditPart' ] ) ) {
		} // EoEI(  )
		else if( isset( $_POST[ 'createEditTask' ] ) ) {
		} // EoEI(  )
*/
		
	//------------------------------------------------------------------------//
		else { // No action, pristine
			$user_id = $_SESSION[ 'user_id' ];
			$sql = "SELECT * FROM `view_projects` WHERE `user_id`=$user_id ORDER BY `sprint_end` DESC";
			$sql_r = queryDB( $sql );

			$continue = true;
			while( $continue ) {
				$result = mysql_fetch_assoc( $sql_r );
				$task_id = $result[ 'task_id' ];
				$sprint_id = $result[ 'sprint_id' ];

				if( is_null( $task_id ) && is_null( $sprint_id ) ) {
				} // Just making sure there is a proper project here.
				else {
					$now = strtotime( date( "Y-m-d" ) );
					$time = $result[ 'sprint_end' ];
					$sprint_end = strtotime( $time );

					if( $now < $sprint_end ) { // Is this sprint the most active
						$_SESSION[ 'pp_pid' ] = $result[ 'id' ];
						$_SESSION[ 'pp_project_name' ] = $result[ 'project_name' ];
						$_SESSION[ 'pp_sid' ] = $sprint_id;
						$_SESSION[ 'pp_sprint_name' ] = $result[ 'sprint_name' ];
						$_SESSION[ 'pp_tid' ] = $task_id;
					}
					else {
						$continue = false;
					}
				}
			}
		}
	} // EoIf( userid )
	else { // Was not logged in, bad.
		// break for main index.php file.
	}


?>