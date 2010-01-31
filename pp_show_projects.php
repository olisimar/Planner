<?php
	/*--------------------------------------------------------------------------
		Public: shows a specific project
		This will list the tasks, sprints and parts that is collected under the
		specific project. It will show this with the full list of tasks taken from
		pp_show_tasks.php - generate_list( $pid, 0, 0, $user_id )
	*/
	function generate_project( $pid, $user_id ) {
		// Presets and Pregets
		$project = mysql_fetch_assoc( queryDB( "SELECT * FROM `projects` WHERE `id`=$pid" ) );
		$pro_name = $project[ 'name' ];
		$pro_desc = $project[ 'description' ];
		$pro_unit = $project[ 'unit_name' ];
		$pro_value = $project[ 'unit_value' ];
		$pro_creat = $project[ 'user_id' ];
		$pro_custm = $project[ 'customer' ];	// Can be Null
		$pro_start = $project[ 'start_at' ];
		$pro_endat = $project[ 'end_at' ];		// Can be Null
		$pro_forum = $project[ 'forum_id' ];	// Can be Null
		$pro_status = $project[ 'status' ];
		if( $pro_status == 'A' ) {
			$pro_status = 'Active';
		} else if( $pro_status == 'D' ) {
			$pro_status = 'Inactive';
		}	else {
			$pro_status = 'Secret';
		}

		$createdby = mysql_fetch_assoc( queryDB( "SELECT `username` FROM `users` WHERE `id`=$pro_creat" ) );
		$pro_cr_na = $createdby[ 'username' ]; // Lots of these, this gets the creators name.

		$con = '';

		// Adding the context menu here.
		$con .= '<div id="context_menu">
						<ul>
						';
		if( $pro_status == 'Active' ) {
			$con .=	'<li> <a href="pp_index.php?action=create&type=task&pid='.$pid.'"> Create a new Task </a> </li>
							<li> <a href="pp_index.php?action=create&type=sprint&pid='.$pid.'"> Create a new Sprint </a> </li>
							<li> <a href="pp_index.php?action=create&type=part&pid='.$pid.'"> Create a new Project Part </a> </li>
							<li> <a href="pp_index.php?action=edit&type=project&pid='.$pid.'"> Edit Project </a> </li>
							<li> <a href="pp_index.php?action=add&type=membert&pid='.$pid.'"> Add more Project Members </a> </li>
							<li> <a href="pp_index.php?action=close&type=project&pid='.$pid.'"> Deactivate Project </a> </li>
						</ul>
					';
		} else {
			$con .=	'	<li> <a href="pp_index.php?action=opent&type=project&pid='.$pid.'"> Activate Project </a> </li>
						</ul>
					';
		}
		$con .= '</div>
					';

		// Adding a floating members list.
		$con .= '<div id="members_list">
						<table>
							<thead>
								<tr>
									<th colspan="2">
										Project Members
									</th>
								</tr>
								<tr>
									<th> Name </th>
									<th> Role </th>
								</tr>
							</thead>
							<tbody>
							';
		$members = queryDB( "SELECT * FROM `project_members` WHERE `project_id`=$pid" );
		while( $member = mysql_fetch_assoc( $members ) ) {
			$member_id = $member[ 'user_id' ];
			$name = mysql_fetch_assoc( queryDB( "SELECT `username` FROM `users` WHERE `id`=$member_id") );
			$member_name = $name[ 'username' ];
			$member_role = $member[ 'role' ];
			$con .= '	<tr>
									<td> <a href="pp_index.php?action=show&pid='.$pid.'&member='.$member_id.'"> '.$member_name.' </a> </td>
									<td> '.$member_role.' </td>
								</tr>
							';
		}
		$con .= '	</tbody>
						</table>
					</div>
					';

		// Project Info
		$con .= '<div id="specific_info">
						<h3> Project: '.$pro_name.' </h3>
						<span> Starts at: '.$pro_start.' </span>
						<span> Created by: '.$pro_cr_na.' </span>
					';
		if( $pro_forum != 0 ) {
			$forum = mysql_fetch_assoc( queryDB( "SELECT `name` FROM `forums` WHERE `id`=$pro_forum" ) );
			$con .= '	<span> Forum: <a href=""> '.$pro_forum.' </a> </span>
					';
		}
		if( !is_null( $project[ 'end_at' ] ) ) {
			$con .= '	<span> End at: '.$pro_endat.' </span>
					';
		}
		$con .= '	<span> Status: '.$pro_status.'
					';
		$con .= '<div class="specific_content">
							<h3> Description </h3>
							<div>
								'.$pro_desc.'
							</div>
						</div>
						<div class="specific_content">
							<h3> Customer </h3>
							<div>
								'.$pro_custm.'
							</div>
						</div>
					</div>
					';


		// Adding all the tasks related to the project.
		require_once( 'pp_show_tasks.php' );
		$con .= generate_task_list( $pid, 0, 0, $user_id );

		// Sending back the completed page
		return $con;
	}

	/*--------------------------------------------------------------------------
		Public: generate a list
		This will produce a list of all the tasks that is related to the user
		it self. It will not list projects that aren't for the user. It will
		have a title and possibly a short description.
	*/
	function generate_project_list( $user_id ) {
		$con = '';
		$projects = queryDB( "SELECT * FROM `project_members` WHERE `user_id`=$user_id ORDER BY `project_id` DESC" );

		$con .= '<div id="content_list">
							<table>
								<thead>
									<tr>
										<th colspan="4">
											Your Projects
										</th>
									</tr>
									<tr>
										<th> Project Name </th>
										<th> Your Role </th>
										<th> Quick Description </th>
										<th> Status </th>
									</tr>
								</thead>
								<tbody>
						';
		while( $project = mysql_fetch_assoc( $projects ) ) {
			$pro_id = $project[ 'project_id' ];
			$proj = mysql_fetch_assoc( queryDB( "SELECT * FROM `projects` WHERE `id`=$pro_id" ) );
			$pro_role = $project[ 'role' ];
			$pro_name = $proj[ 'name' ];

			$pro_status = $proj[ 'status' ];
			if( $pro_status == 'A' ) {
				$pro_status = 'Active';
			} else if( $pro_status == 'D' ) {
				$pro_status = 'Inactive';
			}	else {
				$pro_status = 'Secret';
			}

			$pro_qd = substr( $proj[ 'description' ], 0, 127 );
			$con .= '			<tr>
											<td> <a href="pp_index.php?action=show&pid='.$pro_id.'"> '.$pro_name.' </a> </td>
											<td> '.$pro_role.' </td>
											<td> '.$pro_qd.' </td>
											<td> '.$pro_status.' </td>
										</tr>
						';

		}
		$con .= '		</tbody>
							</table>
						</div>
';

		return $con;
	}
?>