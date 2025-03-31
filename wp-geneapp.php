<?php
/**
 * Plugin Name: WP GeneApp
 * Description: Intègre une app externe via iframe avec transmission sécurisée de l'identité utilisateur WordPress.
 * Version: 1.0.0
 * Author: KoT 
 * License: GPL2
 * Text Domain: wp-geneapp
 */

defined('ABSPATH') || exit;

require_once plugin_dir_path(__FILE__) . 'includes/signature.php';
require_once plugin_dir_path(__FILE__) . 'includes/admin-ui.php';

function wp_geneapp_shortcode($atts) {
    if (!is_user_logged_in()) return '<p>Veuillez vous connecter pour accéder à cette fonctionnalité.</p>';

    $current_user = wp_get_current_user();
    $user_data = [
        'id'        => $current_user->ID,
        'email'     => $current_user->user_email,
        'timestamp' => time(),
    ];

    $signature = wp_geneapp_generate_signature($user_data);
    $payload = [
        'user'      => $user_data,
        'signature' => $signature,
    ];

    $atts = shortcode_atts([
        'src'         => '',
        'width'       => '100%',
        'height'      => '600px',
        'auto_height' => 'false',
    ], $atts);

    if (empty($atts['src'])) return '<p>Erreur : URL de l’iframe manquante.</p>';

    $iframe_id = 'wpGeneappIframe_' . uniqid();

    ob_start();
    ?>
    <iframe id="<?php echo esc_attr($iframe_id); ?>"
            src="<?php echo esc_url($atts['src']); ?>"
            width="<?php echo esc_attr($atts['width']); ?>"
            height="<?php echo esc_attr($atts['height']); ?>"
            style="border: none;"></iframe>

    <script>
      const iframe = document.getElementById('<?php echo esc_js($iframe_id); ?>');

      iframe.onload = () => {
        const payload = <?php echo json_encode($payload); ?>;
        iframe.contentWindow.postMessage(payload, '<?php echo esc_js(parse_url($atts["src"], PHP_URL_SCHEME) . "://" . parse_url($atts["src"], PHP_URL_HOST)); ?>');
      };

      <?php if ($atts['auto_height'] === 'true') : ?>
      window.addEventListener("message", (event) => {
        if (!event.origin.includes("genealogie.app")) return;
        if (event.data.geneappHeight && !isNaN(event.data.geneappHeight)) {
          iframe.style.height = event.data.geneappHeight + "px";
        }
      });
      <?php endif; ?>
    </script>
    <?php
    return ob_get_clean();
}
add_shortcode('geneapp_embed', 'wp_geneapp_shortcode');
