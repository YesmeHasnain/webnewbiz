<?php
if ( has_nav_menu( 'menu-2' ) ) {
    // User has assigned menu to this location;
    // output it
    ?>
<nav class="nav navbar">
    <div class="navbar-menu">
        <?php
			wp_nav_menu( array(
				'theme_location' => 'menu-2',
				'menu_id'        => 'single-menu',
			) );
		?>
    </div>
    <div class='nav-link-container mobile-menu-link'> 
        <a href='#' class="nav-menu-link">              
        <svg width="20" height="16" viewBox="0 0 20 16" fill="none" xmlns="http://www.w3.org/2000/svg">
        <rect y="14" width="10" height="2" fill="#1C2539"/>
        <rect y="7" width="20" height="2" fill="#1C2539"/>
        <rect width="10" height="2" fill="#1C2539"/>
        </svg>          
        </a> 
    </div>
</nav>
<?php } 

?>
<nav class="nav-container mobile-menu-container">
    <ul class="sidenav">
        <li class='nav-link-container'> 
            <a href='#' class="nav-menu-link">              
            <svg width="20" height="16" viewBox="0 0 20 16" fill="none" xmlns="http://www.w3.org/2000/svg">
            <rect y="14" width="10" height="2" fill="#1C2539"/>
            <rect y="7" width="20" height="2" fill="#1C2539"/>
            <rect width="10" height="2" fill="#1C2539"/>
            </svg>
            </a> 
        </li>
        <li>
          <?php
                wp_nav_menu( array(
                    'theme_location' => 'menu-2',
                    'menu_id'        => 'mobile-single-menu',
                ) );
            ?>
        </li>
    </ul>
</nav>