<?php
	/*-----------------------------------------------------------------------*
		Public: shows a task
		This will show a task with with a generated context menu. Beware of
		altering in this but feel free to add. Just remember to complete the
		chain so you don't break the site.
	*/
	function generate_task( $tid, $user_id ) {
		// presets
		$con = '';
		$task_user_id = 0;
		$task_user_name = 'Noone';
		$sid = 0;
		$task_sprint_name = '';
		$ppid = 0;
		$task_part_name = '';
		$pid = 0;
		$project_name = '';

		$task = mysql_fetch_assoc( queryDB( "SELECT * FROM `tasks` WHERE `id`=$tid LIMIT 1" ) );
		$task_est_unit = $task[ 'estimated_units' ];
		$create_id = $task[ 'create_user_id' ];
		$creator = mysql_fetch_assoc( queryDB( "SELECT `username` FROM `users` WHERE `id`=$create_id LIMIT 1" ) );
		$creator_name = $creator[ 'username' ];

		$task_info = mysql_fetch_assoc( queryDB( "SELECT * FROM `view_projects` WHERE `task_id`=$tid LIMIT 1" ) );

		if( is_null( $task_info[ 'user_id' ] ) ) { // Noone was assigned
		}
		else if( $user_id == $task_info[ 'user_id' ] ) { // We are the assigned party
			$task_user_id = $task_info[ 'user_id' ];
			$task_user_name = $task_info[ 'user_name' ];
		}
		else { // someone was assigned but not us.
			$task_user_id = $task_info[ 'user_id' ];
			$task_user_name = $task_info[ 'user_name' ];
		}

		if( !is_null( $task_info[ 'part_name' ] ) ) {
			$ppid = $task_info[ 'part_id' ];
			$task_part_name = $task_info[ 'part_name' ];
		} else {
		}

		if( !is_null( $task_info[ 'sprint_name' ] ) ) {
			$sid = $task_info[ 'sprint_id' ];
			$task_sprint_name = $task_info[ 'sprint_name' ];
		} else {
		}

		$pid = $task_info[ 'id' ];
		$project_name = $task_info[ 'project_name' ];

		$project_info = mysql_fetch_assoc( queryDB( "SELECT * FROM `projects` WHERE `id`=$pid" ) );
		$pro_unit_name = $project_info[ 'unit_name' ];
		$pro_unit_value = $project_info[ 'unit_value' ];

		// Building the context menu here.
		$con .= '<div id="context_menu">
						<ul>
						';

		// Task related things, 3 way break down.
		if( $task[ 'close_user_id' ] == 0 ) {
			if( $sid > 0 ) {
					$con .= '	<li> <a href="pp_index.php?action=edit&type=task&pid='.$pid.'&sid='.$sid.'&tid='.$tid.'"> Edit this Task </a> </li>
								<li> <a href="pp_index.php?action=delete&type=task&pid='.$pid.'&tid='.$tid.'"> Delete Task </a> </li>
							';
			} else if( $ppid > 0 ) {
					$con .= '	<li> <a href="pp_index.php?action=edit&type=task&pid='.$pid.'&sid='.$ppid.'&tid='.$tid.'"> Edit this Task </a> </li>
								<li> <a href="pp_index.php?action=delete&type=task&pid='.$pid.'&tid='.$tid.'"> Delete Task </a> </li>
							';
			} else {
				$con .= '	<li> <a href="pp_index.php?action=edit&type=task&pid='.$pid.'&tid='.$tid.'"> Edit this Task </a> </li>
								<li> <a href="pp_index.php?action=delete&type=task&pid='.$pid.'&tid='.$tid.'"> Delete Task </a> </li>
							';
			}

			// User related things
			if( $user_id == $task_user_id ) {
				$con .= '	<li> <a href="pp_index.php?action=remove&type=member&pid='.$pid.'&tid='.$tid.'&member='.$user_id.'"> Resign from Task </a> </li>
								<li> <a href="pp_index.php?action=close&type=task&pid='.$pid.'&tid='.$tid.'"> Close Task </a> </li>
							';
			}
			else if( $task_user_id == 0 ) {
				$con .= '	<li> <a href="pp_index.php?action=add&type=member&pid='.$pid.'&tid='.$tid.'&member='.$user_id.'"> Assign task to self </a> </li>
							';
			}
			else {
				$con .= '	<li> Task assigned: '.$task_user_name.' </li>
							';
			}

			if( $sid > 0 ) {
				$con .= '	<li> <a href="pp_index.php?action=remove&type=task&pid='.$pid.'&sid='.$sid.'&tid='.$tid.'"> Remove from: '.$task_sprint_name.' </a> </li>
							';
			} // EoI( sid )
			else {
			} // EoE

			// Project part related things
			if( $ppid > 0 ) {
				$con .= '	<li> <a href="pp_index.php?action=remove&type=task&pid='.$pid.'&ppid='.$ppid.'&tid='.$tid.'"> Remove from: '.$task_part_name.' </a> </li>
							';
			} // EoEI( ppid )
			else {
			} // EoE

			// Project related things
			if( $pid > 0 ) {
				$con .= '';
			} // EoEI( pid )
			else {
				// This shouldn't happen. If it does this is a VERY bad thing.
			}
		} // EoI( close_user_id )
		else {
			$con .= '	<li> <a href="pp_index.php?action=open&type=task&pid='.$pid.'&tid='.$tid.'"> Open Task </a> </li>
						';
		} // EoE

		// Sprint related things

		$con .= '</ul>
					</div>
					';

		// Building the showing the task below.
		$con .= '<h3> Name: '.$task[ 'name' ].' </h3>
					<div id="specific_info">
						<span> Assigned to:  '.$task_user_name. '  </span>
						<span> Estimated '.$pro_unit_name.':  '.( $pro_unit_value*$task_est_unit ).'  </span> <br />
						<span> Created at:  '.$task[ 'start_at' ].'  </span>
						<span> Created by:	'.$creator_name.' </span>
					';
		if( $task[ 'close_user_id' ] != 0 ) {
			$closer_id = $task[ 'close_user_id' ];
			$closer = mysql_fetch_assoc( queryDB( "SELECT `username` FROM `users` WHERE `id`=$closer_id" ) );
			$con .= '<br />
						<span> Closed at:  '.$task[ 'end_at' ].'  </span>
						<span> Closed by:  '.$closer[ 'username' ].'  </span>
					';
		}
		$con .= '</div>
					<div class="specific_content">
						<h5> Description: </h5>
						<div>
							'.$task[ 'description' ].'
						</div>
					</div>
';

		return $con;
	} // EoF generate_task()

	/*-----------------------------------------------------------------------*
		Public: shows a list
		This will show a detailed list of every task within a sprint, project
		or part of a project as ids are racked up.
	*/
	function generate_task_list( $pid, $sid, $ppid, $user_id ) {
		// Building the context menu here.
		$tasks = false; // For later checks if something went bad.

		if( $sid > 0 ) {
			$sql = "SELECT * FROM `view_projects` WHERE `sprint_id`=$sid AND `id`=$pid ORDER BY `task_id` DESC";
			$tasks = queryDB( $sql );
		} // EoI( sid )
		else if ( $ppid > 0 ) {
			$sql = "SELECT * FROM `view_projects` WHERE `part_id`=$ppid AND `id`=$pid ORDER BY `task_id` DESC";
			$tasks = queryDB( $sql );
		} // EoEI( ppid )
		else if( $pid > 0 ) {
			$sql = "SELECT * FROM `view_projects` WHERE `id`=$pid ORDER BY `task_id` DESC";
			$tasks = queryDB( $sql );
		} // EoEI( pid )
		else {
			// This shouldn't happen. If it does this is a VERY bad thing.
		}


		// Building the showing the task below.
		if( $tasks != false ) {
			$con = '<div id="content_list">
						<table>
							<thead>
								<tr>
									';
			if( $sid > 0 ) {
				$proj = mysql_fetch_assoc( queryDB( "SELECT `name` FROM `sprints` WHERE `id`=$sid" ) );
				$con .= '<th colspan="5"> Tasks for Sprint: '.$proj[ 'name' ].' </th>
									';
			} else if( $ppid > 0 ) {
				$proj = mysql_fetch_assoc( queryDB( "SELECT `name` FROM `project_parts` WHERE `id`=$ppid" ) );
				$con .= '<th colspan="5"> Tasks for Project Part: '.$proj[ 'name' ].' </th>
									';
			} else {
				$proj = mysql_fetch_assoc( queryDB( "SELECT `name` FROM `projects` WHERE `id`=$pid" ) );
				$con .= '<th colspan="5"> Tasks for Project: '.$proj[ 'name' ].' </th>
									';
			}
			$con .= '</tr>
								<tr>
									<th> Task </th>
									<th> Sprint </th>
									<th> Project Part </th>
									<th> Assigned to </th>
									<th> Status </th>
								</tr>
							</thead>
							<tbody>
							';
			while( $row = mysql_fetch_assoc( $tasks ) ) {
				$proj_n = '';	$spri_n = '';	$part_n = '';	$task_n = ''; // Presets
				$proj_id = 0;	$spri_id = 0;	$part_id = 0;	$task_id = 0; // Presets
				$owner_id = 0;	$owner_name = '';

				$proj_id = $row[ 'id' ];
				$proj_n = $row[ 'project_name' ];
				$task_id = $row[ 'task_id' ];
				$task_n = $row[ 'task_name' ];
				$spri_id = $row[ 'sprint_id' ];
				$spri_n = $row[ 'sprint_name' ];
				$part_id = $row[ 'part_id' ];
				$part_n = $row[ 'part_name' ];
				$owner_id = $row[ 'user_id' ];
				$owner_n = $row[ 'user_name' ];
				$status = mysql_fetch_assoc( queryDB( "SELECT `close_user_id` FROM `tasks` WHERE `id`=$task_id" ) );
				$status = $status[ 'close_user_id' ];

				if( $user_id == $owner_i ) {
					$con .= '	<tr class="selected">
							';
				} else {
					$con .= '	<tr class="unselected">
							';
				}
				$con .= '		<td>
							';

				if( $sid > 0 ) { // !is_null( $row[ 'sprint_id' ] )
					$con .= '			<a href="pp_index.php?action=show&pid='.$proj_id.'&sid='.$part_id.'&tid='.$task_id.'"> '.$task_n.'</a>
							';
				} else if( $ppid > 0 ) { // !is_null( $row[ 'part_id' ] )
					$con .= '			<a href="pp_index.php?action=show&pid='.$proj_id.'&ppid='.$part_id.'&tid='.$task_id.'"> '.$task_n.'</a>
							';
				}	else {
					$con .= '			<a href="pp_index.php?action=show&pid='.$proj_id.'&tid='.$task_id.'"> '.$task_n.'</a>
							';
				}
				$con .= '		</td>
									<td>
							';
				if( is_null( $row[ 'sprint_id' ] ) ) {
					$con .= '			<a href="pp_index.php?action=show&pid='.$proj_id.'&sid=-1"> Unassigned </a>
							';
				} else {
					$con .= '			<a href="pp_index.php?action=show&pid='.$proj_id.'&sid='.$spri_id.'"> '.$spri_n.' </a>
							';
				}
				$con .= '		</td>
									<td>
							';
				if( !is_null( $row[ 'part_id' ] ) ) {
					$con .= '			<a href="pp_index.php?action=show&pid='.$proj_id.'&ppid='.$part_id.'"> '.$part_n.' </a>
							';
				} else {
					$con .= '			<a href="pp_index.php?action=show&pid='.$proj_id.'&ppid=-1"> Unassigned </a>
							';
				}
				$con .= '		</td>
									<td>
							';
				if( !is_null( $row[ 'user_id' ] ) ) {
					$con .= '			<a href="pp_index.php?action=show&pid='.$proj_id.'&member='.$owner_id.'"> '.$owner_n.' </a>
							';
				}	else {
					$con .= '			<a href="pp_index.php?action=show&type=user&pid='.$proj_id.'"> Noone </a>
							';
				}
				$con .= '	</td>
								<td>
							';
				if( $status != 0 ) {
					$con .= ' Closed
							';
				} else {
					$con .= ' Active
							';
				}
				$con .= '	</td>
								</tr>
							';

			}
			$con .= '</tbody>
						</table>
					</div>
';
			return $con;
		} // EoI( tasks )
		else {
			echo 'Weird... <br />';
		} // EoE

	} // EoF generate_task()
?>