( function () {
	var photos = document.querySelector( '[data-home-photo]' );
	if ( photos ) {
		var frames = photos.querySelectorAll( 'img' );
		var index = 0;
		if ( frames.length > 1 ) {
			window.setInterval( function () {
				frames[ index ].classList.remove( 'is-on' );
				index = ( index + 1 ) % frames.length;
				frames[ index ].classList.add( 'is-on' );
			}, 8000 );
		}
	}

	var stage = document.querySelector( '[data-preview-stage]' );
	if ( stage ) {
		document.querySelectorAll( '[data-preview]' ).forEach( function ( link ) {
			link.addEventListener( 'mouseenter', function () {
				stage.hidden = false;
				stage.src = link.getAttribute( 'data-preview' );
			} );
		} );
		var list = document.querySelector( '.side-index' );
		if ( list ) {
			list.addEventListener( 'mouseleave', function () {
				stage.hidden = true;
			} );
		}
	}

	var viewer = document.querySelector( '[data-viewer]' );
	if ( viewer ) {
		var links = viewer.querySelectorAll( '[data-frame-link]' );
		var close = viewer.querySelector( '[data-frame-close]' );
		document.addEventListener( 'keydown', function ( event ) {
			if ( event.key === 'ArrowRight' && links[ 1 ] ) {
				event.preventDefault();
				window.location.href = links[ 1 ].href;
			} else if ( event.key === 'ArrowLeft' && links[ 0 ] ) {
				event.preventDefault();
				window.location.href = links[ 0 ].href;
			} else if ( event.key === 'Escape' && close ) {
				window.location.href = close.href;
			}
		} );
	}

	document.querySelectorAll( '[data-player]' ).forEach( function ( player ) {
		var still = player.querySelector( '[data-still]' );
		var button = player.querySelector( '[data-play]' );
		var bar = player.querySelector( '[data-playhead]' );
		if ( ! still || ! button ) {
			return;
		}
		function toggle() {
			var on = still.classList.toggle( 'is-playing' );
			button.hidden = on;
			if ( bar ) {
				bar.hidden = ! on;
			}
		}
		button.addEventListener( 'click', toggle );
		document.addEventListener( 'keydown', function ( event ) {
			if ( event.key === ' ' && player.contains( document.activeElement ) ) {
				event.preventDefault();
				toggle();
			}
		} );
	} );
}() );
