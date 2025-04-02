<?php
/**
 * Page d'options pour le plugin WP GeneApp
 */

if (!defined('ABSPATH')) {
    exit;
}

class WP_GeneApp_Admin {
    
    /**
     * Initialisation de la page d'administration
     */
    public function __construct() {
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_init', array($this, 'register_settings'));
    }
    
    /**
     * Ajouter le menu dans l'administration
     */
    public function add_admin_menu() {
        add_options_page(
            'Paramètres GeneApp',
            'GeneApp',
            'manage_options',
            'wp-geneapp-settings',
            array($this, 'settings_page')
        );
    }
    
    /**
     * Enregistrer les paramètres
     */
    public function register_settings() {
        register_setting('wp_geneapp_settings', 'geneapp_partner_id');
        register_setting('wp_geneapp_settings', 'geneapp_partner_secret');
        register_setting('wp_geneapp_settings', 'geneapp_iframe_auto_height', array(
            'type' => 'boolean',
            'default' => true,
        ));
        
        add_settings_section(
            'wp_geneapp_main_section',
            'Paramètres d\'intégration',
            array($this, 'main_section_callback'),
            'wp-geneapp-settings'
        );
        
        add_settings_field(
            'geneapp_partner_id',
            'Identifiant Partenaire',
            array($this, 'partner_id_callback'),
            'wp-geneapp-settings',
            'wp_geneapp_main_section'
        );
        
        add_settings_field(
            'geneapp_partner_secret',
            'Clé Secrète',
            array($this, 'partner_secret_callback'),
            'wp-geneapp-settings',
            'wp_geneapp_main_section'
        );
        
        add_settings_field(
            'geneapp_iframe_auto_height',
            'Hauteur automatique',
            array($this, 'auto_height_callback'),
            'wp-geneapp-settings',
            'wp_geneapp_main_section'
        );
    }
    
    /**
     * Callback pour la section principale
     */
    public function main_section_callback() {
        echo '<p>Configurez les paramètres de connexion à GeneApp :</p>';
    }
    
    /**
     * Callback pour l'ID partenaire
     */
    public function partner_id_callback() {
        $value = get_option('geneapp_partner_id', 'geneapp-wp.fr');
        echo '<input type="text" id="geneapp_partner_id" name="geneapp_partner_id" value="' . esc_attr($value) . '" class="regular-text">';
        echo '<p class="description">L\'identifiant fourni par GeneApp.</p>';
    }
    
    /**
     * Callback pour la clé secrète
     */
    public function partner_secret_callback() {
        $value = get_option('geneapp_partner_secret', '');
        echo '<input type="password" id="geneapp_partner_secret" name="geneapp_partner_secret" value="' . esc_attr($value) . '" class="regular-text">';
        echo '<p class="description">La clé secrète fournie par GeneApp.</p>';
    }
    
    /**
     * Callback pour l'option hauteur automatique
     */
    public function auto_height_callback() {
        $value = get_option('geneapp_iframe_auto_height', true);
        echo '<input type="checkbox" id="geneapp_iframe_auto_height" name="geneapp_iframe_auto_height" value="1" ' . checked(1, $value, false) . '>';
        echo '<label for="geneapp_iframe_auto_height">Activer l\'ajustement automatique de la hauteur de l\'iframe</label>';
    }
    
    /**
     * Affichage de la page de paramètres
     */
    public function settings_page() {
        if (!current_user_can('manage_options')) {
            return;
        }
        ?>
        <div class="wrap">
            <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
            <form action="options.php" method="post">
                <?php
                settings_fields('wp_geneapp_settings');
                do_settings_sections('wp-geneapp-settings');
                submit_button('Enregistrer les paramètres');
                ?>
            </form>
            
            <hr>
            
            <h2>Utilisation</h2>
            <p>Vous pouvez utiliser le shortcode <code>[geneapp_embed]</code> sur n'importe quelle page.</p>
            <p>Une page <a href="<?php echo esc_url(get_permalink(get_page_by_path('genealogie'))); ?>">Généalogie</a> a été automatiquement créée lors de l'activation du plugin.</p>
            
            <h3>Options du shortcode</h3>
            <ul>
                <li><code>auto_height="true|false"</code> : Active l'ajustement automatique de la hauteur (par défaut: <?php echo get_option('geneapp_iframe_auto_height', true) ? 'true' : 'false'; ?>)</li>
                <li><code>fullscreen="true|false"</code> : Affiche en plein écran (par défaut: false)</li>
            </ul>
        </div>
        <?php
    }
}

// Initialiser la classe d'administration
$wp_geneapp_admin = new WP_GeneApp_Admin();