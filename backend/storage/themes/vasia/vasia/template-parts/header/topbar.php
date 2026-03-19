
<div class="topbar-header">
	<div class="container">
		<div class="row">
			<div class="topbar-left-position">
				<div id="_desktop_html1_">
					<?php echo vasia_custom_html1(); ?>
				</div>
			</div>
			<div class="topbar-center-position">
				<div id="_desktop_header_account_">
					<?php if(is_woocommerce_activated()) : ?>
						<?php vasia_header_account(); ?>
					<?php endif ?>
				</div>
				<div id="_desktop_topbar_menu_">
					<?php echo vasia_topbar_menu(); ?>
				</div>
			</div>
			<div class="topbar-right-position">
			</div>
		</div>
	</div>
</div>