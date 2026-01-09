<?php
/**
 * Plugin Name: Modern Admin UI
 * Plugin URI: https://smithnew.jbmdcreations.dev
 * Description: Modernizes WordPress admin pages with clean, tabbed interfaces. Control which pages get the modern treatment.
 * Version: 1.1.0
 * Author: JBMD Creations
 * Author URI: https://jbmdcreations.dev
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: modern-admin-ui
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('MODERN_ADMIN_UI_VERSION', '1.1.0');
define('MODERN_ADMIN_UI_PATH', plugin_dir_path(__FILE__));
define('MODERN_ADMIN_UI_URL', plugin_dir_url(__FILE__));

/**
 * Main Plugin Class
 */
class Modern_Admin_UI_Plugin {
    
    private static $instance = null;
    
    /**
     * Get singleton instance
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Constructor
     */
    private function __construct() {
        // Add admin menu
        add_action('admin_menu', array($this, 'add_admin_menu'));
        
        // Register settings
        add_action('admin_init', array($this, 'register_settings'));
        
        // Enqueue admin styles for settings page
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_assets'));
        
        // Load individual page modernizers based on settings
        add_action('admin_init', array($this, 'load_page_modernizers'));
    }
    
    /**
     * Add admin menu
     */
    public function add_admin_menu() {
        add_menu_page(
            'Modern Admin UI',           // Page title
            'Modern Admin UI',           // Menu title
            'manage_options',            // Capability
            'modern-admin-ui',           // Menu slug
            array($this, 'render_settings_page'), // Callback
            'dashicons-admin-appearance', // Icon
            80                           // Position (after Settings)
        );
    }
    
    /**
     * Register plugin settings
     */
    public function register_settings() {
        // Register setting
        register_setting(
            'modern_admin_ui_options',  // Option group
            'modern_admin_ui_settings', // Option name
            array($this, 'sanitize_settings') // Sanitize callback
        );
        
        // Add settings section
        add_settings_section(
            'modern_admin_ui_general',  // Section ID
            'Enable Modern UI for Admin Pages', // Section title
            array($this, 'render_section_description'), // Callback
            'modern-admin-ui'           // Page slug
        );
        
        // Add setting fields
        add_settings_field(
            'enable_general_settings',  // Field ID
            'Settings → General',       // Field title
            array($this, 'render_checkbox_field'), // Callback
            'modern-admin-ui',          // Page slug
            'modern_admin_ui_general',  // Section ID
            array(
                'label_for' => 'enable_general_settings',
                'field_key' => 'enable_general_settings',
                'description' => 'Transform the General Settings page with a modern, tabbed interface'
            )
        );
        
        // Additional settings pages
        $additional_pages = array(
            'enable_reading_settings' => array(
                'title' => 'Settings → Reading',
                'description' => 'Transform the Reading Settings page with a modern, tabbed interface'
            ),
            'enable_discussion_settings' => array(
                'title' => 'Settings → Discussion',
                'description' => 'Transform the Discussion Settings page with organized sections'
            ),
            'enable_media_settings' => array(
                'title' => 'Settings → Media',
                'description' => 'Transform the Media Settings page with visual size cards'
            ),
            'enable_permalink_settings' => array(
                'title' => 'Settings → Permalinks',
                'description' => 'Transform the Permalinks Settings page with SEO-friendly options'
            )
        );
        
        foreach ($additional_pages as $key => $page) {
            add_settings_field(
                $key,
                $page['title'],
                array($this, 'render_checkbox_field'),
                'modern-admin-ui',
                'modern_admin_ui_general',
                array(
                    'label_for' => $key,
                    'field_key' => $key,
                    'description' => $page['description'],
                    'disabled' => false
                )
            );
        }
    }
    
    /**
     * Sanitize settings
     */
    public function sanitize_settings($input) {
        $sanitized = array();
        
        // List of valid fields
        $valid_fields = array(
            'enable_general_settings',
            'enable_reading_settings',
            'enable_discussion_settings',
            'enable_media_settings',
            'enable_permalink_settings'
        );
        
        foreach ($valid_fields as $field) {
            $sanitized[$field] = isset($input[$field]) ? 1 : 0;
        }
        
        return $sanitized;
    }
    
    /**
     * Render section description
     */
    public function render_section_description() {
        echo '<p class="description">Choose which WordPress admin pages should use the modern UI. Changes take effect immediately.</p>';
    }
    
