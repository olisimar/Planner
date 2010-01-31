<?php
	/*
		Authour: Werner
		Created: 091010
		
		This is index for the project planning part. Remove once hooked into
		Rickards login/logout
	*/
	session_start();
	$_SESSION[ 'user_id' ] = 1; //Preset for checking.

	require_once( 'pp_general_io.php' );
	require_once( 'pp_chk_in.php' ); // Place last of all, prepares for the views. Pure Script.
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Script-Type" content="text/javascript" />
		<meta http-equiv="Content-Style-Type" content="text/css" />
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link rel="stylesheet" href="project.css" type="text/css" media="screen" />
		<title> Agile Project Planning </title>
	</head>

	<body>
		<div id="wrapper">

			<div id="header">
				<h1>
					Agile Project Planning
				</h1>
			</div> <!-- EoD:header -->

			<div id="main">
				<div id="show_menu">
					<?php
						require_once( 'pp_generate_menu.php' );
						generate_menu();
					?>
				</div> <!-- EoD:show_menu -->

				<div id="context_info">
					<?php
						require_once( 'pp_generate_content.php' );
						generate_content();
					?>
				</div> <!-- EoD:context_info -->
			</div> <!-- EoD:main -->

			<div id="footer">
				<?php
					require_once( 'pp_generate_footer.php' );
					generate_footer();
				?>
			</div> <!-- EoD:footer -->

		</div> <!-- EoD:wrapper -->
	</body>
</html>