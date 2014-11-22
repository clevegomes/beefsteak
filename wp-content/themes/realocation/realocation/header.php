<?php do_action('aviators_pre_render'); ?>
<!DOCTYPE html>
<html <?php language_attributes() ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Aviators, http://aviators.com">

    <link rel="profile" href="http://gmpg.org/xfn/11">
    <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">

    <?php wp_head(); ?>

    <?php if ( is_singular() ) wp_enqueue_script( "comment-reply" ); ?>
    <title><?php echo wp_title('|', FALSE, 'right'); ?></title>
    <?php if($ga = realocation_ga_get_tracking()): ?>
    <script>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
            (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
            m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

        ga('create', '<?php print $ga['code']; ?>', '<?php print $ga['name']; ?>');
        ga('send', 'pageview');
    </script>
    <?php endif; ?>
</head>

<body <?php body_class(); ?> >
<?php if(get_theme_mod('general_enable_customizer', 1)): ?>
    <?php aviators_get_template('template', 'palette'); ?>
<?php endif; ?>

<div id="wrapper">
    <div id="header-wrapper">
        <div id="header">
            <div id="header-inner">
                <?php if ( get_theme_mod('general_topbar_is_enabled') ) : ?>
                    <?php require_once 'templates/header-bar.php'; ?>
                <?php endif; ?>

                <?php require_once 'templates/header-top.php'; ?>

                <?php require_once 'templates/header-navigation.php'; ?>
            </div><!-- /.header-inner -->
        </div><!-- /#header -->
    </div><!-- /#header-wrapper -->

    <div id="main-wrapper">
        <div id="main">
            <div id="main-inner">
                <?php dynamic_sidebar( 'top-fullwidth' ); ?>

                <?php if (is_singular(array('property'))): ?>
                    <?php
                    wp_enqueue_script('googlemaps3');
                    wp_enqueue_script('clusterer');
                    wp_enqueue_script('infobox');
                    ?>
                    <?php $mapPosition = get_post_meta(get_the_ID(), 'hf_property_map', TRUE); ?>
                    <?php if (isset($mapPosition['items'][0])): ?>
                        <?php if (!empty($mapPosition['items'][0]['latitude']) && !empty($mapPosition['items'][0]['longitude'])) : ?>
                            <div id="map-property">
                            </div><!-- /#map-property -->
                        <?php endif; ?>
                    <?php endif; ?>
                    <?php add_action('aviators_footer_map_detail', 'aviators_properties_map_detail'); ?>
                <?php endif; ?>

                <div class="container">
                    <?php dynamic_sidebar( 'top' ); ?>

                    <?php echo aviators_render_messages(); ?>

                    <div class="block-content block-content-small-padding">
                        <div class="block-content-inner">
                            <div class="row">