<?php
	/*
		Created: 091012
		Authour: Werner / Tobias Landen

		This is DBConnector.php, made for the single purpose of giving a simple
		database connection. Check index.php for how to get hold of one.
	*/
	
class DBConnector{
  private static $conn = null;
	
	public static function get_instance() {
		if(DBConnector::$conn == null){
			DBConnector::$conn = mysqli_connect( "localhost", "avancwebb08", "host09" );
		  mysqli_select_db( DBConnector::$conn,"dev_avancwebb08" );
		}
		return DBConnector::$conn;
	}

	public static function query( $SQL ){
		return mysqli_query( DBConnector::get_instance(),$SQL );
	}

	public static function clean( $string ) {
 		$cleanString = "";
 		if( is_numeric( $string ) ){
			$cleanString = $string;
 		}
 		else{
			$cleanString =  "'" . mysqli_real_escape_string(DBConnector::get_instance(),$string) . "'";
	 	}
 		return $cleanString;
	}

	public static function close(){
		mysqli_close(DBConnector::get_instance());
		DBConnector::$conn = null;
	}
}
?>

