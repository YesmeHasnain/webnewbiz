<?php 

$format = get_post_format();
$theme_video_meta = get_post_meta(get_the_ID(),'theme_postvideo_options',true);
$theme_post_meta_video = isset($theme_video_meta['textm']) && !empty($theme_video_meta['textm']) ? $theme_video_meta['textm'] : '';

?>

<?php 
	$video_url = knor_video_embed($theme_post_meta_video);
?>

<div class="embed-responsive embed-responsive-16by9">
	<iframe class="embed-responsive-item" src="<?php echo esc_url($video_url); ?>" allowfullscreen>
	</iframe>
</div>
