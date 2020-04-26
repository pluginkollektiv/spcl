( function() {
	wp.domReady( function() {
		var isChecking = false,
			noticesArray = [],
			postId = wp.data.select( 'core/editor' ).getCurrentPostId();

		// Listen to changes in the editor.
		var checkLinks = wp.data.subscribe( function() {
			var isSaving = wp.data.select( 'core/editor' ).isSavingPost();

			if ( isSaving && ! isChecking ) {
				isChecking = true;

				// Remove old notices if existent.
				if ( noticesArray.length !== 0 ) {
					for ( var i = 0; i < noticesArray.length; i++ ) {
						var noticePromise = noticesArray[i];
						noticePromise.then( function( value ) {
							wp.data.dispatch( 'core/notices' ).removeNotice( value.notice.id );
						} );
					}

					noticesArray = [];
				}

				// Fire the AJAX request.
				var xhr = new XMLHttpRequest();

				xhr.open( 'POST', ajaxurl, true );
				xhr.setRequestHeader( 'Content-Type', 'application/x-www-form-urlencoded' );
				xhr.responseType = 'json';
				xhr.onreadystatechange = function() {
					if ( this.readyState === XMLHttpRequest.DONE && this.status === 200 ) {
						if ( xhr.response.length > 0 ) {
							// Build string with notices.
							var noticesString = '';
							xhr.response.forEach( function( currentValue ) {
								if ( noticesString !== '' ) {
									noticesString += '<br>';
								}
								noticesString += currentValue.error_text;
							} );

							// Display notice and add its Promise to the noticesArray.
							noticesArray.push( wp.data.dispatch( 'core/notices' ).createErrorNotice(
								noticesString,
								{
									__unstableHTML: true,
								}
							) )

							isChecking = false;
						}
					}
				}
				xhr.send( '_nonce=' + postId + '&action=spcl_link_check&check_post=' + postId );
			}
		} );
	} );
} )();
