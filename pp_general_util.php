<?php
	/* * * * * Util Functions * * * * *
	 *	These functions are for general use on the page, used by all sections of
	 *	the php scripts. It has no script to run it self, it's all for the
	 *	benefit of the other files/scripts.
	 *	Also a nice central point for util functions which is what these are for.
	 * * * * * * * * * * * * * * * * * * */

	/* -- Moved to general_io.php
		Public
		Connect to a database and make a SQL query retuns the result as is.
		refactor. Not effective for the connections but it is self-contained.
		Move out the parts of opening and closing to index.php when that is
		possible.
		Use this one for general purpose access, chain as needed.

	function query_db( $indata ) {
		$resurs = mysql_connect( 'localhost', 'root', 'gabba4' ); // Alter for remote host
		$db = mysql_select_db( 'goodomens', $resurs ); // Alter for remote host

		$result = mysql_query( $indata ) or die( mysql_error() );
		mysql_close( $resurs ); // Put in index.php
		
		return $result;
	}
	*/
	
	/*
		Public
		Cleans any indata and strips it of any extra chars. If it fails it returns
		nothing which in it self is FALSE.
	*/
	function clean( $inData ) {
		$resurs = mysql_connect( 'localhost', 'root', 'gabba4' ); // Alter for remote host
		
		if( get_magic_quotes_gpc() == 1 ) {
			$inData = stripslashes( $inData );
		}
		
		$inData = mysql_real_escape_string( $inData, $resurs );
		if( $inData ) {
			return $inData; // returns the string if it was ok.
		}
		else {
			return "";
		}
		return $inData;
	}
?>