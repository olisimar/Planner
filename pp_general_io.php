<?php
/* * * * * * * * * * * * General in/out-data from DB * * * * * * * * * *
 * For general purposes uses as how to transformation of data that is
 * drawn from the database. This also holds the general purpose database
 * SQL pusher and receiver.
 */
 
	/*-----------------------------------------------------------------------*
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

	/*-----------------------------------------------------------------------*
		Public
		Connect to a database and make a SQL query retuns the result as is.
		refactor. Not effective for the connections but it is self-contained.
		Move out the parts of opening and closing to index.php when that is
		possible.
		Use this one for general purpose access, chain as needed.
		
		TYPE is for the type of result you want, SQL, array of arrays or JSON.
	*/
	function queryDB( $sql, $type='sql' ) {
		$resurs = mysql_connect( 'localhost', 'root', 'gabba4' );
		$db = mysql_select_db( 'dev_avancwebb08', $resurs );
		
		$result = mysql_query( $sql ) or die( mysql_error() );
		mysql_close( $resurs );
		
		if( ( $type == 'sql' ) || ( $type == 'SQL' ) ) {
			return $result;
		}
		else {
			return convertTo( $type, $result );
		}
	}

	/*-----------------------------------------------------------------------*
		Private: util
		This is for my convertion of SQL resultsets to arrays or json
		arrays. If type doesn't match JSON or json it returns the array. It
		returns the array in mysql_fetch_assoc() format.
	*/
	function convertTo( $type, $inData ) {
		$outData;
		
		while( $row = mysql_fetch_assoc( $indata ) ) {
			$outData[] = $row;
		}

		if( $type == 'JSON' || $type == 'json' ) {
			$outData = json_encode( $json );
		}

		return $outData;
	}
?>