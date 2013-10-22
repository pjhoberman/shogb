<?
	$ip[] = '70.91.172.41';
	$ip[] = '24.9.97.178';

	if( !in_array( $_SERVER['REMOTE_ADDR'], $ip ) )
		header( "Location: /" );
		
	require_once( $_SERVER['DOCUMENT_ROOT'] . '/include/db.inc.php' );
	require_once( $_SERVER['DOCUMENT_ROOT'] . '/include/mysql_db.class.php' );
	require_once( $_SERVER['DOCUMENT_ROOT'] . '/include/choices.class.php' );

	if( $_POST )
	{
		$choice = new choices;
		$choice -> name = $_POST['name'];
		$choice -> location = $_POST['location'];
		$choice -> type = $_POST['type'];
		
		$choice -> add_to_db();
		
	}
	
?>

<form action="" method="post">
Name: <input type="text" name="name" /><br />
Location: <input type="text" name="location" /><br/>
Steakhouse: <input type="radio" name="type" value="steakhouse" /><br />
Gaybar:<input type="radio" name="type" value="gaybar" /><br />
<input type="submit" value="submit" />
</form>