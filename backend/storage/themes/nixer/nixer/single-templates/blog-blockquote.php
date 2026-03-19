<?php 
$nixer_redux_demo = get_option('redux_demo');
?>

<?php if (!empty($nixer_redux_demo['quote-switch'])): ?>
	<div class="postbox__item mb-80">
		<div class="postbox__blockquote">
			<blockquote>
				<span class="postbox__blockquote-icon">
					<svg xmlns="http://www.w3.org/2000/svg" width="55" height="39" viewBox="0 0 55 39" fill="none">
						<path d="M0 23.4H11.7855L3.92842 39H15.7139L23.5709 23.4V0H0V23.4Z" fill="white"/>
						<path d="M31.4297 0V23.4H43.2151L35.3581 39H47.1436L55.0006 23.4V0H31.4297Z" fill="white"/>
					</svg>
				</span>
				<?php if (!empty($nixer_redux_demo['quote-text'])): ?>
					<p><?php echo wp_specialchars_decode(esc_attr($nixer_redux_demo['quote-text']));?></p>
				<?php endif ?>
				<span class="postbox__blockquote-icon-1">
					<svg xmlns="http://www.w3.org/2000/svg" width="55" height="39" viewBox="0 0 55 39" fill="none">
						<path d="M0 23.4H11.7855L3.92842 39H15.7139L23.5709 23.4V0H0V23.4Z" fill="white" fill-opacity="0.1"/>
						<path d="M31.4297 0V23.4H43.2151L35.3581 39H47.1436L55.0006 23.4V0H31.4297Z" fill="white" fill-opacity="0.1"/>
					</svg>
				</span>
			</blockquote>
		</div>
	</div>
<?php endif ?>