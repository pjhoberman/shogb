$( document ).ready(

	function()
	{
		$( '#steakhouse, #gaybar' ).click(
			function()
			{
				var form = $( '<form action="" method="post" id="form"></form>' )
			
				form
					.append( '<input type="hidden" name="choice" value="' + $( '#choice_id' ).val() + '" />' )
					.append( '<input type="hidden" name="selection" value="' + $( this ).attr( 'id' ) + '" />' )
					.appendTo( 'body' )
				
				$( '#form' ).submit()
			} // function
		) // click
		
		$( '#message' ).fadeTo( 5000, 1 ).slideUp()
		
		$( '#kill_session' ).click(
			function()
			{
				var form = $( '<form action="" method="post" id="killform"></form>' )
				form
					.append( '<input type="hidden" name="kill" value="true" />' )
					.appendTo( 'body' )
				
				$( '#killform' ).submit()
				
	
			} // function
		) // click
	} // function
) // ready