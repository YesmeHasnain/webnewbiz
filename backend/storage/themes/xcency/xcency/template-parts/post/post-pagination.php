<div class="row post-pagination">
	<div class="col-lg-12 xcency-list-style">
		<?php
		the_posts_pagination(array(
			'next_text' => '<i class="fa-solid fa-angles-right"></i>',
			'prev_text' => '<i class="fa-solid fa-angles-left"></i>',
			'screen_reader_text' => '',
			'type'                => 'list'
		));
		?>
	</div>
</div>