<?php
//this is index.php
	include('DBConnector.php');
	$DB = new DBConnector();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Script-Type" content="text/javascript" />
		<meta http-equiv="Content-Style-Type" content="text/css" />
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	</head>

	<body>
		<div id="wrapper">
		
			<div id="header">
			</div>

			<div id="main">
				<div id="context_menu">
				</div>

				<div id="context_info">
				</div>
			</div>

			<div id="footer">
			</div>
			
		</div>
	</body>
</html>