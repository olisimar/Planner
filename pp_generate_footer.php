<?php
	/*
		This will generate the footer of the page.
	*/

	/*------------------------------------------------------------------------*
	*/
	function generate_footer() {
		$print = '';
		$user_id = $_SESSION[ 'user_id' ];
		$print = '<span> UserID:</span> '.$user_id.'<br />
				';
		$pid = $_SESSION[ 'pp_pid' ];
		$sid = $_SESSION[ 'pp_sid' ];
		$tid = $_SESSION[ 'pp_tid' ];
		$ppid = $_SESSION[ 'pp_ppid' ];
		$menu = '';
		// What to show is decided here. Specifics
		$print .= '<span> pid:'.$pid.'| sid:'.$sid.'| tid:'.$tid.'| ppid:'.$ppid.' <span> <br />
				';
		$action = $_SESSION[ 'pp_action' ];
		$type = $_SESSION[ 'pp_type' ];
		$print .= '<span> action: '.$action.' | type: '.$type.' <span> <br />
';
		echo $print;
	}
?>