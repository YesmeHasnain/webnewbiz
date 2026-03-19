
<!-- Portfolio Detail Start -->
    <div class="reactheme-porfolio-details"> 
        <?php while ( have_posts() ) : the_post(); ?>          
       
        <div class="project-desc">       
           <?php  the_content(); ?>
        </div>               
        

      <?php endwhile; wp_reset_query();?>
	  
	  <?php 
		$next_post = get_next_post();
		$previous_post = get_previous_post();
		if( !empty($next_post) || !empty($previous_post)):?>
			<div class="ps-navigation">
			
					<?php			 
						$url_next = is_object( $next_post ) ? get_permalink( $next_post->ID ) : ''; 
						$title    = is_object( $next_post ) ? get_the_title( $next_post->ID ) : ''; 
					
						
						?>
					<div class="rts-left-write-blog-wrapper-main">
						<?php if($next_post):?>	
					
							<div class="left-icon-area single">
								<div class="icon-1">
								<a href="<?php echo esc_url( $url_next ) ?>">
									<i class="rt-arrow-left"></i>
								</a>
								</div>
								<div class="writing-content">
								<a href="<?php echo esc_url( $url_next ) ?>"><span><?php echo esc_html__('Previous', 'startli'); ?></span>
									<h6 class="title">
									<?php echo esc_attr( $title ); ?>
									</h6></a>
								</div>
							</div>
						
						<?php endif; ?>

						<?php $url_previous = is_object( $previous_post ) ? get_permalink( $previous_post->ID ) : '';
						$title = is_object( $previous_post ) ? get_the_title( $previous_post->ID ) : ''; ?>
                        <?php if($previous_post):?>	
						<div class="right-icon-area single">
                            <div class="writing-content">
							<a href="<?php echo esc_url( $url_previous ) ?>"><span><?php echo esc_html__('Next', 'startli'); ?></span>
                                <h6 class="title">
								<?php echo esc_attr( $title ); ?>
                                </h6></a>
                            </div>
                            <div class="icon-1">
							<a href="<?php echo esc_url( $url_previous ) ?>"> <i class="rt-arrow-right"></i></a>
                            </div>
                        </div>
						<?php endif; ?>
                    </div>
				</div>
		<?php endif; ?>
			
      </div>

<!-- Portfolio Detail End -->