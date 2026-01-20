<?php
/**
 * Soli Admin Theme functions and definitions.
 *
 * @package Soli_Admin_Theme
 * @since 1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Theme setup.
 *
 * @since 1.0.0
 */
function soli_admin_theme_setup(): void {
    load_theme_textdomain( 'soli-admin-theme', get_template_directory() . '/languages' );

    add_theme_support( 'title-tag' );
    add_theme_support( 'html5', array( 'style', 'script' ) );
}
add_action( 'after_setup_theme', 'soli_admin_theme_setup' );

/**
 * Enqueue theme styles.
 *
 * @since 1.0.0
 */
function soli_admin_theme_enqueue_styles(): void {
    wp_enqueue_style(
        'soli-admin-theme-style',
        get_stylesheet_uri(),
        array(),
        wp_get_theme()->get( 'Version' )
    );
}
add_action( 'wp_enqueue_scripts', 'soli_admin_theme_enqueue_styles' );

/**
 * Redirect non-authenticated users to the login page.
 *
 * @since 1.0.0
 */
function soli_admin_theme_require_login(): void {
    if ( ! is_user_logged_in() ) {
        wp_safe_redirect( wp_login_url( home_url() ) );
        exit;
    }
}
add_action( 'template_redirect', 'soli_admin_theme_require_login' );

/**
 * Load text domain on login page.
 *
 * @since 1.0.0
 */
function soli_admin_theme_login_init(): void {
    load_textdomain(
        'soli-admin-theme',
        get_template_directory() . '/languages/soli-admin-theme-' . determine_locale() . '.mo'
    );
}
add_action( 'login_init', 'soli_admin_theme_login_init' );

/**
 * Enqueue login page styles.
 *
 * @since 1.0.0
 */
function soli_admin_theme_login_styles(): void {
    wp_enqueue_style(
        'soli-admin-theme-login',
        get_stylesheet_uri(),
        array(),
        wp_get_theme()->get( 'Version' )
    );
}
add_action( 'login_enqueue_scripts', 'soli_admin_theme_login_styles' );

/**
 * Add custom heading above the login form.
 *
 * @since 1.0.0
 * @param string $message Login message.
 * @return string
 */
function soli_admin_theme_login_message( string $message ): string {
    $title    = esc_html__( 'Soli Administration', 'soli-admin-theme' );
    $subtitle = esc_html__( 'Administration and Authentication', 'soli-admin-theme' );

    $custom_header  = '<h1 class="soli-login-title">' . $title . '</h1>';
    $custom_header .= '<p class="soli-login-subtitle">' . $subtitle . '</p>';

    return $custom_header . $message;
}
add_filter( 'login_message', 'soli_admin_theme_login_message' );

/**
 * Change login header URL.
 *
 * @since 1.0.0
 * @return string
 */
function soli_admin_theme_login_header_url(): string {
    return home_url();
}
add_filter( 'login_headerurl', 'soli_admin_theme_login_header_url' );

/**
 * Change login header text.
 *
 * @since 1.0.0
 * @return string
 */
function soli_admin_theme_login_header_text(): string {
    return get_bloginfo( 'name' );
}
add_filter( 'login_headertext', 'soli_admin_theme_login_header_text' );

/**
 * Remove posts, pages, media, and comments from admin menu.
 *
 * @since 1.0.0
 */
function soli_admin_theme_remove_menus(): void {
    remove_menu_page( 'edit.php' );              // Posts.
    remove_menu_page( 'edit.php?post_type=page' ); // Pages.
    remove_menu_page( 'upload.php' );            // Media.
    remove_menu_page( 'edit-comments.php' );     // Comments.
}
add_action( 'admin_menu', 'soli_admin_theme_remove_menus', 999 );

/**
 * Remove items from admin bar.
 *
 * @since 1.0.0
 * @param WP_Admin_Bar $wp_admin_bar Admin bar instance.
 */
function soli_admin_theme_remove_admin_bar_items( WP_Admin_Bar $wp_admin_bar ): void {
    $wp_admin_bar->remove_node( 'new-post' );
    $wp_admin_bar->remove_node( 'new-page' );
    $wp_admin_bar->remove_node( 'new-media' );
    $wp_admin_bar->remove_node( 'comments' );
}
add_action( 'admin_bar_menu', 'soli_admin_theme_remove_admin_bar_items', 999 );

/**
 * Block access to posts, pages, media, and comments admin pages.
 *
 * @since 1.0.0
 */
function soli_admin_theme_block_admin_pages(): void {
    global $pagenow;

    $blocked_pages = array(
        'edit.php',
        'post.php',
        'post-new.php',
        'upload.php',
        'media-new.php',
        'edit-comments.php',
        'comment.php',
    );

    if ( in_array( $pagenow, $blocked_pages, true ) ) {
        // Allow pages post type check.
        if ( 'edit.php' === $pagenow || 'post-new.php' === $pagenow ) {
            $post_type = isset( $_GET['post_type'] ) ? sanitize_key( $_GET['post_type'] ) : 'post';
            if ( 'post' === $post_type || 'page' === $post_type ) {
                wp_safe_redirect( admin_url() );
                exit;
            }
        } elseif ( 'post.php' === $pagenow ) {
            $post_id   = isset( $_GET['post'] ) ? absint( $_GET['post'] ) : 0;
            $post_type = $post_id ? get_post_type( $post_id ) : 'post';
            if ( 'post' === $post_type || 'page' === $post_type ) {
                wp_safe_redirect( admin_url() );
                exit;
            }
        } else {
            wp_safe_redirect( admin_url() );
            exit;
        }
    }
}
add_action( 'admin_init', 'soli_admin_theme_block_admin_pages' );

/**
 * Disable comment support.
 *
 * @since 1.0.0
 */
function soli_admin_theme_disable_comments(): void {
    // Remove comment support from all post types.
    foreach ( get_post_types() as $post_type ) {
        if ( post_type_supports( $post_type, 'comments' ) ) {
            remove_post_type_support( $post_type, 'comments' );
            remove_post_type_support( $post_type, 'trackbacks' );
        }
    }
}
add_action( 'admin_init', 'soli_admin_theme_disable_comments' );

/**
 * Close comments on the front-end.
 *
 * @since 1.0.0
 * @return bool
 */
add_filter( 'comments_open', '__return_false', 20 );
add_filter( 'pings_open', '__return_false', 20 );

/**
 * Hide existing comments.
 *
 * @since 1.0.0
 * @return array
 */
add_filter( 'comments_array', '__return_empty_array', 10 );
