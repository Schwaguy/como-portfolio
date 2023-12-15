(function($) {
	
	var comofields = array('comoportfolio-website','comoportfolio-featured','comoportfolio-icon','comoportfolio-icon-hover');

   	// we create a copy of the WP inline edit post function
   	var $wp_inline_edit = inlineEditPost.edit;
   	// and then we overwrite the function with our own code
   	inlineEditPost.edit = function( id ) {

    	// "call" the original WP edit function
      	// we don't want to leave WordPress hanging
      	$wp_inline_edit.apply( this, arguments );

      	// now we take care of our business

      	// get the post ID
      	var $post_id = 0;
      	if ( typeof( id ) == 'object' )
         	$post_id = parseInt( this.getId( id ) );

      	if ( $post_id > 0 ) {

         	// define the edit row
        	var $edit_row = $( '#edit-' + $post_id );
	 	
			// Populate Fields
			var $comoteam_title = $( '#comoteam-title-' + $post_id ).text();
			$edit_row.find( 'input[name="comoteam-title"]' ).val( $comoteam_title );
		  
			for each (comofields)
		  
		  
     	}
  	};
   
   	$( '#bulk_edit' ).live( 'click', function() {
	   	var $bulk_row = $( '#bulk-edit' );
	   	var $post_ids = new Array();
	   	$bulk_row.find( '#bulk-titles' ).children().each( function() {
	      	$post_ids.push( $( this ).attr( 'id' ).replace( /^(ttle)/i, '' ) );
	   	});
	   	var $comoteam_title = $bulk_row.find( 'input[name="comoteam-title"]' ).val();
	
	   	// save the data
	   	$.ajax({
	      	url: ajaxurl, // this is a variable that WordPress has already defined for us
	      	type: 'POST',
	      	async: false,
	      	cache: false,
	      	data: {
	        	action: 'team_save_bulk_edit', // this is the name of our WP AJAX function that we'll set up next
	        	post_ids: $post_ids, // and these are the 2 parameters we're passing to our function
		 		comoteam_title: $comoteam_title,
	      	}
	   	});
	});
})(jQuery);