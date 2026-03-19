<?php 

global $startli_option;
if(!empty($startli_option['facebook']) || !empty($startli_option['twitter']) || !empty($startli_option['rss']) || !empty($startli_option['pinterest']) || !empty($startli_option['google']) || !empty($startli_option['instagram']) || !empty($startli_option['vimeo']) || !empty($startli_option['tumblr']) ||  !empty($startli_option['youtube'])){
?>

    <ul class="offcanvas_social">  
        <?php
        if(!empty($startli_option['facebook'])) { ?>
        <li> 
        <a href="<?php echo esc_url($startli_option['facebook'])?>" target="_blank"><span><i class="fa fa-facebook"></i></span></a> 
        </li>
        <?php } ?>
        <?php if(!empty($startli_option['twitter'])) { ?>
        <li> 
        <a href="<?php echo esc_url($startli_option['twitter']);?> " target="_blank"><span><i class="fa fa-twitter"></i></span></a> 
        </li>
        <?php } ?>
        <?php if(!empty($startli_option['rss'])) { ?>
        <li> 
        <a href="<?php  echo esc_url($startli_option['rss']);?> " target="_blank"><span><i class="fa fa-rss"></i></span></a> 
        </li>
        <?php } ?>
        <?php if (!empty($startli_option['pinterest'])) { ?>
        <li> 
        <a href="<?php  echo esc_url($startli_option['pinterest']);?> " target="_blank"><span><i class="fa fa-pinterest-p"></i></span></a> 
        </li>
        <?php } ?>
        <?php if (!empty($startli_option['linkedin'])) { ?>
        <li> 
        <a href="<?php  echo esc_url($startli_option['linkedin']);?> " target="_blank"><span><i class="fa fa-linkedin"></i></span></a> 
        </li>
        <?php } ?>
        <?php if (!empty($startli_option['google'])) { ?>
        <li> 
        <a href="<?php  echo esc_url($startli_option['google']);?> " target="_blank"><span><i class="fa fa-google-plus-square"></i></span></a> 
        </li>
        <?php } ?>
        <?php if (!empty($startli_option['instagram'])) { ?>
        <li> 
        <a href="<?php  echo esc_url($startli_option['instagram']);?> " target="_blank"><span><i class="fa fa-instagram"></i></span></a> 
        </li>
        <?php } ?>
        <?php if(!empty($startli_option['vimeo'])) { ?>
        <li> 
        <a href="<?php  echo esc_url($startli_option['vimeo'])?> " target="_blank"><span><i class="fa fa-vimeo"></i></span></a> 
        </li>
        <?php } ?>
        <?php if (!empty($startli_option['tumblr'])) { ?>
        <li> 
        <a href="<?php  echo esc_url($startli_option['tumblr'])?> " target="_blank"><span><i class="fa fa-tumblr"></i></span></a> 
        </li>
        <?php } ?>
        <?php if (!empty($startli_option['youtube'])) { ?>
        <li> 
        <a href="<?php  echo esc_url($startli_option['youtube'])?> " target="_blank"><span><i class="fa fa-youtube"></i></span></a> 
        </li>
        <?php } ?>     
    </ul>
<?php }

