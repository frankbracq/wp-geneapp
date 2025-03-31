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

function render_geneapp_embed_shortcode($atts) {
  $atts = shortcode_atts([
      'src' => 'https://genealogie.app/embed',
      'auto_height' => 'false',
      'width' => '100%',
      'height' => '600px'
  ], $atts);

  $user = wp_get_current_user();
  $uid = $user->ID;
  $email = $user->user_email;
  $ts = time();
  $secret = get_option('geneapp_hmac_secret');

  // Générer la signature HMAC
  $string_to_sign = "uid=$uid&email=$email&ts=$ts";
  $sig = hash_hmac('sha256', $string_to_sign, $secret);

  // Construire l'URL finale avec les paramètres signés
  $signed_src = add_query_arg([
      'uid' => $uid,
      'email' => rawurlencode($email),
      'ts' => $ts,
      'sig' => $sig
  ], $atts['src']);

  // Générer l'iframe
  $iframe_id = 'geneapp-embed-' . uniqid();
  $iframe = sprintf(
      '<iframe id="%s" src="%s" width="%s" height="%s" style="border:none;" loading="lazy" allowfullscreen></iframe>',
      esc_attr($iframe_id),
      esc_url($signed_src),
      esc_attr($atts['width']),
      esc_attr($atts['height'])
  );

  // Script pour auto-resize si demandé
  $script = '';
  if ($atts['auto_height'] === 'true') {
      $script = "<script>
          window.addEventListener('message', function(event) {
              if (event.origin !== 'https://genealogie.app') return;
              if (event.data?.type === 'resize' && typeof event.data.height === 'number') {
                  const iframe = document.getElementById('$iframe_id');
                  if (iframe) iframe.style.height = event.data.height + 'px';
              }
          });
      </script>";
  }

  return $iframe . $script;
}

add_shortcode('geneapp_embed', 'wp_geneapp_shortcode');
