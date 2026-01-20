<?php
/**
 * Main template file - User Dashboard.
 *
 * @package Soli_Admin_Theme
 * @since 1.0.0
 */

defined( 'ABSPATH' ) || exit;

$current_user = wp_get_current_user();
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<main class="soli-admin-dashboard">
    <h1><?php esc_html_e( 'Soli Administration', 'soli-admin-theme' ); ?></h1>
    <p class="soli-subtitle"><?php esc_html_e( 'Your account information', 'soli-admin-theme' ); ?></p>

    <div class="soli-user-info">
        <div class="soli-user-field">
            <label><?php esc_html_e( 'Username', 'soli-admin-theme' ); ?></label>
            <span><?php echo esc_html( $current_user->user_login ); ?></span>
        </div>

        <div class="soli-user-field">
            <label><?php esc_html_e( 'Email address', 'soli-admin-theme' ); ?></label>
            <span><?php echo esc_html( $current_user->user_email ); ?></span>
        </div>
    </div>

    <div class="soli-actions">
        <a href="<?php echo esc_url( wp_lostpassword_url( home_url() ) ); ?>" class="soli-btn-primary">
            <?php esc_html_e( 'Reset password', 'soli-admin-theme' ); ?>
        </a>
        <a href="<?php echo esc_url( wp_logout_url( home_url() ) ); ?>" class="soli-btn-secondary">
            <?php esc_html_e( 'Log out', 'soli-admin-theme' ); ?>
        </a>
    </div>
</main>

<?php wp_footer(); ?>
</body>
</html>
