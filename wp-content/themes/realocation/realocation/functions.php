<?php

define ('AVIATORS_SIDEBARS_ALL', 1);
define ('AVIATORS_SIDEBARS_ANY', 0);

define('THEMENAME', 'realocation');

require_once get_template_directory() . '/launcher/launcher.php';
/*****************************************************************
 * Misc
 *****************************************************************/
require_once get_template_directory() . '/core/class-tgm-plugin-activation.php';
require_once get_template_directory() . '/core/aq_resizer.php';

/*****************************************************************
 * Settings
 *****************************************************************/
require_once get_template_directory() . '/core/settings/tgm.php';
require_once get_template_directory() . '/core/settings/menus.php';
require_once get_template_directory() . '/core/settings/sidebars.php';
require_once get_template_directory() . '/core/settings/scripts.php';
require_once get_template_directory() . '/core/settings/customizations.php';

/*****************************************************************
 * Utility
 *****************************************************************/
require_once get_template_directory() . '/utility/image.php';
require_once get_template_directory() . '/utility/templates.php';
require_once get_template_directory() . '/utility/comments.php';
require_once get_template_directory() . '/utility/pagination.php';
require_once get_template_directory() . '/utility/settings.php';


/**
 * Define steps for launcher
 * @param $steps
 * @return mixed
 */
function realocation_aviators_launcher_steps($steps) {

    $steps['content'] = array(
        'title' => __('Content Import', 'aviators'),
        'importer' => 'content',
        'file' => dirname(__FILE__) . '/exports/realocation_content.xml',
    );

    $steps['hydra'] = array(
        'title' => __('Hydra Import', 'aviators'),
        'importer' => 'hydra',
        'file' => dirname(__FILE__) . '/exports/hydra_export.xml',
    );

    $steps['widget'] = array(
        'title' => __('Widget Import', 'aviators'),
        'importer' => 'widget-settings',
        'file' => dirname(__FILE__) . '/exports/widget_data.json',
    );

    $steps['logic'] = array(
        'title' => __('Widget Logic Import', 'aviators'),
        'importer' => 'widget-logic',
        'file' => dirname(__FILE__) . '/exports/widget_logic.json',
    );

    $steps['theme'] = array(
        'title' => __('Theme Options', 'aviators'),
        'importer' => 'theme-options',
        'file' => dirname(__FILE__) . '/exports/theme_options.json',
    );

    return $steps;
}
add_filter('aviators_launcher_steps', 'realocation_aviators_launcher_steps');

if (!isset($content_width)) {
    $content_width = 1170;
}

function aviators_footer() {
    $instance = NULL;
    do_action('aviators_footer_map_widget', $instance);
    do_action('aviators_footer_map_detail');
}

function aviators_entry_meta() {
    if (is_sticky() && is_home() && !is_paged()) {
        echo '<span class="featured-post">' . __('Sticky', 'aviators') . '</span>';
    }

    if (!has_post_format('link') && 'post' == get_post_type()) {
        aviators_entry_date();
    }

    $tag_list = get_the_tag_list('', __(', ', 'aviators'));
    if ($tag_list) {
        echo '<span class="tags-links">' . $tag_list . '</span>';
    }

    if (in_array('category', get_object_taxonomies(get_post_type()))) {
        echo '<div class="entry-meta"><span class="cat-links">' . get_the_category_list(',') . '</span></div>';
    }

    if ('post' == get_post_type()) {
        $author_posts_url = esc_url(get_author_posts_url(get_the_author_meta('ID')));
        $author_title = esc_attr(sprintf(__('View all posts by %s', 'aviators'), get_the_author()));
        $author = get_the_author();
        print '<span class="author vcard">' . __('Posted by', 'aviators') . ' <a class="url fn n" href="' . $author_posts_url . '" title="' . $author_title . '" rel="author">' . $author . '</a></span> ' . __('on', 'aviators') . ' ' . aviators_entry_date();
    }
}

function aviators_link_pages() {
    wp_link_pages(array(
        'before' => '<div class="page-links"><span class="page-links-title">' . __('Pages:', 'aviators') . '</span>',
        'after' => '</div>',
        'link_before' => '<span>',
        'link_after' => '</span>'
    ));
}

function aviators_comments_popup_link() {
    comments_popup_link(
        '<span class="leave-reply">' . __('Leave a comment', 'aviators') . '</span>',
        __('One comment so far', 'aviators'),
        __('View all % comments', 'aviators')
    );
}

function aviators_the_content() {
    the_content(__('Continue reading', 'aviators'));
}

function aviators_morelink_class($link, $text) {
    return str_replace(
        'more-link', 'more-link btn arrow-right btn-primary', $link
    );
}

add_action('the_content_more_link', 'aviators_morelink_class', 10, 2);

