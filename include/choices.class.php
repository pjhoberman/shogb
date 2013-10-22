<?
	require_once( $_SERVER['DOCUMENT_ROOT'] . "/include/mysql_db.class.php" );
	
	class choices extends mysql_db_object
	{
		function right()
		{
			$this -> right ++;
			$this -> update_db();
		} // correct
		
		function wrong()
		{
			$this -> wrong ++;
			$this -> update_db();
		} // wrong
	} // class choices
?>