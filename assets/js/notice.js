( function() {
	wp.domReady( function() {
		var needsCheck = false,
			noticesArray = [];

		// Listen to changes in the editor.
		wp.data.subscribe( function() {
			if ( wp.data.select( 'core/editor' ).isSavingPost() ) {
				needsCheck = true;
			} else if ( needsCheck ) {
				needsCheck = false;
				var postId = wp.data.select( 'core/editor' ).getCurrentPostId();

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
							xhr.response.forEach(
								function( currentValue ) {
									if ( noticesString !== '' ) {
										noticesString += '<br>';
									}
									noticesString += currentValue.error_text;
								}
							);

							// Display notice and add its Promise to the noticesArray.
							noticesArray.push(
								wp.data.dispatch( 'core/notices' ).createErrorNotice(
									noticesString,
									{
										__unstableHTML: true,
									}
								)
							);
						}
					}
				};
				xhr.send( '_nonce=' + spclScriptData.nonce + '&action=spcl_link_check&check_post=' + postId );
			}
		} );
	} );
}() );