    /**
     * Render checkbox field
     */
    public function render_checkbox_field($args) {
        $options = get_option('modern_admin_ui_settings', array());
        $value = isset($options[$args['field_key']]) ? $options[$args['field_key']] : 0;
        $disabled = isset($args['disabled']) && $args['disabled'] ? 'disabled' : '';
        $checked = checked(1, $value, false);
        
        ?>
        <div class="modern-toggle-wrapper">
            <label class="modern-toggle">
                <input 
                    type="checkbox" 
                    id="<?php echo esc_attr($args['label_for']); ?>"
                    name="modern_admin_ui_settings[<?php echo esc_attr($args['field_key']); ?>]"
                    value="1"
                    <?php echo $checked; ?>
                    <?php echo $disabled; ?>
                    class="modern-toggle-checkbox"
                >
                <span class="modern-toggle-slider"></span>
            </label>
            <span class="modern-toggle-label">
                <?php echo $checked ? 'Enabled' : 'Disabled'; ?>
            </span>
            <?php if (isset($args['disabled']) && $args['disabled']): ?>
                <span class="modern-badge coming-soon">Coming Soon</span>
            <?php endif; ?>
        </div>
        <p class="description"><?php echo esc_html($args['description']); ?></p>
        <?php
    }
    
    /**
     * Enqueue admin assets
     */
    public function enqueue_admin_assets($hook) {
        // Only load on our settings page
        if ('toplevel_page_modern-admin-ui' !== $hook) {
            return;
        }
        
        // Add inline styles for our settings page
        wp_add_inline_style('wp-admin', $this->get_settings_page_styles());
        
        // Add inline script for toggle interactions
        wp_add_inline_script('jquery', $this->get_settings_page_scripts());
    }
    
    /**
     * Get settings page styles
     */
    private function get_settings_page_styles() {
        return "
        /* Modern Admin UI Settings Page Styles */
        .modern-admin-ui-wrap {
            max-width: 1200px;
            margin: 20px 0;
        }
        
        .modern-admin-ui-wrap .wrap > h1 {
            font-size: 28px;
            font-weight: 400;
            margin-bottom: 8px;
        }
        
        .modern-admin-header {
            background: white;
            padding: 24px 32px;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        
        .modern-admin-header h1 {
            font-size: 28px;
            font-weight: 400;
            margin: 0 0 8px 0;
            color: #1d2327;
        }
        
        .modern-admin-header p {
            color: #646970;
            font-size: 14px;
            margin: 0;
        }
        
        .modern-admin-ui-content {
            background: white;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            padding: 32px;
        }
        
        .modern-admin-ui-content .form-table th {
            padding: 20px 0;
            font-weight: 600;
            color: #1d2327;
            font-size: 14px;
            width: 280px;
        }
        
        .modern-admin-ui-content .form-table td {
            padding: 20px 0;
            border-bottom: 1px solid #f0f0f1;
        }
        
        .modern-admin-ui-content .form-table tr:last-child td {
            border-bottom: none;
        }
        
        /* Toggle Switch Styles */
        .modern-toggle-wrapper {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 6px;
        }
        
        .modern-toggle {
            position: relative;
            display: inline-block;
            width: 48px;
            height: 26px;
            margin: 0;
        }
        
        .modern-toggle-checkbox {
            opacity: 0;
            width: 0;
            height: 0;
        }
        
        .modern-toggle-slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #8c8f94;
            transition: 0.3s;
            border-radius: 26px;
        }
        
        .modern-toggle-slider:before {
            position: absolute;
            content: '';
            height: 20px;
            width: 20px;
            left: 3px;
            bottom: 3px;
            background-color: white;
            transition: 0.3s;
            border-radius: 50%;
        }
        
        .modern-toggle-checkbox:checked + .modern-toggle-slider {
            background-color: #2271b1;
        }
        
        .modern-toggle-checkbox:checked + .modern-toggle-slider:before {
            transform: translateX(22px);
        }
        
        .modern-toggle-checkbox:disabled + .modern-toggle-slider {
            opacity: 0.5;
            cursor: not-allowed;
        }
        
        .modern-toggle-label {
            font-weight: 500;
            color: #1d2327;
            font-size: 14px;
        }
        
        .modern-toggle-checkbox:checked ~ .modern-toggle-label {
            color: #2271b1;
        }
        
        .modern-badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
        }
        
        .modern-badge.coming-soon {
            background: #f0f6fc;
            color: #0969da;
        }
        
        .modern-admin-ui-content .description {
            margin-top: 6px;
            font-size: 13px;
            color: #646970;
            line-height: 1.5;
        }
        
        .modern-admin-ui-content .submit {
            margin: 0;
            padding: 24px 0 0 0;
            border-top: 1px solid #e0e0e0;
        }
        
        .modern-admin-ui-content .button-primary {
            background: #2271b1;
            border-color: #2271b1;
            padding: 10px 24px;
            font-size: 14px;
            font-weight: 500;
            height: auto;
        }
        
