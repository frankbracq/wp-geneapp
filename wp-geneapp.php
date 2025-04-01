<?php
/**
 * Plugin Name: WP GeneApp (Test Signature HMAC)
 * Description: Version de test avec clé HMAC codée en dur.
 * Version: 1.1.0-test
 */

 function wp_geneapp_shortcode($atts) {
    if (!is_user_logged_in()) return '<p>Veuillez vous connecter pour accéder à cette fonctionnalité.</p>';

    $current_user = wp_get_current_user();
    $user_data = [
        'id'        => $current_user->ID,
        'email'     => $current_user->user_email,
        'timestamp' => time(),
    ];

    $atts = shortcode_atts([
        'src'         => '',
        'width'       => '100%',
        'height'      => '600px',
        'auto_height' => 'false',
    ], $atts);

    if (empty($atts['src'])) return '<p>Erreur : URL de l’iframe manquante.</p>';

    // Infos partenaire
    $partner_id = 'lisi7921.odns.fr';
    $partner_secret = 'cle_secrete_wp_lisi7921';

    // Génération signature (appel propre)
    $signature = wp_geneapp_generate_signature($partner_id, $user_data, $partner_secret);

    // URL iframe complète
    $iframe_url = add_query_arg([
        'partner_id' => $partner_id,
        'uid'        => $user_data['id'],
        'email'      => urlencode($user_data['email']),
        'ts'         => $user_data['timestamp'],
        'sig'        => $signature,
    ], $atts['src']);

    $iframe_id = 'wpGeneappIframe_' . uniqid();

    ob_start();
    ?>
    <iframe id="<?php echo esc_attr($iframe_id); ?>"
            src="<?php echo esc_url($iframe_url); ?>"
            width="<?php echo esc_attr($atts['width']); ?>"
            height="<?php echo esc_attr($atts['height']); ?>"
            style="border: none;"></iframe>

    <?php if ($atts['auto_height'] === 'true') : ?>
    <script>
      window.addEventListener("message", (event) => {
        if (!event.origin.includes("genealogie.app")) return;
        if (event.data.geneappHeight && !isNaN(event.data.geneappHeight)) {
          const iframe = document.getElementById('<?php echo esc_js($iframe_id); ?>');
          iframe.style.height = event.data.geneappHeight + "px";
        }
      });
    </script>
    <?php endif; ?>

    <?php
    return ob_get_clean();
}
add_shortcode('geneapp_embed', 'wp_geneapp_shortcode');
