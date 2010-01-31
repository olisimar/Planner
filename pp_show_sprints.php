<?php
	/*--------------------------------------------------------------------------
		Public:
	*/
	function generate_sprint( $pid, $sid, $user_id ) {
		// Presets
		$sprints = mysql_fetch_assoc( queryDB( "SELECT * FROM `sprints` WHERE `id`=$sid" ) );
		$sp_id = $sprints[ 'id' ];
		$sp_name = $sprints[ 'name' ];
		$sp_desc = $sprints[ 'description' ];
		$sp_start = $sprints[ 'start_at' ];
		$sp_end   = $sprints[ 'end_at' ];
		$sp_cr_id = $sprints[ 'user_id' ];
		$creator = mysql_fetch_assoc( queryDB( "SELECT `username` FROM `users` WHERE `id`=$sp_cr_id" ) );
		$sp_cr_na = $creator[ 'username' ];
		$con = '';

		// Context menu
		$con .= '<div id="context_menu">
						<ul>
							<li> <a href="pp_index.php?action=delete&type=sprint&pid='.$pid.'&sid='.$sid.'"> Delete Sprint </a> </li>
							<li> <a href="pp_index.php?action=edit&type=sprint&pid='.$pid.'&sid='.$sid.'"> Edit Sprint </a> </li>
							<li> <a href="pp_index.php?action=create&type=task&pid='.$pid.'&sid='.$sid.'"> Create a new Task For Sprint </a> </li>
						</ul>
					';
		$con .= '</div>
					';


		// Part info
		$con .= '<div id="specific_info">
							<h3> Project Part: '.$sp_name.' </h3>
							<span> Starts: '.$sp_start.' </span>
							<span> Created by: '.$sp_cr_na.' </span>
							<span> Ends: '.$sp_end.' </span>
						';
		$con .= '	<div class="specific_content">
								<h3> Description </h3>
								<div>
									'.$sp_desc.'
								</div>
							</div>
						';
		$con .= '</div>
					';

		// Related tasks
		require_once( 'pp_show_tasks.php' );
		$con .= generate_task_list( $pid, $sid, 0, $user_id );

		return $con;
	}

	/*--------------------------------------------------------------------------
		Public:
	*/
	function generate_sprint_list( $pid, $user_id ) {
		$sprints = queryDB( "SELECT * FROM `sprints` WHERE `project_id`=$pid" );
		$project = mysql_fetch_assoc( queryDB( "SELECT `name` FROM `projects` WHERE `id`=$pid") );
		$now = strtotime( date( "Y-m-d" ) );

		$con .= '<div id="content_list">
							<table>
								<thead>
									<tr>
										<th colspan="3">
											Sprints for Project: '.$project[ 'name' ].'
										</th>
									</tr>
									<tr>
										<th> Sprint Name </th>
										<th> Quick Description </th>
									</tr>
								</thead>
								<tbody>
						';

		while( $sprint = mysql_fetch_assoc( $sprints ) ) {
			$spri_na = $sprint[ 'name' ];
			$spri_id = $sprint[ 'id' ];
			$spri_st = strtotime( $sprint[ 'start_at' ] );
			$spri_en = strtotime( $sprint[ 'end_at' ] );
			$spri_dq = substr( $sprint[ 'description' ], 0,120 );
			if( $now > $spi_st && $now < $spri_en ) { // This is an active sprint
				$con .= '			<tr class="selected">
												<td> <a href="pp_index.php?action=show&pid='.$pid.'&sid='.$spri_id.'"> '.$spri_na.' </a> </td>
												<td> '.$spri_dq.' </td>
											</tr>
						';
			}
			else { // Inactive for some reason ;)
				$con .= '			<tr>
												<td> <a href="pp_index.php?action=show&pid='.$pid.'&sid='.$spri_id.'"> '.$spri_na.' </a> </td>
												<td> '.$spri_dq.' </td>
											</tr>
						';
			}
		} //EoW( sprints )

		$con .= '		</tbody>
							</table>
						</div>
';
		return $con;
	}
?>