        .modern-admin-ui-content .button-primary:hover {
            background: #135e96;
            border-color: #135e96;
        }
        
        /* Success Message */
        #setting-error-settings_updated {
            margin: 0 0 20px 0 !important;
            padding: 12px 16px !important;
            background: #d7f1e6 !important;
            border-left: 4px solid #00a32a !important;
            border-radius: 8px !important;
        }
        
        /* Info Box */
        .modern-info-box {
            background: #f0f6fc;
            border-left: 4px solid #2271b1;
            padding: 16px;
            border-radius: 6px;
            margin-top: 24px;
        }
        
        .modern-info-box h3 {
            margin: 0 0 8px 0;
            color: #1d2327;
            font-size: 14px;
            font-weight: 600;
        }
        
        .modern-info-box p {
            margin: 0;
            color: #646970;
            font-size: 13px;
            line-height: 1.5;
        }
        
        .modern-info-box ul {
            margin: 8px 0 0 20px;
            color: #646970;
            font-size: 13px;
        }
        
        .modern-info-box li {
            margin: 4px 0;
        }
        ";
    }
    
    /**
     * Get settings page scripts
     */
    private function get_settings_page_scripts() {
        return "
        jQuery(document).ready(function($) {
            // Update toggle label text on change
            $('.modern-toggle-checkbox').on('change', function() {
                var label = $(this).closest('.modern-toggle-wrapper').find('.modern-toggle-label');
                if ($(this).is(':checked')) {
                    label.text('Enabled');
                } else {
                    label.text('Disabled');
                }
            });
        });
        ";
    }
    
    /**
     * Render settings page
     */
    public function render_settings_page() {
        // Check user capabilities
        if (!current_user_can('manage_options')) {
            return;
        }
        ?>
        <div class="wrap modern-admin-ui-wrap">
            <div class="modern-admin-header">
                <h1>Modern Admin UI Settings</h1>
                <p>Control which WordPress admin pages use the modern interface</p>
            </div>
            
            <?php settings_errors('modern_admin_ui_messages'); ?>
            
            <div class="modern-admin-ui-content">
                <form method="post" action="options.php">
                    <?php
                    settings_fields('modern_admin_ui_options');
                    do_settings_sections('modern-admin-ui');
                    submit_button('Save Settings', 'primary', 'submit', false);
                    ?>
                </form>
                
                <div class="modern-info-box">
                    <h3>✨ What does this do?</h3>
                    <p>This plugin modernizes WordPress admin pages with:</p>
                    <ul>
                        <li>Clean, organized tabbed interfaces</li>
                        <li>Improved visual hierarchy and spacing</li>
                        <li>Modern form styling with better focus states</li>
                        <li>Responsive design that works on all devices</li>
                    </ul>
                    <p style="margin-top: 12px;"><strong>Note:</strong> All WordPress functionality is preserved - we only improve the visual design and user experience.</p>
                </div>
            </div>
        </div>
        <?php
    }
    
    /**
     * Load page modernizers based on settings
     */
    public function load_page_modernizers() {
        $options = get_option('modern_admin_ui_settings', array());

        // Load General Settings modernizer if enabled
        if (isset($options['enable_general_settings']) && $options['enable_general_settings']) {
            require_once MODERN_ADMIN_UI_PATH . 'includes/class-general-settings.php';
            new Modern_General_Settings();
        }

        // Load Reading Settings modernizer if enabled
        if (isset($options['enable_reading_settings']) && $options['enable_reading_settings']) {
            require_once MODERN_ADMIN_UI_PATH . 'includes/class-reading-settings.php';
            new Modern_Reading_Settings();
        }

        // Load Discussion Settings modernizer if enabled
        if (isset($options['enable_discussion_settings']) && $options['enable_discussion_settings']) {
            require_once MODERN_ADMIN_UI_PATH . 'includes/class-discussion-settings.php';
            new Modern_Discussion_Settings();
        }

        // Load Media Settings modernizer if enabled
        if (isset($options['enable_media_settings']) && $options['enable_media_settings']) {
            require_once MODERN_ADMIN_UI_PATH . 'includes/class-media-settings.php';
            new Modern_Media_Settings();
        }

        // Load Permalinks Settings modernizer if enabled
        if (isset($options['enable_permalink_settings']) && $options['enable_permalink_settings']) {
            require_once MODERN_ADMIN_UI_PATH . 'includes/class-permalinks-settings.php';
            new Modern_Permalinks_Settings();
        }
    }
}

// Initialize the plugin
function modern_admin_ui_init() {
    return Modern_Admin_UI_Plugin::get_instance();
}

// Start the plugin
add_action('plugins_loaded', 'modern_admin_ui_init');
