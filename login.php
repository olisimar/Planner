<?php session_start(); ?>

<a href="/ad/register.php"> Register </a>

<fieldset>
	<legend> Login </legend>
	<form action="login.php" method="post">
		<label> Username: </label>
		<input type="text" name="username" value="" />
		<label> Password: </label>
		<input type="password" name="password" value="" />
		<input type="submit" name="login" value="Login" />
	</form>
</fieldset>

<?php

function login() {
	include 'DBConnector.php';
	include '/home/malkolm/Documents/salt.php';	

	if(isset($_POST['login'])) {
		$DB = new DBConnector();
		$conn = $DB->get_connection();

		$username = mysqli_real_escape_string($conn, $_POST['username']);
		$password = mysqli_real_escape_string($conn, $_POST['password']);
		
		$hashed_password = hash("SHA512", $password.$salt);

		$login_query = "SELECT * FROM users WHERE (username = '$username' OR email='$username') 
													AND password = '$hashed_password' LIMIT 1";
		$result = $DB->query($login_query);

		$user = mysqli_fetch_assoc($result);
			if ($user['id'] && ($user['username'] == $username || $user['email'] == $username) 
												&& $user['password'] == $hashed_password) {

				$_SESSION['id'] = $row['id'];

				$user_id = $user['id'];
				$current_time = time();
				$current_date = date("Y-m-d H:m:s", $current_time);
				$expiry_time = time()+1200;
				$expiry_date = date("Y-m-d H:m:s", $expiry_time);

				$session_creation = "INSERT INTO sessions
									(user_id, expires_at)
									VALUES ('$user_id', '$expiry_date')";
				$DB->query($session_creation);
		echo "You are logged in";
	} else {
		echo "Username or password does not match any entry in the database.";
		}
	}
}

login();

include 'session_time_check.php';

?>