function aviators_entry_date($echo = FALSE) {
    $format_prefix = (has_post_format('chat') || has_post_format('status')) ? __('%1$s on %2$s', '1: post format name. 2: date', 'aviators') : '%2$s';

    $date = sprintf('<span class="date"><a href="%1$s" title="%2$s" rel="bookmark"><time class="entry-date" datetime="%3$s">%4$s</time></a></span>',
        esc_url(get_permalink()),
        esc_attr(sprintf(__('Permalink to %s', 'aviators'), the_title_attribute('echo=0'))),
        esc_attr(get_the_date('c')),
        esc_html(sprintf($format_prefix, get_post_format_string(get_post_format()), get_the_date()))
    );

    if ($echo) {
        echo $date;
    }

    return $date;
}

/**
 * Settings page link
 * @param $post_type
 * @param $page_id
 */
function aviators_configure_page_link($post_type, $page_id) {
    // @todo access check
    if (!current_user_can('edit_pages')) {
        return;
    }
    $path = aviators_settings_get_settings_path($post_type, $page_id);

    $output = '<span class="edit-link"><a href=' . $path . '>';
    $output .= '<i class="fa fa-cog"></i>' . __("Configure", 'aviators');
    $output .= '</a></span>';

    print $output;
}

/**
 * Edit post link
 */
function aviators_edit_post_link($post_id = 0) {
    edit_post_link(__('<i class="fa fa-pencil"></i> Edit', 'aviators'), '<span class="edit-link">', '</span>', $post_id);
}

/**
 * Better excerpt read more link
 */
add_filter('excerpt_more', 'aviators_excerpt_more');
function aviators_excerpt_more($more) {
    return '<div class="read-more-wrapper"><a class="btn btn-primary" href="' . get_permalink(get_the_ID()) . '">' . __('Read More', 'aviators') . ' </a></div>';
}

/**
 * Additional theme setup functions
 */
add_action('after_setup_theme', 'aviators_theme_setup');
function aviators_theme_setup() {
    load_theme_textdomain('aviators', get_template_directory() . '/languages');
    add_editor_style();

    add_theme_support('automatic-feed-links');
    add_theme_support('post-thumbnails');
    // add_theme_support('custom-header');
    // add_theme_support('custom-background');

    add_filter('widget_text', 'do_shortcode');

    if (current_user_can('subscriber')) {
        remove_action('wp_footer', 'wp_admin_bar_render', 1000);
        add_filter('body_class', 'aviators_remove_admin_bar_for_subscriber');
    }
}

function aviators_remove_admin_bar_for_subscriber($classes) {
    $result = array();

    foreach ($classes as $class) {
        if ($class != 'admin-bar') {
            $result[] = $class;
        }
    }

    return $result;
}

/**
 * Helper function for content loop to know if there it is next post in loop
 */
function more_posts() {
    global $wp_query;
    return $wp_query->current_post + 1 < $wp_query->post_count;
}

/**
 * Nice formatted title tag value
 */
add_filter('wp_title', 'aviators_wp_title', 10, 2);
function aviators_wp_title($title, $sep) {
    global $paged, $page;

    if (is_feed()) {
        return $title;
    }


    // Add the site name.
    $title .= get_bloginfo('name');

    // Add the site description for the home/front page.
    $site_description = get_bloginfo('description', 'display');
    if ($site_description && (is_home() || is_front_page())) {
        $title = "$title $sep $site_description";
    }

    // Add a page number if necessary.
    if ($paged >= 2 || $page >= 2) {
        $title = "$title $sep " . sprintf(__('Page %s', 'nuczv'), max($paged, $page));
    }

    return strip_tags(html_entity_decode($title));
}

/**
 * Switch body's wide/boxed layout class
 */
add_filter('body_class', 'aviators_body_class');
function aviators_body_class($classes = '') {
    $classes[] = get_theme_mod('general_layout', 'layout-wid');
    $classes[] = get_theme_mod('footer_variant', 'footer-dark');
    $classes[] = get_theme_mod('header_variant', 'header-dark');
    $classes[] = get_theme_mod('background_pattern', 'cloth-alike');
    $classes[] = get_theme_mod('map_navigation_variant', 'map-navigation-dark');

    return $classes;
}

/**
 * Disable admin's bar top margin
 */
add_action('get_header', 'aviators_disable_admin_bar_top_margin');
function aviators_disable_admin_bar_top_margin() {
    remove_action('wp_head', '_admin_bar_bump_cb');
}

function aviators_add_message($message, $type = 'success') {
    $_SESSION['aviators']['messages'][] = array(
        'message' => $message,
        'type' => $type,
    );

}

function aviators_flush_messages() {
    unset($_SESSION['aviators']['messages']);
}

function aviators_render_messages() {
    $output = "";

    if (!defined('HYDRA_THEME_MODE')) {
        return;
    }

    $messages = hydra_get_messages();
    if ($messages) {
        foreach ($messages as $message) {
            $_SESSION['aviators']['messages'][] = array(
                'message' => $message['text'],
                'type' => $message['type'],
            );
        }
    }

    if (isset($_SESSION['aviators']['messages'])) {
        if (count($_SESSION['aviators']['messages'])) {
            foreach ($_SESSION['aviators']['messages'] as $message) {
                $output .= "<div class=\"alert alert-" . $message['type'] . "\">" . $message['message'] . "</div>";
            }
        }
    }

    aviators_flush_messages();

    return $output;
}


