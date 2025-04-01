<?php
defined('ABSPATH') || exit;

/**
 * Génère une signature HMAC complète pour la requête iframe GeneApp
 */
function wp_geneapp_generate_signature($partner_id, $user_data, $secret) {
    $payload = http_build_query([
        'partner_id' => $partner_id,
        'uid'        => $user_data['id'],
        'email'      => $user_data['email'],
        'ts'         => $user_data['timestamp'],
    ]);

    return hash_hmac('sha256', $payload, $secret);
}

