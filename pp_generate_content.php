<?php
	function generate_content() {
		$user_id = $_SESSION[ 'user_id' ]; // quickie access.
		$action = $_SESSION[ 'pp_action' ]; // (show|create|delete|edit|close|remove|add)
		$type = $_SESSION[ 'pp_type' ]; // (project|sprint|task|part|member)
		$pid = $_SESSION[ 'pp_pid' ];
		$sid = $_SESSION[ 'pp_sid' ];
		$tid = $_SESSION[ 'pp_tid' ];
		$ppid = $_SESSION[ 'pp_ppid' ];
		$member_id = $_SESSION[ 'pp_member_id' ];
		$content = '';

		// Due to the various actions only show, create and edit are shown here
		// as the other are actions that are taken care of before and are nothing
		// to show off for a user.

		if( $action == 'show' ) {
			if( $member_id > 0 ) {
				require_once( 'pp_show_members.php' );
				$content .= generate_member( $pid, $user_id );
			}
			else if( $member_id == -1 ){
				require_once( 'pp_show_members.php' );
				$content .= generate_member_list( $pid, $user_id );
			}

			/*--*--*--*--*--*--*--*--*--*/
			else if( $tid > 0 ) { // Showing a task
				require_once( 'pp_show_tasks.php' );
				$content .= generate_task( $tid, $user_id );
			} // EoI( tid > 0 )
			else if( $tid == -1 ) {
				require_once( 'pp_show_tasks.php' );
				$content .= generate_task_list( $pid, $sid, $ppid, $user_id );
			} // EoEI( $tid = -1 )

			/*--*--*--*--*--*--*--*--*--*/
			else if( $sid > 0 ) {
				require_once( 'pp_show_sprints.php' );
				$content .= generate_sprint( $pid, $sid, $user_id );
			} // EoEI( sid > 0 )
			else if( $sid == -1 ) {
				require_once( 'pp_show_sprints.php' );
				$content .= generate_sprint_list( $pid, $user_id );
			} //EoEI( sid = -1)

			/*--*--*--*--*--*--*--*--*--*/
			else if( $ppid > 0 ) {
				require_once( 'pp_show_parts.php' );
				$content .= generate_part( $pid, $ppid, $user_id );
			} // EoEI( ppid > 0 )
			else if( $ppid == -1 ) {
				require_once( 'pp_show_parts.php' );
				$content .= generate_part_list( $pid, $user_id );
			} // EoEI( ppid = -1)

			/*--*--*--*--*--*--*--*--*--*/
			else if( $pid > 0 ) {
				require_once( 'pp_show_projects.php' );
				$content .= generate_project( $pid, $user_id );
			} // EoEI( pid > 0 )
			else if( $pid == -1 ) {
				require_once( 'pp_show_projects.php' );
				$content .= generate_project_list( $user_id );
			}

			/*--*--*--*--*--*--*--*--*--*/
			else {
				echo 'Bad indata...'; // Make a handler here for the future.
			} // EoE
		} // EoI( show )

		/*--*--*--*--*--*--*--*--*--*/
		else if( $action == 'create' ) {
			if( $type == 'project' ) {
				require_once( 'pp_form_project.php' );
				$content .= projectForm( $pid );
			}
			else if( $type == 'sprint' ) {
				require_once( 'pp_form_sprint.php' );
				$content .= sprintForm( $pid, $sid );
			}
			else if( $type == 'part' ) {
				require_once( 'pp_form_project_part.php' );
				$content .= projectPartForm( $pid, $ppid );
			}
			else if( $type == 'task' ) {

			}
			else {
				// Bad indata, make a handler here for the future.
			}
		}

		/*--*--*--*--*--*--*--*--*--*/
		else if( $action == 'edit' ) {
			if( $type == 'project' ) {
				require_once( 'pp_form_project.php' );
				$content .= projectForm( $pid );
			}
			else if( $type == 'sprint' ) {
				require_once( 'pp_form_sprint.php' );
				$content .= sprintForm( $pid, $sid );
			}
			else if( $type == 'part' ) {
				require_once( 'pp_form_project_part.php' );
				$content .= projectPartForm( $pid, $ppid );
			}
			else if( $type == 'task' ) {
			}
			else {
				// Bad indata, make a handler here for the future.
			}
		}

		echo $content;
	}
?>