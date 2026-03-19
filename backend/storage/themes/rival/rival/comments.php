<?php
/**
 * The template for displaying Comments.
 *
 * The area of the page that contains comments and the comment form.
 * If the current post is protected by a password and the visitor has not yet
 * entered the password we will return early without loading the comments.
 */
if ( post_password_required() )
    return;
?>

<?php if ( have_comments() ) : ?>
<div class="comments">
    <h2 class="count"><?php comments_number( esc_html__(' 0 Comments', 'rival'), esc_html__(' 1 Comment', 'rival'), esc_html__('% Comments', 'rival') ); ?></h2>
    <div class="entriesContainer">
        <ul class="comments clearfix">
            <?php wp_list_comments('callback=rival_theme_comment'); ?>
        </ul>
    </div>
</div>
<?php endif; ?>
<?php
if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) :
?>
<div class="pagination_area">
     <nav>
          <ul class="pagination">
               <li> <?php paginate_comments_links( 
                    array(
                    'prev_text' => wp_specialchars_decode('<i class="fa fa-angle-left"></i>',ENT_QUOTES),
                    'next_text' => wp_specialchars_decode('<i class="fa fa-angle-right"></i>',ENT_QUOTES),
                    ));  ?>
                </li>
          </ul>
     </nav>
</div>                                       
<?php endif; ?>   
<?php
    if ( is_singular() ) wp_enqueue_script( "comment-reply" );
$aria_req = ( $req ? " aria-required='true'" : '' );
$comment_args = array(
        'id_form' => '',        
        'class_form' => 'replyForm',                         
        'title_reply'=> wp_specialchars_decode(esc_html__( 'Leave A Comment', 'rival' ),ENT_QUOTES),
        'title_reply_before' =>'<h2 class="count">',
        'title_reply_after' => '</h2>',
        'fields' => apply_filters( 'comment_form_default_fields', array(
            'author' => '
                        <div class="inputColumns clearfix">
                            <div class="column1">
                                <input name="author" id="name" type="text" placeholder="'.esc_attr__('Name *', 'rival').'" required="'.esc_attr__('required', 'rival').'">
                            </div>',
            'email'  => '   <div class="column2">
                                <input name="email" id="email" type="email" placeholder="'.esc_attr__('Email *', 'rival').'" required="'.esc_attr__('required', 'rival').'">
                            </div>
                        </div>',
            'website'=> '   <input type="text" placeholder="'.esc_attr__('WebSite', 'rival').'" id="website" name="website" required="'.esc_attr__('required', 'rival').'">',
            ) ),   
            'comment_field' => '<textarea name="comment"  placeholder="'.esc_attr__('Write A Comment *', 'rival').'" id="message" cols="45" rows="10"></textarea>', 
            'label_submit' => esc_html__( 'Post A Comment', 'rival' ),
            'submit_button' =>'<button id="submit" type="submit">'.esc_attr__('%4$s', 'rival').'</button>',
            'submit_field' => ''.esc_attr__('%1$s', 'rival').' '.esc_attr__('%2$s', 'rival').'',
            'comment_notes_before' => '',
            'comment_notes_after' => '',             
)
?>
<?php if ( comments_open() ) : ?>
    <?php comment_form($comment_args); ?>
<?php endif; ?>