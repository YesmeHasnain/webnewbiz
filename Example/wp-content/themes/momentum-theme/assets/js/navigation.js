( function() {
    const siteNavigation = document.getElementById( 'site-navigation' );
    if ( ! siteNavigation ) return;

    const button = siteNavigation.getElementsByTagName( 'button' )[ 0 ];
    if ( 'undefined' === typeof button ) return;

    const menu = siteNavigation.getElementsByTagName( 'ul' )[ 0 ];
    if ( 'undefined' === typeof menu ) { button.style.display = 'none'; return; }

    if ( ! menu.classList.contains( 'nav-menu' ) ) menu.classList.add( 'nav-menu' );

    button.addEventListener( 'click', function() {
        siteNavigation.classList.toggle( 'toggled' );
        const expanded = siteNavigation.classList.contains( 'toggled' );
        button.setAttribute( 'aria-expanded', expanded );
        menu.setAttribute( 'aria-expanded', expanded );
    } );

    document.addEventListener( 'keydown', function( event ) {
        if ( event.key === 'Escape' && siteNavigation.classList.contains( 'toggled' ) ) {
            siteNavigation.classList.remove( 'toggled' );
            button.setAttribute( 'aria-expanded', 'false' );
            menu.setAttribute( 'aria-expanded', 'false' );
            button.focus();
        }
    } );
} )();
