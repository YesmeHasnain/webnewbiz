<?php if (get_the_author_meta('description')) {?>	  
      <div class="author-info">
        <div class="author-avatar">
		<?php echo get_avatar( get_the_author_meta( 'user_email' ), apply_filters( 'gauthier_author_bio_avatar_size', 200 ) ); ?>
		</div>
        <!-- .author-avatar -->
        <div class="author-description"><?php echo esc_html (get_the_author_meta('Position'));?>
          <?php _e( '-', 'gauthier' ); ?>
					<?php printf( esc_html( _n('Published post: %d', 'Published posts: %d', count_user_posts( get_the_author_meta() ), 'gauthier' ) ),
					number_format_i18n( count_user_posts( get_the_author_meta( 'ID' ) ) )); ?>	
		  <h3><a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>" rel="author"><?php printf( __( ' %s', 'gauthier' ), get_the_author() ); ?></a>
		  </h3>
          <p><?php the_author_meta( 'description' ); ?></p>
          <div class="author-contact-wrapper">
            <?php if(get_the_author_meta('twitter')){ ?>
            <div class ="author-socmed-wrapper "><a rel="me" href="http://twitter.com/<?php the_author_meta('twitter'); ?>"><div class ="author-twitter"></div></a> <span class="tooltiptext"><?php _e( 'X', 'gauthier' ); ?></span></div>
            <?php }
			if(get_the_author_meta('facebook')){ ?>
            <div class ="author-socmed-wrapper "><a rel="me" href="http://facebook.com/<?php the_author_meta('facebook'); ?>">
              <div class ="author-facebook"></div></a><span class="tooltiptext"><?php _e( 'Facebook', 'gauthier' ); ?></span></div>
            <?php }
			if(get_the_author_meta('youtube')){ ?>
            <div class ="author-socmed-wrapper "><a rel="me" href="http://youtube.com/<?php the_author_meta('youtube'); ?>"><div class ="author-youtube"></div></a><span class="tooltiptext"><?php _e( 'Youtube', 'gauthier' ); ?></span></div>
            <?php }
			if(get_the_author_meta('vimeo')){ ?>
            <div class ="author-socmed-wrapper "><a rel="me" href="http://vimeo.com/<?php the_author_meta('vimeo'); ?>"><div class ="author-vimeo"></div></a> <span class="tooltiptext"><?php _e( 'Vimeo', 'gauthier' ); ?></span></div>
            <?php }
			if(get_the_author_meta('linkedin')){ ?>
            <div class ="author-socmed-wrapper "><a rel="me" href="http://linkedin.com/<?php the_author_meta('linkedin'); ?>" data-placement="top" data-toggle="tooltip" class="tip-top" data-original-title="Linkedin" >
              <div class ="author-linkedin"></div></a><span class="tooltiptext"><?php _e( 'Linkedin', 'gauthier' ); ?></span></div>
            <?php }
			if(get_the_author_meta('devianart')){ ?>
            <div class ="author-socmed-wrapper "><a rel="me" href="http://devianart.com/<?php the_author_meta('devianart'); ?>" data-placement="top" data-toggle="tooltip" class="tip-top" data-original-title="Devian-art" >
              <div class ="author-devianart"></div></a><span class="tooltiptext"><?php _e( 'Deviant', 'gauthier' ); ?></span></div>
            <?php }
			if(get_the_author_meta('dribble')){ ?>
            <div class ="author-socmed-wrapper "><a rel="me" href="http://dribble.com/<?php the_author_meta('dribble'); ?>" data-placement="top" data-toggle="tooltip" class="tip-top" data-original-title="Dribble" >
              <div class ="author-dribble"></div></a><span class="tooltiptext"><?php _e( 'Dribble', 'gauthier' ); ?></span></div>
            <?php }
			if(get_the_author_meta('flickr')){ ?>
			<div class ="author-socmed-wrapper "><a rel="me" href="http://flickr.com/<?php the_author_meta('flickr'); ?>" data-placement="top" data-toggle="tooltip" class="tip-top" data-original-title="Flickr" >
              <div class ="author-flickr"> </div></a><span class="tooltiptext"><?php _e( 'Flickr', 'gauthier' ); ?></span></div>
            <?php }
			if(get_the_author_meta('instagram')){ ?>
            <div class ="author-socmed-wrapper "><a rel="me" href="http://instagram.com/<?php the_author_meta('instagram'); ?>" data-placement="top" data-toggle="tooltip" class="tip-top" data-original-title="Instagram" >
              <div class ="author-instagram"></div></a><span class="tooltiptext"><?php _e( 'Instagram', 'gauthier' ); ?></span></div>
            <?php }
			if(get_the_author_meta('behance')){ ?>
            <div class ="author-socmed-wrapper "><a rel="me" href="http://behance.com/<?php the_author_meta('behance'); ?>" data-placement="top" data-toggle="tooltip" class="tip-top" data-original-title="Behance" >
              <div class ="author-behance"></div></a><span class="tooltiptext"><?php _e( 'Behance', 'gauthier' ); ?></span></div>
            <?php }
			if(get_the_author_meta('reddit')){ ?>
            <div class ="author-socmed-wrapper "><a rel="me" href="http://reddit.com/<?php the_author_meta('reddit'); ?>" data-placement="top" data-toggle="tooltip" class="tip-top" data-original-title="Reddit" >
              <div class ="author-reddit"></div></a><span class="tooltiptext"><?php _e( 'Reddit', 'gauthier' ); ?></span></div>
            <?php }
			if(get_the_author_meta('github')){ ?>
            <div class ="author-socmed-wrapper "><a rel="me" href="http://github.com/<?php the_author_meta('github'); ?>" data-placement="top" data-toggle="tooltip" class="tip-top" data-original-title="Github" >
              <div class ="author-github"></div></a><span class="tooltiptext"><?php _e( 'Github', 'gauthier' ); ?></span></div>
            <?php }
			if(get_the_author_meta('pinterest')){ ?>
            <div class ="author-socmed-wrapper "> <a rel="me" href="http://pinterest.com/<?php the_author_meta('pinterest'); ?>" data-placement="top" data-toggle="tooltip" class="tip-top" data-original-title="Pinterest" >
              <div class ="author-pinterest"> </div></a><span class="tooltiptext"><?php _e( 'Pinterest', 'gauthier' ); ?></span></div>
            <?php } ?>
          </div>
        </div>
        <!-- .author-description	-->
      </div>
<?php } ?>	