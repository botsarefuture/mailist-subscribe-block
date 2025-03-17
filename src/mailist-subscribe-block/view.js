document.addEventListener( 'DOMContentLoaded', function () {
	const form = document.querySelector( '.mailist-subscribe-form' );

	if ( form ) {
		form.addEventListener( 'submit', function ( event ) {
			event.preventDefault();

			const email = form.querySelector( '#email' ).value;
			const listId = form.dataset.listId; // Get listId from the block's data attribute

			if ( ! email ) {
				alert( 'Please enter a valid email address.' );
				return;
			}

			fetch( 'https://mailist.luova.club/signup', {
				method: 'POST',
				headers: {
					'Content-Type': 'application/json',
				},
				body: JSON.stringify( { email: email, list_id: listId } ),
			} )
				.then( ( response ) => response.json() )
				.then( ( data ) => {
					if ( data.success ) {
						alert( 'Subscription successful!' );
					} else {
						alert( `Subscription failed: ${ data.message }` );
					}
				} )
				.catch( ( error ) => {
					console.error( 'Error:', error );
					alert( 'An error occurred. Please try again.' );
				} );
		} );
	}

	console.log( 'meow' );
} );
