<div class="header-bar">
    <div class="container">
        <?php dynamic_sidebar( 'topbar-left'); ?>

        <?php if ( function_exists( 'icl_sitepress_activate' ) ) : ?>
            <?php echo do_action( 'icl_language_selector' ); ?>
        <?php endif; ?>

        <?php if (is_user_logged_in()) : ?>
            <?php $menu = wp_nav_menu( array(
                'container_class' => 'header-bar-nav',
                'theme_location' => 'authenticated',
                'menu_class' => 'header-bar-nav nav',
                'fallback_cb' => false,
                'echo' => false,
            ) ); ?>

            <?php if ( substr_count( $menu, 'class="menu-item' ) > 0 ) : ?>
                <?php echo $menu; ?>
            <?php endif; ?>
        <?php else : ?>
            <?php $menu = wp_nav_menu( array(
                'container_class' => 'header-bar-nav',
                'theme_location' => 'anonymous',
                'menu_class' => 'nav',
                'fallback_cb' => false,
                'echo' => false,
            ) ); ?>

            <?php if ( substr_count( $menu, 'class="menu-item' ) > 0 ) : ?>
                <?php echo $menu; ?>
            <?php endif ?>
        <?php endif; ?>
    </div><!-- /.container -->

</div><!-- /.header-bar -->