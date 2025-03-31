<?php
defined('ABSPATH') || exit;

function wp_geneapp_generate_signature($data) {
    $secret = get_option('wp_geneapp_secret', 'default_key');
    return hash_hmac('sha256', json_encode($data), $secret);
}
