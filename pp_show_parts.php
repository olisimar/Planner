<?php
	/*--------------------------------------------------------------------------
		Public:
	*/
	function generate_part( $pid, $ppid, $user_id ) {
		// Presets
		$project_parts = mysql_fetch_assoc( queryDB( "SELECT * FROM `project_parts` WHERE `id`=$ppid" ) );
		$pp_id = $project_parts[ 'id' ];
		$pp_name = $project_parts[ 'name' ];
		$pp_desc = $project_parts[ 'description' ];
		$pp_cust = $project_parts[ 'customer' ];
		$pp_cr_id = $project_parts[ 'user_id' ];
		$creator = mysql_fetch_assoc( queryDB( "SELECT `username` FROM `users` WHERE `id`=$pp_cr_id" ) );
		$pp_cr_na = $creator[ 'username' ];
		$con = '';
		// Context menu
		$con .= '<div id="context_menu">
						<ul>
							<li> <a href="pp_index.php?action=delete&type=part&pid='.$pid.'&ppid='.$ppid.'"> Delete Project Part </a> </li>
							<li> <a href="pp_index.php?action=edit&type=part&pid='.$pid.'&ppid='.$ppid.'"> Edit Project Part </a> </li>
							<li> <a href="pp_index.php?action=create&type=task&pid='.$pid.'&ppid='.$ppid.'"> Create a new Task For Project Part </a> </li>
						</ul>
					';
		$con .= '</div>
					';


		// Part info
		$con .= '<div id="specific_info">
							<h3> Project Part: '.$pp_name.' </h3>
						';
		$con .= '	<div class="specific_content">
								<h3> Description </h3>
								<div>
									'.$pp_desc.'
								</div>
							</div>
							<div class="specific_content">
								<h3> Customer </h3>
								<div>
									'.$pp_cust.'
								</div>
							</div
						';
		$con .= '</div>
					';

		// Related tasks
		require_once( 'pp_show_tasks.php' );
		$con .= generate_task_list( $pid, 0, $ppid, $user_id );

		return $con;
	}

	/*--------------------------------------------------------------------------
		Public:
	*/
	function generate_part_list( $pid, $user_id ) {
		$parts = queryDB( "SELECT * FROM `project_parts` WHERE `project_id`=$pid" );
		$project = mysql_fetch_assoc( queryDB( "SELECT `name` FROM `projects` WHERE `id`=$pid") );
		$now = strtotime( date( "Y-m-d" ) );

		$con .= '<div id="content_list">
							<table>
								<thead>
									<tr>
										<th colspan="3">
											Project Part of Project: '.$project[ 'name' ].'
										</th>
									</tr>
									<tr>
										<th> Project Part Name </th>
										<th> Quick Description </th>
									</tr>
								</thead>
								<tbody>
						';

		while( $part = mysql_fetch_assoc( $parts ) ) {
			$part_na = $part[ 'name' ];
			$part_id = $part[ 'id' ];
			$part_dq = substr( $part[ 'description' ], 0,120 );

			$con .= '			<tr>
												<td> <a href="pp_index.php?action=show&pid='.$pid.'&ppid='.$part_id.'"> '.$part_na.' </a> </td>
												<td> '.$part_dq.' </td>
											</tr>
						';
		} //EoW( parts )

		$con .= '		</tbody>
							</table>
						</div>
';
		return $con;
	}
?>