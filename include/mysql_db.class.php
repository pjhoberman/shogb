<?

	class mysql_db_object {
	
		var $mysql_db_object_local_only = array();
		var $connection = DB;
		var $database = DB_DATABASE;
		var $table;
		var $primary_key = 'id';
		var $last_mysql_query;
		var $last_mysql_error;
		
		function mysql_db_object( $object = NULL ) 
		{
		
			while( list( $key , ) = each( $this ) )
				$this->mysql_db_object_local_only[ $key ] = TRUE;
				
			if ( $object !== NULL )
			{

				while( list( $key , $value ) = each( $object ) )
					$this->{ $key } = $value;
			
			} // if
			
			if ( ! isset( $this->table ) )
				$this->table = get_class( $this );
			
		} // mysql_db_object
		
		function database_table()
		{
		
			return '`' . $this->database . '`.`' . $this->table . '`';
		
		} // database_table
		
		function update_db() 
		{
			$update_sql = 'UPDATE ' . $this->database_table() . ' SET ';

			$update_parts = array();
			
			reset( $this );
			while( list( $key , $value ) = each( $this ) ) {
			
				if (
					( ! $this->mysql_db_object_local_only[ $key ] ) &&
					( $key != $this->primary_key )
				) {
				
					if ( $value != 'NOW()' )
					{
				
						$update_part = '`' . $key . "` = '";
						$update_part.= addslashes( $value ) . "'";
					
					} // if
					else					
						$update_part = '`' . $key . "` = " . $value;
				
					$update_parts[] = $update_part;
				
				} // if
				
			} // while
			$update_sql.= implode( ' , ' , $update_parts );
			$update_sql.= ' WHERE `' . $this->primary_key . "` = '";
			$update_sql.= addslashes( $this->{ $this->primary_key } ) . "'";
			
			$this -> last_mysql_query = $update_sql;
			
			return mysql_query( $update_sql , $this->connection );
		
		} // update_db
		
		function add_to_db()
		{
		
			$insert_sql = 'INSERT INTO ' . $this->database_table() . ' (';
			
			$keys = array();
			$values = array();
			
			reset( $this );
			while( list( $key , $value ) = each( $this ) ) {
			
				if (
					( ! $this->mysql_db_object_local_only[ $key ] ) &&
					( $key != $this->primary_key )
				) {
				
					$keys[] = '`' . $key . "`";
					$values[] = ( $value != 'NOW()' ) ?
						"'" . addslashes( $value ) . "'" : $value;
				
				} // if
				
			} // while
			
			$insert_sql.= implode( ' , ' , $keys ) . ' ) VALUES ( ';
			$insert_sql.= implode( ' , ' , $values ) . ' )';

			$this -> last_mysql_query = $insert_sql;
			
			$result = mysql_query( $insert_sql , $this->connection );
			
			if ( ! $result )
				$this -> last_mysql_error = mysql_error();

			$this->{ $this->primary_key } = mysql_insert_id( $this->connection );

			return $result;
		
		} // add_to_db
			
		function delete_from_db()
		{

			$delete_sql = 'DELETE FROM ' . $this->database_table() . ' 
			WHERE `' . $this->primary_key . "` = '" . addslashes( $this->{ $this->primary_key } ) . "'";
			
			$this -> last_mysql_query = $delete_sql;

			return mysql_query( $delete_sql , $this->connection );
		
		} // delete_from_db
		
		function get_from_db( 
			$where = '1 = 1' , 
			$order = '' , 
			$limit = 0 
		)
		{

			if ( $order == '' ) $order = '`' . $this -> primary_key . '` ASC';

			$results = array();

			$class = get_class( $this );

			$select_sql = 'SELECT * FROM ' . $this->database_table() . '
			WHERE ' . $where . ' ORDER BY ' . $order;
			if ( $limit != 0 ) $select_sql.= ' LIMIT ' . $limit;
	
			$this -> last_mysql_query = $select_sql;
	
			$select_result = mysql_query( $select_sql , $this->connection );
	
			if ( ! $select_result )
				$this -> last_mysql_error = mysql_error();
			
			echo( $this -> last_mysql_error );

			if ( mysql_num_rows( $select_result ) > 0 )
			{
				while ( $result = mysql_fetch_object( $select_result ) )
					$results[] = new $class( $result );

			} // if

			return $results;

		} // get_from_db

	} // mysql_db_object

?>