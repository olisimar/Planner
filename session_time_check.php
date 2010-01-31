<?php
if ($_GET) {
	$session_id = $_SESSION['id'];
	$check_time = time();
	$check_date = date("Y-m-d H:m:s", $check_time);

	function session_check($session_id, $check_date) {
		$DB = new DBConnector();		

		$session_query = "SELECT * FROM sessions WHERE id = '$session_id' LIMIT 1";
		$DB->query($session_query);
		if ($check_date - $row['expires_at'] <= 60) {
			session_destroy();
		}
		else {
			$update_session = "UPDATE INTO sessions WHERE id = '$session_id'
									(expires_at)
									VALUES ($current_time)";
			$DB->query($update_session);
			$_SESSION['id'] = $row['id'];
			$session_id = $_SESSION['id'];	
		}
	}

	session_check($session_id, $current_time);
}

	if ($session_id > 0) {
		echo "Inloggad";
	}
?>
