<?
	session_start();

		
	require_once( $_SERVER['DOCUMENT_ROOT'] . '/include/db.inc.php' );
	require_once( $_SERVER['DOCUMENT_ROOT'] . '/include/mysql_db.class.php' );
	require_once( $_SERVER['DOCUMENT_ROOT'] . '/include/choices.class.php' );
	require_once( $_SERVER['DOCUMENT_ROOT'] . '/include/guess_log.class.php' );
	
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

?>
	
Right = <?= $right ?><br />
Wrong = <?= $wrong ?><br />
Total = <?= $total ?><br />
Ratio = <?= round( $right / $wrong, 3 )?> <br />
Percent Right = <?= round( ( $right / $total ) * 100, 2 )?>

<hr />
<table border=1 cellpadding=3>
	<tr>
		<td>#</td>
		<td onclick="sort('name')">Name</td>
		<td onclick="sort('location')">Location</td>
		<td onclick="sort('type')">Type</td>
		<td onclick="sort('right')">Right</td>
		<td onclick="sort('wrong')">Wrong</td>
		<td onclick="sort('total')">Total</td>
		<td onclick="sort('right')">% Right</td>
		<td onclick="sort('total')">% Shown</td>
		<td onclick="sort('total')">Shown</td>
	</tr>

<?
	$c = 0;
	foreach( $choices as $choice )
	{
		$c++;
		$choice_total = $choice -> right + $choice -> wrong;
		
		$shown = round( ( $choice_total / $total ) * 100, 1 );
			
		echo '<tr>';
			echo '<td>' . $c . '</td>';
			echo '<td>' . $choice -> name . '</td>';
			echo '<td>' . $choice -> location . '</td>';
			echo '<td>' . $choice -> type . '</td>';
			echo '<td>' . $choice -> right . '</td>';
			echo '<td>' . $choice -> wrong . '</td>';
			echo '<td>' . $choice_total . '</td>';
			echo '<td>' . round( ( $choice -> right / ($choice -> right + $choice -> wrong ) ) * 100, 2 ) . '</td>';
			echo '<td>' . $shown . '</td>';
			echo '<td><div style="width:' . $shown*100 . 'px; background-color: #180; height: 1em;"></div></td>';
		echo '</tr>';
	}
?>

</table>

<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
try {
var pageTracker = _gat._getTracker("UA-10614593-1");
pageTracker._trackPageview();
} catch(err) {}</script>

<!-- Woopra Code Start -->
<script type="text/javascript" src="//static.woopra.com/js/woopra.v2.js"></script>
<script type="text/javascript">
woopraTracker.track();
</script>
<!-- Woopra Code End -->