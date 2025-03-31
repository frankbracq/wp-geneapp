<?php
defined('ABSPATH') || exit;

function wp_geneapp_register_admin_menu() {
    add_options_page(
        'WP GeneApp',
        'WP GeneApp',
        'manage_options',
        'wp-geneapp',
        'wp_geneapp_render_settings_page'
    );
}
add_action('admin_menu', 'wp_geneapp_register_admin_menu');

function wp_geneapp_render_settings_page() {
    ?>
    <div class="wrap">
        <h1>WP GeneApp – Configuration</h1>
        <form method="post" action="options.php">
            <?php
                settings_fields('wp_geneapp_options');
                do_settings_sections('wp-geneapp');
                submit_button();
            ?>
        </form>
    </div>
    <?php
}

function wp_geneapp_register_settings() {
    register_setting('wp_geneapp_options', 'wp_geneapp_secret');

    add_settings_section(
        'wp_geneapp_main',
        'Clé secrète pour signature HMAC',
        null,
        'wp-geneapp'
    );

    add_settings_field(
        'wp_geneapp_secret',
        'Clé HMAC',
        'wp_geneapp_secret_field_render',
        'wp-geneapp',
        'wp_geneapp_main'
    );
}
add_action('admin_init', 'wp_geneapp_register_settings');

function wp_geneapp_secret_field_render() {
    $value = get_option('wp_geneapp_secret', '');
    echo '<input type="text" name="wp_geneapp_secret" value="' . esc_attr($value) . '" style="width: 400px;" />';
}
