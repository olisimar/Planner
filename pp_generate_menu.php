<?php
	/*
		Authour:	Werner
		Created:	091021

		This file produces several final menues in full html with echo. This is
		for timesaving purposes. There is no JSON support now, belongs to another
		view set.
		This will produce lists and if a pid exists it will highlight that with
		a CSS class. It works on what is located in the $_SESSION[] variable and
		nothing else. Selects over (project|sprint|task|part).
	*/

	/*-----------------------------------------------------------------------*
		Public: generator.
		This function decides what's going in the menu when all options
		are evaluated. There aren't much to do here but show simple enough.
	*/
	function generate_menu() {
		$user_id = $_SESSION[ 'user_id' ]; // quickie access.
		$pid = $_SESSION[ 'pp_pid' ];
		$sid = $_SESSION[ 'pp_sid' ];
		$tid = $_SESSION[ 'pp_tid' ];
		$ppid = $_SESSION[ 'pp_ppid' ];
		$menu = '';

		// What to show is decided here. Specifics.
		// If any special cases on the entry of things then make the choices here.
		// Do not alter any function or their signature.
		if( $pid != 0 && $sid == 0 && $tid == 0 && $ppid == 0 ) { // Show project, show:sprints&parts&tasks or show all projects if pid=-1
			$menu = getProjects( $pid, $user_id );
		}
		else if( $pid != 0 && $sid == 0 && $tid != 0 && $ppid == 0 ) { // Show project, show:sprints&parts&tasks or show all projects if pid=-1
			$menu = getProjects( $pid, $user_id );
		}
		else if( $pid > 0 && $sid != 0 && $tid == 0 && $ppid == 0 ) { // Show projects sprint, show:tasks or sprints if sid=-1
			$menu = getSprints( $pid, $sid, $user_id );
		}
		else if( $pid > 0 && $sid > 0 && $tid !=  0 && $ppid == 0 ) { // Show projects sprint, show:tasks, specific hightlighted.
			$menu = getTasks( $pid, $sid, $ppid, $tid, $user_id );
		}
		else if( $pid > 0 && $sid == 0 && $tid == 0 && $ppid != 0 ) { // Show projects part, show:tasks or parts if ppid=-1
			$menu = getProjectParts( $pid, $ppid, $user_id );
		}
		else if( $pid > 0 && $sid == 0 && $tid != 0 && $ppid > 0 ) { // Show projects part, show:tasks, specific hightlighted.
			$menu = getTasks( $pid, $sid, $ppid, $tid, $user_id );
		}
		else {
			$menu = getProjects( -1, $user_id );
		}
		echo $menu;
	}

	/*-----------------------------------------------------------------------*
		Public: show a project in different ways.
		This will always and only show a users projects which they are apart off.
		-1 one will show all the projects a user is apart of or the full content
	*/
	function getProjects( $pid, $user_id ) {
		$menu = '';

		if( $pid == -1 ) {
 			$sql = "SELECT `project_members`.*,`projects`.`name`,`projects`.`status` FROM `projects` INNER JOIN `project_members` ON `projects`.`id`=`project_members`.`project_id` WHERE `project_members`.`user_id`='$user_id' ORDER BY `projects`.`id` DESC";
			$sql_r = queryDB( $sql, 'sql' );
			$menu = '<ul id="show_menu">
								<li> <a href="pp_index.php?action=create&type=project"> Create a new Project </a> </li>
					';
			while( $row = mysql_fetch_assoc( $sql_r ) ) {
				if( $row[ 'status' ] != 'D' ) {
					$menu .= '	<li> <a href=pp_index.php?action=show&pid='.$row[ 'project_id' ].'> '.$row[ 'name' ].' </a> </li>
					';
				}
			}
			$menu .= '</ul>
';
		} // EoI( pid=-1 )

		else { // We have a proper pid
			$time = date( "Y-m-d" );

			$menu .= '<ul id="show_menu">
						<li> <a href="pp_index.php?action=show&pid=-1"> Show All Projects </a> </li>
					';
			$row = mysql_fetch_assoc( queryDB( "SELECT `name` FROM `projects` WHERE `id`=$pid" ) );
			$menu .= '	<li> <a href="pp_index.php?action=show&pid='.$pid.'"> Project: '.$row[ 'name' ].' </a> </li>
					';
			$sql = "SELECT * FROM `sprints` WHERE `project_id`=$pid AND `end_at`>'$time' AND `start_at`<'$time' ORDER BY `end_at` DESC";
			$r_sql = queryDB( $sql );
			while( $row = mysql_fetch_assoc( $r_sql ) ) {
				$sprint_na = $row[ 'name' ];
				$sprint_id = $row[ 'id' ];
				$menu .= '	<li> <a href="pp_index.php?action=show&pid='.$pid.'&sid='.$sprint_id.'"> Sprint: '.$sprint_na.' </a> </li>
					';

				// Grouping tasks to a sprint.
				$sql = "SELECT `id`,`name` FROM `view_sprint_tasks` WHERE `sprint_id`=$sprint_id ORDER BY `id` DESC";
				$r_sql = queryDB( $sql );
				while( $row = mysql_fetch_assoc( $r_sql ) ) {
					$task_id = $row[ 'id' ];
					$task_na = $row[ 'name' ];
					$menu .= '	<li class="indent"> <a href="pp_index.php?action=show&pid='.$pid.'&sid='.$sprint_id.'&tid='.$task_id.'"> Task: '.$task_na.' </a> </li>
					';
				}
			}
			$sql = "SELECT `id`,`name` FROM `project_parts` WHERE `project_id`=$pid";
			$r_sql = queryDB( $sql );
			while( $row = mysql_fetch_assoc( $r_sql ) ) {
				$pp_id = $row[ 'id' ];
				$pp_na = $row[ 'name' ];
				$menu .='	<li> <a href="pp_index.php?action=show&pid='.$pid.'&ppid='.$pp_id.'"> Project Part: '.$pp_na.' </a> </li>
					';
				// Finding all related tasks to the part
				$sql = "SELECT `id`,`name` FROM `view_part_tasks` WHERE `part_id`=$pp_id ORDER BY `id` DESC";
				$r_sql = queryDB( $sql );
				while( $row = mysql_fetch_assoc( $r_sql ) ) {
					$task_id = $row[ 'id' ];
					$task_na = $row[ 'name' ];
					$menu .= '	<li class="indent"> <a href="pp_index.php?action=show&pid='.$pid.'&ppid='.$pp_id.'&tid='.$task_id.'"> Task: '.$task_na.' </a> </li>
					';
				}
			}
			$menu .= '</ul>
';
		}
		return $menu;
	}

	/*-----------------------------------------------------------------------*
		Public: show a sprint or sprints
		This will show all the tasks associated with a sprint. If the -1 toggle
		is used one can see all the sprints attached to the project even if
		they are done or not started.
	*/
	function getSprints( $pid, $sid, $user_id )	{
		$menu = '';
		if( $sid == -1 ) {
			$row = mysql_fetch_assoc( queryDB( "SELECT `name` FROM `projects` WHERE `id`=$pid" ) );
			$pname = $row[ 'name' ];
			$menu .= '	<ul id="show_menu">
									<li> <a href="pp_index.php?action=show&pid='.$_SESSION[ 'pp_pid' ].'"> Project: '.$pname.' </a> </li>
							';
			$sql = "SELECT `id`,`name` FROM `sprints` WHERE `project_id`=$pid ORDER BY `id` DESC";
			$r_sql = queryDB( $sql );

			while( $row = mysql_fetch_assoc( $r_sql ) ) {
				$sp_id = $row[ 'id' ];
				$sp_name = $row[ 'name' ];
				$menu .= '	<li> <a href="pp_index.php?action=show&pid='.$pid.'&sid='.$sp_id.'"> Sprint: '.$sp_name.' </a> </li>
							';
			}
			$menu .= '	</ul>
';
		} //IoE( sid==-1)

		else {
			$row = mysql_fetch_assoc( queryDB( "SELECT `name` FROM `projects` WHERE `id`=$pid" ) );
			$pname = $row[ 'name' ];
			$row = mysql_fetch_assoc( queryDB( "SELECT `name` FROM `sprints` WHERE `id`=$sid" ) );
			$sname = $row[ 'name' ];
			$menu .= '	<ul id="show_menu">
									<li> <a href="pp_index.php?action=show&pid='.$_SESSION[ 'pp_pid' ].'"> Project: '.$pname.' </a> </li>
									<li> <a href="pp_index.php?action=show&pid='.$_SESSION[ 'pp_pid' ].'&sid='.$_SESSION[ 'pp_sid' ].'"> Sprint: '.$sname.' </a> </li>
							';
			$sql = "SELECT `id`,`name` FROM `view_sprint_tasks` WHERE `sprint_id`=$sid ORDER BY `id` DESC";
			$r_sql = queryDB( $sql );
			while( $row = mysql_fetch_assoc( $r_sql ) ) {
				$id = $row[ 'id' ];
				if( !is_null( $id ) ) {
					$na = $row[ 'name' ];
					$menu .= '<li class="indent"> <a href="pp_index.php?action=show&pid='.$pid.'&sid='.$sid.'&tid='.$id.'"> Task: '.$na.' </a> </li>
							';
				}
			}
			$menu .= '<li> <a href="pp_index.php?action=show&pid='.$pid.'&sid=-1"> Show all Sprints for this project </a> </li>
							';
			$menu .= '	</ul>
';
		}
		return $menu;
	}

	/*-----------------------------------------------------------------------*
		Public:
	*/
	function getProjectParts( $pid, $ppid, $user_id ) {
		$menu = '';

		if( $ppid == -1 ) {
			$row = mysql_fetch_assoc( queryDB( "SELECT `name` FROM `projects` WHERE `id`=$pid" ) );
			$pname = $row[ 'name' ];
			$menu .= '	<ul id="show_menu">
									<li> <a href="pp_index.php?action=show&pid='.$_SESSION[ 'pp_pid' ].'"> Project: '.$pname.' </a> </li>
							';
			$sql = "SELECT `id`,`name` FROM `project_parts` WHERE `project_id` ORDER BY `id` DESC";
			$r_sql = queryDB( $sql );
			while( $row = mysql_fetch_assoc( $r_sql ) ) {
				$menu .= '<li> <a href="pp_index.php?action=show&pid='.$pid.'&ppid='.$row[ 'id' ].'"> Part: '.$row[ 'name' ].' </a> </li>
							';
			}
			$menu .= '	</ul>
';
			return $menu;

		} // EoI( ppid =-1 )

		else {
			$row = mysql_fetch_assoc( queryDB( "SELECT `name` FROM `projects` WHERE `id`=$pid" ) );
			$pname = $row[ 'name' ];
			$menu .= '	<ul id="show_menu">
									<li> <a href="pp_index.php?action=show&pid='.$pid.'"> Project: '.$pname.' </a> </li>
							';
			$name = mysql_fetch_assoc( queryDB( "SELECT `name` FROM `project_parts` WHERE `id`=$ppid" ) );
			$menu .= '	<li> <a href="pp_index.php?action=show&pid='.$pid.'&ppid='.$ppid.'"> Part: '.$name[ 'name' ].' </a> </li>
							';
			$sql = "SELECT `id`,`name` FROM view_part_tasks WHERE `part_id`=$ppid ORDER BY `id` DESC";
			$r_sql = queryDB( $sql );
			while( $row = mysql_fetch_assoc( $r_sql ) ) {
				$menu .= '<li class="indent"> <a href="pp_index.php?action=show&pid='.$pid.'&ppid='.$ppid.'&tid='.$row[ 'id' ].'"> Task: '.$row[ 'name' ].' </a> </li>
							';
			}
			$menu .= '<li> <a href="pp_index.php?action=show&pid='.$pid.'&ppid=-1"> Show all project parts of this project </a> </li>
						</ul>
';
		}
		return $menu;
	}

	/*-----------------------------------------------------------------------*
		Public: shows all tasks related to a sprint or project part.
		It will only show a users own tasks when it has an id for a task. The
		-1 option will show the tasks associated with that particular sprint
		or project part. This is for control of the user so one can focus on
		ones own parts.
	*/
	function getTasks( $pid, $sid, $ppid, $tid, $user_id ) {
		$sql = '';
		$menu = '';
		if( $tid < 0 ) {
			$row = mysql_fetch_assoc( queryDB( "SELECT `name` FROM `projects` WHERE `id`=$pid" ) );
			$pname = $row[ 'name' ];
			$menu .= '<ul id="show_menu">
						<li> <a href="pp_index.php?action=show&pid='.$_SESSION[ 'pp_pid' ].'"> Project: '.$pname.' </a> </li>
					';
			if( $sid > 0 ) {
				$row = mysql_fetch_assoc( queryDB( "SELECT `name` FROM `sprints` WHERE `id`=$sid" ) );
				$menu .= '	<li> <a href="pp_index.php?action=show&pid='.$pid.'&sid='.$sid.'"> Sprint: '.$row[ 'name' ].' </a> </li>
					';
				$sql = "SELECT `id`,`name` FROM `view_sprint_tasks` WHERE `sprint_id`='$sid' ORDER BY `id` DESC";
				$r_sql = queryDB( $sql );
				while( $row = mysql_fetch_assoc( $r_sql ) ) {
					$task_id = $row[ 'id' ];
					$task_na = $row[ 'name' ];
					if( $task_id == $tid ) {
						$menu .= '	<li class="selected"> <a href="pp_index.php?action=show&pid='.$pid.'&sid='.$sid.'&tid='.$task_id.'"> Task: '.$task_na.' </a> </li>
					';
					}
					else {
						$menu .= '	<li class="unselected"> <a href="pp_index.php?action=show&pid='.$pid.'&sid='.$sid.'&tid='.$task_id.'"> Task: '.$task_na.' </a> </li>
					';
					}
				}
			} // EoI( sid )

			else if( $ppid > 0 ) {
				$row = mysql_fetch_assoc( queryDB( "SELECT `name` FROM `project_parts` WHERE `id`=$ppid" ) );
				$menu .= '	<li> <a href="pp_index.php?action=show&pid='.$pid.'&ppid='.$ppid.'"> Project Part: '.$row[ 'name' ].' </a> </li>
					';
				$sql = "SELECT `id`,`name` FROM `view_part_tasks` WHERE `part_id`='$ppid' ORDER BY `id` DESC";
				$r_sql = queryDB( $sql );
				while( $row = mysql_fetch_assoc( $r_sql ) ) {
					$task_id = $row[ 'id' ];
					$task_na = $row[ 'name' ];
					if( $task_id == $tid ) {
						$menu .= '	<li class="selected"> <a href="pp_index.php?action=show&pid='.$pid.'&ppid='.$ppid.'&tid='.$task_id.'"> Task: '.$task_na.' </a> </li>
					';
					}
					else {
						$menu .= '	<li class="unselected"> <a href="pp_index.php?action=show&pid='.$pid.'&ppid='.$ppid.'&tid='.$task_id.'"> Task: '.$task_na.' </a> </li>
					';
					}
				}
			} // EoI( ppid )

			$menu .= '</ul>
';
		} // EoI( tid=-1 )

		else {
			$row = mysql_fetch_assoc( queryDB( "SELECT `name` FROM `projects` WHERE `id`=$pid" ) );
			$pname = $row[ 'name' ];
			$menu .= '<ul id="show_menu">
						<li> <a href="pp_index.php?action=show&pid='.$_SESSION[ 'pp_pid' ].'"> Project: '.$pname.' </a> </li>
					';
			if( $sid > 0 ) {
				$row = mysql_fetch_assoc( queryDB( "SELECT `name` FROM `sprints` WHERE `id`=$sid" ) );
				$menu .= '	<li> <a href="pp_index.php?action=show&pid='.$pid.'&sid='.$sid.'"> Sprint: '.$row[ 'name' ].' </a> </li>
					';
				$sql = "SELECT `id`,`name` FROM `view_sprint_tasks` WHERE `user_id`='$user_id' AND `sprint_id`='$sid' ORDER BY `id` DESC";
				$r_sql = queryDB( $sql );
				while( $row = mysql_fetch_assoc( $r_sql ) ) {
					$task_id = $row[ 'id' ];
					$task_na = $row[ 'name' ];
					if( $task_id == $tid ) {
						$menu .= '	<li class="selected"> <a href="pp_index.php?action=show&pid='.$pid.'&sid='.$sid.'&tid='.$task_id.'"> Task: '.$task_na.' </a> </li>
					';
					}
					else {
						$menu .= '	<li class="unselected"> <a href="pp_index.php?action=show&pid='.$pid.'&sid='.$sid.'&tid='.$task_id.'"> Task: '.$task_na.' </a> </li>
					';
					}
				}
				$menu .= '	<li> <a href="pp_index.php?action=show&pid='.$pid.'&sid='.$sid.'&tid=-1"> Show all tasks of this Sprint </a> </li>
					';
			} // EoI( sid )

			else if( $ppid > 0 ) {
				$row = mysql_fetch_assoc( queryDB( "SELECT `name` FROM `project_parts` WHERE `id`=$ppid" ) );
				$menu .= '	<li> <a href="pp_index.php?action=show&pid='.$pid.'&ppid='.$ppid.'"> Project Part: '.$row[ 'name' ].' </a> </li>
					';
				$sql = "SELECT `id`,`name` FROM `view_part_tasks` WHERE `user_id`='$user_id' AND `part_id`='$ppid' ORDER BY `id` DESC";
				$r_sql = queryDB( $sql );
				while( $row = mysql_fetch_assoc( $r_sql ) ) {
					$task_id = $row[ 'id' ];
					$task_na = $row[ 'name' ];
					if( $task_id == $tid ) {
						$menu .= '	<li class="selected"> <a href="pp_index.php?action=show&pid='.$pid.'&ppid='.$ppid.'&tid='.$task_id.'"> Task: '.$task_na.' </a> </li>
					';
					}
					else {
						$menu .= '	<li class="unselected"> <a href="pp_index.php?action=show&pid='.$pid.'&ppid='.$ppid.'&tid='.$task_id.'"> Task: '.$task_na.' </a> </li>
					';
					}
				}
				$menu .= '	<li> <a href="pp_index.php?action=show&pid='.$pid.'&ppid='.$ppid.'&tid=-1"> Show all tasks of this Project Part </a> </li>
					';
			} // EoI( ppid )

			$menu .= '</ul>
';
		}
		return $menu;
	}

?>