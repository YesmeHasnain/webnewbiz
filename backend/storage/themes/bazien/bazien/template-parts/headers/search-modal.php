<div class="full reveal search-reveal" id="headerSearchModal" data-reveal>
  <div class="site-search full-screen">
    <div class="header-search">

        <?php if ( NOVA_WOOCOMMERCE_IS_ACTIVE ) : ?>
            <?php do_action('nova_ajax_search_form'); ?>
        <?php else: ?>
            <?php get_search_form(); ?>
        <?php endif; ?>

        <button class="close-button" aria-label="Close menu" type="button" data-close>
          <svg class="bazien-close-canvas">
            <use xlink:href="#bazien-close-canvas"></use>
          </svg>
        </button>

    </div>
  </div>
</div>
