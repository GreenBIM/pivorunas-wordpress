( function ( $ ) {
	function row( attachment ) {
		var thumb = attachment.sizes && attachment.sizes.thumbnail ? attachment.sizes.thumbnail.url : attachment.url;
		return $(
			'<li data-id="' + attachment.id + '">' +
				'<img alt="" src="' + thumb + '" width="72" height="72" />' +
				'<span></span>' +
				'<button type="button" class="button-link" data-pivorunas-remove">Remove</button>' +
				'<input type="hidden" name="pivorunas_frames[]" />' +
			'</li>'
		).find( 'span' ).text( attachment.title || attachment.filename || '' ).end()
			.find( 'input' ).val( attachment.id ).end();
	}

	$( document ).on( 'click', '[data-pivorunas-add-frames]', function ( event ) {
		event.preventDefault();
		var list = $( '[data-pivorunas-frames]' );
		var frame = wp.media( { title: 'Photographs', multiple: true, library: { type: 'image' } } );
		frame.on( 'select', function () {
			frame.state().get( 'selection' ).each( function ( model ) {
				list.append( row( model.toJSON() ) );
			} );
		} );
		frame.open();
	} );

	$( document ).on( 'click', '[data-pivorunas-remove]', function ( event ) {
		event.preventDefault();
		$( this ).closest( 'li' ).remove();
	} );

	$( document ).on( 'click', '[data-pivorunas-add-video]', function ( event ) {
		event.preventDefault();
		var box = $( '[data-pivorunas-video]' );
		var frame = wp.media( { title: 'Video', multiple: false, library: { type: 'video' } } );
		frame.on( 'select', function () {
			var file = frame.state().get( 'selection' ).first().toJSON();
			box.find( 'input' ).val( file.id );
			box.find( '[data-pivorunas-video-name]' ).text( file.filename || file.title || '' );
		} );
		frame.open();
	} );

	$( document ).on( 'click', '[data-pivorunas-clear-video]', function ( event ) {
		event.preventDefault();
		var box = $( '[data-pivorunas-video]' );
		box.find( 'input' ).val( '' );
		box.find( '[data-pivorunas-video-name]' ).text( '' );
	} );

	$( document ).on( 'click', '[data-pivorunas-home-choose]', function ( event ) {
		event.preventDefault();
		var field = $( this ).closest( '.pivorunas-home-field' );
		var frame = wp.media( { title: 'Photograph', multiple: false, library: { type: 'image' } } );
		frame.on( 'select', function () {
			var file = frame.state().get( 'selection' ).first().toJSON();
			var thumb = file.sizes && file.sizes.medium ? file.sizes.medium.url : file.url;
			field.find( 'input' ).val( file.id );
			field.find( '[data-pivorunas-home-preview]' ).html( '<img alt="" src="' + thumb + '" />' );
		} );
		frame.open();
	} );

	$( document ).on( 'click', '[data-pivorunas-home-clear]', function ( event ) {
		event.preventDefault();
		var field = $( this ).closest( '.pivorunas-home-field' );
		field.find( 'input' ).val( '' );
		field.find( '[data-pivorunas-home-preview]' ).empty();
	} );
}( jQuery ) );
