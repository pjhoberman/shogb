<?		
	require_once( $_SERVER['DOCUMENT_ROOT'] . '/include/db.inc.php' );
	require_once( $_SERVER['DOCUMENT_ROOT'] . '/include/mysql_db.class.php' );
	require_once( $_SERVER['DOCUMENT_ROOT'] . '/include/guess_log.class.php' );
	require_once( $_SERVER['DOCUMENT_ROOT'] . '/include/choices.class.php' );

	if( isset( $_GET['update'] ) )
	{
		$choices = new choices;
		$choices = $choices -> get_from_db( '1=1', 'name');
		
		foreach( $choices as $choice )
		{
			$right += $choice -> right;
			$wrong += $choice -> wrong;
			
		} // foreach
		
		$total = $right + $wrong;
		
		$log = new guess_log;
		$log -> guesses = $total;
		$log -> time = date( 'Y-m-d H:i:s' );
		$log -> correct =  round( ( $right / $total ) * 100, 2 );
		$log -> add_to_db();
		
		header( "Location: /log.php" );
	} // if


	$logs = new guess_log;
	$logs = $logs -> get_from_db();



	?>
	<form action="" method="get">
		<input type="submit" value="Update Log" name="update" />
	</form>

	<table border="1" cellpadding="5">
		<tr>
			<td>Date</td>
			<td>Guesses</td>
			<td>Correct</td>
			<td>Timestamp</td>
			<td>Time since last log (hours)</td>
			<td>Guesses since last log</td>
			<td>Guesses / minute since last log</td>
		</tr>
	<?
	$last_timestamp = '';
	$last_guesses = '';
	
	foreach( $logs as $log )
	{
		if( $last_timestamp != '' )
		{
			$elapsed_time = ( strtotime( $log -> time ) - $last_timestamp )/(60*60);
			
			
			$hours = round( ( strtotime( $log -> time ) - $last_timestamp )/(60*60), 0 );
			$minutes = round( ( strtotime( $log -> time ) - $last_timestamp )%(60*60)/60, 0 );
			 
			$display_time = $hours ? $hours . ' hours, ':'';
			$display_time .= $minutes . ' minutes';

		} // if
		
		else
			$elapsed_time = '-';
		
		if( $last_guesses != '' )
			$new_guesses = $log -> guesses - $last_guesses;
	
		else
			$new_guesses = '-';
			
		if( $new_guesses == '-' || $elapsed_time == '-' )
			$rate = '-';
		
		else
			$rate = round( $new_guesses/($elapsed_time*60), 1 );
			
		
		
		echo '<tr class="record">';
			echo '<td class="date">' . date( 'n/j/Y g:i A', strtotime( $log -> time ) ) . '</td>';
			echo '<td class="total">' . $log -> guesses . '</td>';
			echo '<td class="correct">' . $log -> correct . '</td>';
			echo '<td class="timestamp">' . strtotime( $log -> time ) . '</td>';
			echo '<td class="elapsed_time">' . $display_time . '</td>';
			echo '<td class="new_guesses">' . $new_guesses . '</td>';
			echo '<td class="rate">' . $rate . '</td>';
		echo '</tr>';
		
		$last_timestamp = strtotime( $log -> time );
		$last_guesses = $log -> guesses;
	} // foreach

?>

	</table>
	
	<!-- Woopra Code Start -->
<script type="text/javascript" src="//static.woopra.com/js/woopra.v2.js"></script>
<script type="text/javascript">
woopraTracker.track();
</script>
<!-- Woopra Code End -->