<?php

/**
 * @author: VLThemes
 * @version: 1.0.5
 */

$post_audio_link = ziomm_get_field( 'post_audio_link' );

if ( ! empty( $post_audio_link ) && is_single() ) :

	$oembed = wp_oembed_get( $post_audio_link );

	if ( ! empty( $oembed ) ) : ?>

		<div class="vlt-post-media__audio">

			<?php echo wp_oembed_get( $post_audio_link ); ?>

		</div>

	<?php else : ?>

		<div class="vlt-post-media__audio">

			<?php

				// Settings for audio player
				$settings = apply_filters( 'vlthemes/audio-post-format-settings', [] );

				// Init audio player
				echo wp_audio_shortcode( array_merge( array( 'src' => esc_url( $post_audio_link ) ), $settings ) );

			?>

		</div>

	<?php endif; ?>

<?php endif; ?>