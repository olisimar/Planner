<?php

function logout() {
	if(isset($_POST['logout'])) {
		session_destroy();
		if ($_SESSION['id'] == null) {
			echo "Logged out";
		} 
		else {
			echo "Failed to log out. Session is still active.";
		}
	}
}

logout();

?>
