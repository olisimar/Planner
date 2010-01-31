<a href="/ad/login.php"> Login </a>

<fieldset style="width:500px;height:750px;";>
<legend> Register user </legend>
	<form action="register.php" method="post"> 
		<p> <label> Username </label> </p>
			<p> <input type="text" name="username" value="" /> </p>
		<p> <label> Password</label> </p>		
			<p> <input type="password" name="password" value="" /> </p>
		<p> <label> Password confirmation </label> </p>
			<p> <input type="password" name="confirm_password" value="" /> </p>
		<p> <label> First name </label> </p>
			<p> <input type="text" name="firstname" value="" /> </p>
		<p> <label> Last name </label> </p>
			<p> <input type="text" name="lastname" value="" /> </p>
		<p> <label> Email </label> </p>	
			<p> <input type="text" name="email" value="" /> </p>
		<p> <label> Description </label> </p>
			<p> <textarea rows="10" cols="30" name="description" value=""> </textarea> </p>
		<p> <input type="submit" name="register" value="Submit" /> </p>
	</form>
</fieldset>

<?php

function register_user() {
	
	include 'DBConnector.php';
	include '/home/malkolm/Documents/salt.php';
	$DB = new DBConnector();	
	$conn = $DB->get_connection();

if(isset($_POST['register'])) {
	$username = mysqli_real_escape_string($conn, $_POST['username']);
	$password = mysqli_real_escape_string($conn, $_POST['password']);
	$confirm = mysqli_real_escape_string($conn, $_POST['confirm_password']);
	$firstname = mysqli_real_escape_string($conn, $_POST['firstname']);
	$lastname = mysqli_real_escape_string($conn, $_POST['lastname']);
	$email = mysqli_real_escape_string($conn, $_POST['email']);
	$description = mysqli_real_escape_string($conn, $_POST['description']);
	$passlength = strlen($password);

	$username_check = "SELECT username FROM users";
	$result = $DB->query($username_check);
	$username_check_result = mysqli_fetch_assoc($result);
	
	$email_check = "SELECT email FROM descriptions";
	$result = $DB->query($email_check);
	$email_check_result = mysqli_fetch_assoc($result);
	
	if ($username == $username_check_result['username'] || $email == $email_check_result['email']) {
			echo "There's already a user with that username and/or email-adress.";
		}
		elseif($password == $confirm) {
				if($passlength > 5 && $passlength < 40) {
					$hashed_password = hash("SHA512", $password.$salt);

					$user_query = "INSERT INTO users
								(username, email, password)
								VALUES ('$username', '$email', '$hashed_password')";
					$DB->query($user_query);

					$last_insert_id = mysqli_insert_id($conn);
		
					$description_query = "INSERT INTO descriptions
								(user_id, firstname, lastname, email, description)
								VALUES ('$last_insert_id', '$firstname', '$lastname', '$email', '$description')";
					$DB->query($description_query);
				}
				else {
					echo "Password has to be between 6 and 39 characters long";
				}				
			}
			else {
				echo "Password and password-confirmation does not match";
			}
		}
	}	

register_user();

?>