/**
 * Determine if all or at least one sidebar is/are active
 * Used to determine if render on not to render grouped section of sidebars - like footer
 * @param $sidebars
 * @param int $condition
 * @return bool
 */
function aviators_active_sidebars($sidebars, $condition = AVIATORS_SIDEBARS_ALL) {
    if (is_string($sidebars)) {
        $sidebars = array($sidebars);
    }

    // not valid data provided
    if (!is_array($sidebars) && !count($sidebars)) {
        return FALSE;
    }

    if ($condition == AVIATORS_SIDEBARS_ALL) {
        foreach ($sidebars as $sidebar) {
            if (!is_active_sidebar($sidebar)) {
                return FALSE;
            }
        }
        return TRUE;
    }

    if ($condition == AVIATORS_SIDEBARS_ANY) {
        foreach ($sidebars as $sidebar) {
            if (is_active_sidebar($sidebar)) {
                return TRUE;
            }
        }
        return FALSE;
    }
}

/**
 * Hydra callback to route rendering of various post types nad post display types
 * @param $meta
 * @param $delta
 * @param $settings
 * @param $item
 * @return string
 */
function aviators_post_reference_item_render($view, $meta, $delta, $settings, $item) {
    $dbModel = new HydraFieldViewModel();
    $field = $view->loadField();

    $args = array(
        'post_type' => $field->attributes['post_type'],
        'post__in' => $item['value'],
        'posts_per_page' => '9',
    );

    query_posts($args);
    ob_start();
    echo "<div class=row>";

    if ( have_posts() ) {
        while (have_posts()) {
            echo "<div class=col-sm-4>";
            the_post();
            aviators_get_content_template($field->attributes['post_type'], $settings['display_type']);
            echo "</div>";
        }
    }
    echo "</div>";
    echo aviators_pagination();
    wp_reset_query();

    $output = ob_get_clean();



    return $output;
}

add_filter('hydra_post_reference_item_render', 'aviators_post_reference_item_render', 10, 5);


/**
 * Gets array of allowed pages for settings
 *
 * Not all page templates can have settings attached
 *
 * @param $supported_pages
 * @return array
 */
function aviators_property_theme_supported_pages($supported_pages) {
    $pages = get_posts(array(
        'post_type' => 'page',
        'meta_key' => '_wp_page_template',
        'meta_value' => 'page-properties.php',
        'numberposts' => -1
    ));

    if (!count($pages)) {
        return array();
    }

    foreach ($pages as $page) {
        $supported_pages[$page->ID] = $page->post_title;
    }

    return $supported_pages;
}

add_filter('aviators_property_supported_page_type', 'aviators_property_theme_supported_pages');

/**
 * Gets array of allowed pages for settings
 *
 * Not all page templates can have settings attached
 *
 * @param $supported_pages
 * @return array
 */
function aviators_package_theme_supported_pages($supported_pages) {
    $pages = get_posts(array(
        'post_type' => 'page',
        'meta_key' => '_wp_page_template',
        'meta_value' => 'page-packages.php',
    ));

    if (!count($pages)) {
        return array();
    }


    foreach ($pages as $page) {
        $supported_pages[$page->ID] = $page->post_title;
    }

    return $supported_pages;
}

add_filter('aviators_package_supported_page_type', 'aviators_package_theme_supported_pages');


/**
 *
 */
function aviators_allow_upload() {
    $contributor = get_role('subscriber');
    $contributor->add_cap('upload_files');
}
add_action('init','aviators_allow_upload');


add_action('pre_get_posts','aviators_restrict_media_library');
function aviators_restrict_media_library( $wp_query_obj )
{
    global $current_user, $pagenow;

    if( !is_a( $current_user, 'WP_User') ) {
        return;
    }

    if( 'upload.php' != $pagenow && 'media-upload.php' != $pagenow && 'admin-ajax.php' != $pagenow) {
        return;
    }

    if( !current_user_can('delete_pages') ) {
        if($wp_query_obj->query['post_type'] == 'attachment') {
            $wp_query_obj->set('author', $current_user->id );
        }
    }
}

function realocation_php_version_check() {

    if(version_compare(phpversion(), '5.3.1', '<')) {
        $output = '';
        $output .= '<div class="error">';
        $output .= '<p>';
        $output .= __( 'You require at least PHP 5.3 or higher in order to run Realocation. <strong>Upgrade your PHP before continuing with installation.</strong>', 'aviators' );
        $output .= '</p>';
        $output .= '</div>';

        echo $output;
    }
}
add_action( 'admin_notices', 'realocation_php_version_check' );