<?
	session_start();

		
	require_once( $_SERVER['DOCUMENT_ROOT'] . '/include/db.inc.php' );
	require_once( $_SERVER['DOCUMENT_ROOT'] . '/include/mysql_db.class.php' );
	require_once( $_SERVER['DOCUMENT_ROOT'] . '/include/choices.class.php' );
	
	$choices = new choices;
	
	if( isset( $_POST['choice'] ) )
		$id = $_POST['choice'];
	
	else
		$id = -1;

	do{ 
		list( $choice ) = $choices -> get_from_db( '1=1', 'RAND()' ); 
		echo '<br />';
		echo $choice -> id;
	}
	
	while ($choice -> id == $id );
	

	$message = '';
	$message_class = 'hidden';

	if( isset( $_POST['kill'] ) && $_POST['kill'] == true )
	{
		
		$_SESSION['right'] = 0;
		$_SESSION['wrong'] = 0;

	} // if
	
	else if( isset( $_POST['choice'] ) && isset( $_POST['selection'] ) )
	{
		list( $previous_choice ) = $choices -> get_from_db( '`id` = ' . $_POST['choice'] );
		if( count( $previous_choice ) )
		{
			if( $_SESSION['right'] == '' )
				$_SESSION['right'] = 0;
				
			if( $_SESSION['wrong'] == '' )
				$_SESSION['wrong'] = 0;
			
			if( $previous_choice -> type == $_POST['selection'] )
			{
				$correct = true;
				$previous_choice -> right();
				$_SESSION['right'] ++;
			} // if
			
			else
			{
				$correct = false;
				$previous_choice -> wrong();
				$_SESSION['wrong'] ++;
			} // else
			
			$total = $previous_choice -> right + $previous_choice -> wrong;
			$session_total = $_SESSION['right'] + $_SESSION['wrong'];
			
			$percentage = $correct ? $previous_choice -> right / $total : $previous_choice -> wrong / $total;
			$percentage *= 100;
			$percentage = round( $percentage, 2 );

			$message = $previous_choice -> name . ' is a ';
			$message .= $previous_choice -> type == 'steakhouse' ? 'steak house' : 'gay bar';
			$message .= $previous_choice -> location != '' ? ' in ' . $previous_choice -> location . '.<br />' : '.<br />';
			
			$message .= $percentage . '% got that ';
			$message .= $correct ? 'right':'wrong';
			$message .= ', too.<br />';
			
			$message .= "You're " . $_SESSION['right'] . ' for ' . $session_total . ', or ' . round( ( $_SESSION['right'] / $session_total ) * 100 ) . '%.';
			$message .= ' <span id="kill_session">clear this</span>';
			
			$message_class = $correct ? 'right':'wrong';
			
			
			
		} // if
	} // if
	
		 $_POST['choice'] = NULL;
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>Steak House or Gay Bar?</title>
		
		<link rel="stylesheet" type="text/css" href="/css/main.css" />
		
		<script type="text/javascript" src="/js/jquery-1.3.2.min.js"></script>
		<script type="text/javascript" src="/js/stuff.js"></script>
	</head>
	<body>
		<input type="hidden" id="choice_id" name="choice" value="<?= $choice -> id ?>" />
		
		<div id="message" class="<?= $message_class ?>"><?= $message ?></div>
		<div id="choice"><?= ucfirst( htmlspecialchars( $choice -> name ) ) ?></div>
		<div id="selection">
			<div id="steakhouse">Steak House</div>
			<div id="gaybar">Gay Bar</div>
		</div>
	<div id="ads">
		<script type="text/javascript">
			<!-- 
			google_ad_client = "pub-4187553581216573"; /* steakhouseorgaybar */ 
			google_ad_slot = "5844623897"; 
			google_ad_width = 728; 
			google_ad_height = 90; 
			//-->
		</script> 
		<script type="text/javascript" src="http://pagead2.googlesyndication.com/pagead/show_ads.js"></script>
	</div>	
		<div id="info">Something broken? Have a suggestion?<br /><a href="mailto:heyyou@steakhouseorgaybar.com">say hey</a></div>

	<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
try {
var pageTracker = _gat._getTracker("UA-10614593-1");
pageTracker._trackPageview();
} catch(err) {}</script>
	
	</body>
</html>