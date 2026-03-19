<?php
if(is_active_sidebar('footer-widget')) :
    ?>
	<div class="footer-widget-area">
		<div class="container">
			<div class="row">
				<?php dynamic_sidebar( "footer-widget" ) ?>
			</div>
		</div>
	</div>
<?php endif;?>