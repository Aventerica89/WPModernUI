<?php
/**
 * Plugin Name: Modern Admin UI
 * Plugin URI: https://smithnew.jbmdcreations.dev
 * Description: Modernizes WordPress admin pages with clean, tabbed interfaces. Control which pages get the modern treatment.
 * Version: 1.2.0
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
define('MODERN_ADMIN_UI_VERSION', '1.2.0');
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

        // Output custom styles on all admin pages
        add_action('admin_head', array($this, 'output_custom_styles'));
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
    }

    /**
     * Get all available settings pages
     */
    public function get_settings_pages() {
        return array(
            'enable_general_settings' => array(
                'title' => 'Settings → General',
                'description' => 'Transform the General Settings page with a modern, tabbed interface'
            ),
            'enable_writing_settings' => array(
                'title' => 'Settings → Writing',
                'description' => 'Transform the Writing Settings page with organized sections'
            ),
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
            ),
            'enable_list_tables' => array(
                'title' => 'List Tables (Posts, Pages, etc.)',
                'description' => 'Modernize all list tables including Posts, Pages, Categories, Tags, Users, and more'
            ),
            'enable_profile_page' => array(
                'title' => 'Users → Profile',
                'description' => 'Transform the user profile page with organized tabs for personal options, contact info, and account settings'
            ),
            'enable_admin_menu' => array(
                'title' => 'Admin Menu & Toolbar',
                'description' => 'Modernize the sidebar navigation menu and top admin toolbar with enhanced styling'
            )
        );
    }
    
    /**
     * Sanitize settings
     */
    public function sanitize_settings($input) {
        $sanitized = array();

        // Master toggle
        $sanitized['enable_all_settings'] = isset($input['enable_all_settings']) ? 1 : 0;

        // Individual page settings
        $pages = $this->get_settings_pages();
        foreach (array_keys($pages) as $field) {
            $sanitized[$field] = isset($input[$field]) ? 1 : 0;
        }

        // Style options
        $valid_color_schemes = array('default', 'blue', 'green', 'purple', 'orange', 'dark');
        $sanitized['color_scheme'] = isset($input['color_scheme']) && in_array($input['color_scheme'], $valid_color_schemes)
            ? $input['color_scheme'] : 'default';

        $sanitized['primary_color'] = isset($input['primary_color'])
            ? sanitize_hex_color($input['primary_color']) : '#2271b1';

        $sanitized['rounded_corners'] = isset($input['rounded_corners']) ? 1 : 0;
        $sanitized['compact_mode'] = isset($input['compact_mode']) ? 1 : 0;
        $sanitized['smooth_animations'] = isset($input['smooth_animations']) ? 1 : 0;
        $sanitized['modern_buttons'] = isset($input['modern_buttons']) ? 1 : 0;
        $sanitized['enhanced_focus'] = isset($input['enhanced_focus']) ? 1 : 0;

        return $sanitized;
    }

    /**
     * Get default style options
     */
    public function get_default_styles() {
        return array(
            'color_scheme' => 'default',
            'primary_color' => '#2271b1',
            'rounded_corners' => 1,
            'compact_mode' => 0,
            'smooth_animations' => 1,
            'modern_buttons' => 1,
            'enhanced_focus' => 1
        );
    }

    /**
     * Output custom styles on admin pages
     */
    public function output_custom_styles() {
        $options = get_option('modern_admin_ui_settings', array());
        $defaults = $this->get_default_styles();

        // Merge with defaults
        $styles = wp_parse_args($options, $defaults);

        // Color scheme presets
        $color_schemes = array(
            'default' => array('primary' => '#2271b1', 'accent' => '#135e96'),
            'blue' => array('primary' => '#0073aa', 'accent' => '#005177'),
            'green' => array('primary' => '#00a32a', 'accent' => '#007017'),
            'purple' => array('primary' => '#7c3aed', 'accent' => '#5b21b6'),
            'orange' => array('primary' => '#d97706', 'accent' => '#b45309'),
            'dark' => array('primary' => '#1e293b', 'accent' => '#334155')
        );

        $scheme = isset($color_schemes[$styles['color_scheme']]) ? $color_schemes[$styles['color_scheme']] : $color_schemes['default'];
        $primary_color = !empty($styles['primary_color']) && $styles['color_scheme'] === 'default' ? $styles['primary_color'] : $scheme['primary'];

        // Calculate accent color (darker version)
        $accent_color = $this->adjust_color_brightness($primary_color, -20);

        ?>
        <style type="text/css" id="modern-admin-ui-custom-styles">
        :root {
            --maui-primary: <?php echo esc_attr($primary_color); ?>;
            --maui-accent: <?php echo esc_attr($accent_color); ?>;
            --maui-radius: <?php echo $styles['rounded_corners'] ? '8px' : '2px'; ?>;
            --maui-radius-sm: <?php echo $styles['rounded_corners'] ? '4px' : '1px'; ?>;
        }

        <?php if ($styles['smooth_animations']) : ?>
        /* Smooth animations */
        .modern-settings-wrapper,
        .modern-tab-content,
        .modern-settings-panel,
        .modern-toggle-slider,
        .modern-toggle-slider:before,
        .button,
        input,
        select,
        textarea {
            transition: all 0.2s ease !important;
        }
        <?php endif; ?>

        <?php if ($styles['modern_buttons']) : ?>
        /* Modern buttons */
        .modern-settings-wrapper .button-primary,
        .modern-tab-content .button-primary {
            background: var(--maui-primary) !important;
            border-color: var(--maui-primary) !important;
            border-radius: var(--maui-radius-sm) !important;
            text-shadow: none !important;
            box-shadow: 0 1px 2px rgba(0,0,0,0.1) !important;
        }

        .modern-settings-wrapper .button-primary:hover,
        .modern-tab-content .button-primary:hover {
            background: var(--maui-accent) !important;
            border-color: var(--maui-accent) !important;
        }

        .modern-settings-wrapper .button,
        .modern-tab-content .button {
            border-radius: var(--maui-radius-sm) !important;
        }
        <?php endif; ?>

        <?php if ($styles['enhanced_focus']) : ?>
        /* Enhanced focus states */
        .modern-settings-wrapper input:focus,
        .modern-settings-wrapper select:focus,
        .modern-settings-wrapper textarea:focus,
        .modern-tab-content input:focus,
        .modern-tab-content select:focus,
        .modern-tab-content textarea:focus {
            border-color: var(--maui-primary) !important;
            box-shadow: 0 0 0 2px rgba(34, 113, 177, 0.2) !important;
            outline: none !important;
        }
        <?php endif; ?>

        <?php if ($styles['compact_mode']) : ?>
        /* Compact mode */
        .modern-settings-wrapper {
            padding: 16px !important;
        }
        .modern-tab-content {
            padding: 20px !important;
        }
        .modern-form-section {
            margin-bottom: 20px !important;
        }
        .modern-tab-content .form-table td {
            padding: 0 0 16px 0 !important;
        }
        <?php endif; ?>

        /* Apply primary color to UI elements */
        .modern-settings-wrapper .modern-tab-button.active,
        .modern-tab-content .modern-tab-button.active {
            border-bottom-color: var(--maui-primary) !important;
            color: var(--maui-primary) !important;
        }

        .modern-toggle-checkbox:checked + .modern-toggle-slider {
            background-color: var(--maui-primary) !important;
        }

        .modern-settings-wrapper,
        .modern-tab-content {
            border-radius: var(--maui-radius) !important;
        }

        .modern-info-box {
            border-left-color: var(--maui-primary) !important;
        }
        </style>
        <?php
    }

    /**
     * Adjust color brightness
     */
    private function adjust_color_brightness($hex, $percent) {
        $hex = ltrim($hex, '#');

        if (strlen($hex) === 3) {
            $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
        }

        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));

        $r = max(0, min(255, $r + ($r * $percent / 100)));
        $g = max(0, min(255, $g + ($g * $percent / 100)));
        $b = max(0, min(255, $b + ($b * $percent / 100)));

        return sprintf('#%02x%02x%02x', $r, $g, $b);
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

        // Add script to footer
        add_action('admin_footer', array($this, 'output_settings_scripts'));
    }

    /**
     * Output settings page scripts in footer
     */
    public function output_settings_scripts() {
        ?>
        <script type="text/javascript">
        jQuery(document).ready(function($) {
            // Tab switching
            $('.modern-settings-tab').on('click', function() {
                var tab = $(this).data('tab');
                $('.modern-settings-tab').removeClass('active');
                $(this).addClass('active');
                $('.modern-settings-panel').removeClass('active');
                $('.modern-settings-panel[data-tab="' + tab + '"]').addClass('active');
            });

            // Update toggle label text on change
            function updateToggleLabel($checkbox) {
                var $label = $checkbox.closest('.modern-toggle-wrapper').find('.modern-toggle-label');
                $label.text($checkbox.is(':checked') ? 'Enabled' : 'Disabled');
            }

            // Update counter badge
            function updateCounter() {
                var count = $('.modern-settings-list .modern-toggle-checkbox:checked').length;
                var total = $('.modern-settings-list .modern-toggle-checkbox').length;
                $('.modern-counter').text(count + '/' + total);
            }

            // Update master toggle state based on individual toggles
            function updateMasterToggle() {
                var allChecked = true;
                var anyChecked = false;
                $('.modern-settings-list .modern-toggle-checkbox').each(function() {
                    if ($(this).is(':checked')) {
                        anyChecked = true;
                    } else {
                        allChecked = false;
                    }
                });

                var $master = $('#enable_all_settings');
                var $label = $master.closest('.modern-toggle-wrapper').find('.modern-toggle-label');

                if (allChecked) {
                    $master.prop('checked', true);
                    $label.text('All Enabled');
                } else if (anyChecked) {
                    $master.prop('checked', false);
                    $label.text('Some Enabled');
                } else {
                    $master.prop('checked', false);
                    $label.text('Disabled');
                }
            }

            // Master toggle - enable/disable all
            $('#enable_all_settings').on('click', function() {
                var isChecked = $(this).is(':checked');
                $('.modern-settings-list .modern-toggle-checkbox').each(function() {
                    $(this).prop('checked', isChecked);
                    updateToggleLabel($(this));
                });
                updateCounter();

                var $label = $(this).closest('.modern-toggle-wrapper').find('.modern-toggle-label');
                $label.text(isChecked ? 'All Enabled' : 'Disabled');
            });

            // Individual toggle changes
            $('.modern-settings-list .modern-toggle-checkbox').on('change', function() {
                updateToggleLabel($(this));
                updateCounter();
                updateMasterToggle();
            });

            // Initialize
            updateCounter();
            updateMasterToggle();

            // Color scheme selection
            $('.color-scheme-radio').on('change', function() {
                $('.color-scheme-option').removeClass('selected');
                $(this).closest('.color-scheme-option').addClass('selected');
            });

            // Sync color picker with hex input
            $('#primary_color').on('input', function() {
                $('#primary_color_hex').val($(this).val());
            });

            $('#primary_color_hex').on('input', function() {
                var hex = $(this).val();
                if (/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/.test(hex)) {
                    $('#primary_color').val(hex);
                }
            });

            // Update toggle labels in style options
            $('.modern-style-options .modern-toggle-checkbox').on('change', function() {
                var $label = $(this).closest('.modern-toggle-wrapper').find('.modern-toggle-label');
                $label.text($(this).is(':checked') ? 'Enabled' : 'Disabled');
            });
        });
        </script>
        <?php
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
            overflow: hidden;
        }

        /* Tab Navigation */
        .modern-settings-tabs {
            display: flex;
            border-bottom: 1px solid #e0e0e0;
            padding: 0 32px;
            background: #fafafa;
        }

        .modern-settings-tab {
            padding: 16px 24px;
            background: none;
            border: none;
            border-bottom: 3px solid transparent;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            color: #646970;
            transition: all 0.2s ease;
            position: relative;
            top: 1px;
        }

        .modern-settings-tab:hover {
            color: #2271b1;
        }

        .modern-settings-tab.active {
            color: #2271b1;
            border-bottom-color: #2271b1;
            background: white;
        }

        /* Tab Content */
        .modern-settings-panel {
            display: none;
            padding: 32px;
        }

        .modern-settings-panel.active {
            display: block;
        }

        /* Master Toggle */
        .modern-master-toggle {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 8px;
            padding: 20px 24px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            color: white;
        }

        .modern-master-toggle h3 {
            margin: 0;
            font-size: 16px;
            font-weight: 600;
        }

        .modern-master-toggle p {
            margin: 4px 0 0 0;
            font-size: 13px;
            opacity: 0.9;
        }

        .modern-master-toggle .modern-toggle-slider {
            background-color: rgba(255,255,255,0.3);
        }

        .modern-master-toggle .modern-toggle-checkbox:checked + .modern-toggle-slider {
            background-color: rgba(255,255,255,0.9);
        }

        .modern-master-toggle .modern-toggle-checkbox:checked + .modern-toggle-slider:before {
            background-color: #667eea;
        }

        .modern-master-toggle .modern-toggle-label {
            color: white;
        }

        /* Individual Settings */
        .modern-settings-list {
            margin-left: 24px;
            border-left: 3px solid #e0e0e0;
            padding-left: 24px;
        }

        .modern-setting-item {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            padding: 16px 0;
            border-bottom: 1px solid #f0f0f1;
        }

        .modern-setting-item:last-child {
            border-bottom: none;
        }

        .modern-setting-info h4 {
            margin: 0 0 4px 0;
            font-size: 14px;
            font-weight: 600;
            color: #1d2327;
        }

        .modern-setting-info p {
            margin: 0;
            font-size: 13px;
            color: #646970;
        }

        /* Toggle Switch Styles */
        .modern-toggle-wrapper {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .modern-toggle {
            position: relative;
            display: inline-block;
            width: 48px;
            height: 26px;
            margin: 0;
            flex-shrink: 0;
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

        .modern-toggle-label {
            font-weight: 500;
            color: #1d2327;
            font-size: 14px;
        }

        .modern-toggle-checkbox:checked ~ .modern-toggle-label {
            color: #2271b1;
        }

        /* Form Actions */
        .modern-form-actions {
            margin-top: 24px;
            padding-top: 24px;
            border-top: 1px solid #e0e0e0;
        }

        .modern-form-actions .button-primary {
            background: #2271b1;
            border-color: #2271b1;
            padding: 10px 24px;
            font-size: 14px;
            font-weight: 500;
            height: auto;
        }

        .modern-form-actions .button-primary:hover {
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

        /* Counter badge */
        .modern-counter {
            display: inline-block;
            background: #2271b1;
            color: white;
            font-size: 11px;
            font-weight: 600;
            padding: 2px 8px;
            border-radius: 10px;
            margin-left: 8px;
        }

        /* Style Options Section */
        .modern-style-section {
            margin-bottom: 32px;
            padding-bottom: 32px;
            border-bottom: 1px solid #e0e0e0;
        }

        .modern-style-section:last-of-type {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }

        .modern-style-section h3 {
            font-size: 16px;
            font-weight: 600;
            color: #1d2327;
            margin: 0 0 8px 0;
        }

        .modern-style-section .section-description {
            color: #646970;
            font-size: 13px;
            margin: 0 0 20px 0;
        }

        /* Color Scheme Options */
        .modern-color-schemes {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
            gap: 12px;
            margin-bottom: 24px;
        }

        .color-scheme-option {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 16px 12px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s ease;
            background: white;
        }

        .color-scheme-option:hover {
            border-color: #2271b1;
            background: #f8fbfd;
        }

        .color-scheme-option.selected {
            border-color: #2271b1;
            background: #f0f6fc;
        }

        .color-scheme-radio {
            position: absolute;
            opacity: 0;
            pointer-events: none;
        }

        .color-swatch {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            margin-bottom: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        }

        .color-name {
            font-size: 13px;
            font-weight: 500;
            color: #1d2327;
            text-align: center;
        }

        /* Custom Color Picker */
        .custom-color-picker {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-top: 20px;
        }

        .custom-color-picker label {
            display: block;
            margin-bottom: 12px;
        }

        .custom-color-picker .color-hint {
            display: block;
            font-size: 12px;
            color: #646970;
            font-weight: normal;
            margin-top: 4px;
        }

        .color-input-wrapper {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .color-picker-input {
            width: 60px;
            height: 40px;
            padding: 0;
            border: 2px solid #e0e0e0;
            border-radius: 6px;
            cursor: pointer;
            background: none;
        }

        .color-picker-input::-webkit-color-swatch-wrapper {
            padding: 2px;
        }

        .color-picker-input::-webkit-color-swatch {
            border: none;
            border-radius: 4px;
        }

        .color-hex-input {
            width: 100px;
            padding: 8px 12px;
            border: 1px solid #e0e0e0;
            border-radius: 6px;
            font-family: monospace;
            font-size: 14px;
        }

        /* Style Options List */
        .modern-style-options {
            background: white;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            overflow: hidden;
        }

        .style-option-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px 20px;
            border-bottom: 1px solid #f0f0f1;
        }

        .style-option-item:last-child {
            border-bottom: none;
        }

        .style-option-info h4 {
            margin: 0 0 4px 0;
            font-size: 14px;
            font-weight: 600;
            color: #1d2327;
        }

        .style-option-info p {
            margin: 0;
            font-size: 13px;
            color: #646970;
        }
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

        $options = get_option('modern_admin_ui_settings', array());
        $pages = $this->get_settings_pages();
        $enable_all = isset($options['enable_all_settings']) ? $options['enable_all_settings'] : 0;

        // Count enabled
        $enabled_count = 0;
        foreach (array_keys($pages) as $key) {
            if (isset($options[$key]) && $options[$key]) {
                $enabled_count++;
            }
        }
        ?>
        <div class="wrap modern-admin-ui-wrap">
            <div class="modern-admin-header">
                <h1>Modern Admin UI Settings</h1>
                <p>Control which WordPress admin pages use the modern interface</p>
            </div>

            <?php settings_errors('modern_admin_ui_messages'); ?>

            <div class="modern-admin-ui-content">
                <!-- Tab Navigation -->
                <div class="modern-settings-tabs">
                    <button type="button" class="modern-settings-tab active" data-tab="pages">
                        Admin Pages <span class="modern-counter"><?php echo $enabled_count; ?>/<?php echo count($pages); ?></span>
                    </button>
                    <button type="button" class="modern-settings-tab" data-tab="styles">
                        Style Options
                    </button>
                    <button type="button" class="modern-settings-tab" data-tab="about">
                        About
                    </button>
                </div>

                <form method="post" action="options.php">
                    <?php settings_fields('modern_admin_ui_options'); ?>

                    <!-- Tab 1: Admin Pages -->
                    <div class="modern-settings-panel active" data-tab="pages">
                        <!-- Master Toggle -->
                        <div class="modern-master-toggle">
                            <div>
                                <h3>Enable All Modern UI</h3>
                                <p>Transform all supported admin pages with one click</p>
                            </div>
                            <div class="modern-toggle-wrapper">
                                <label class="modern-toggle">
                                    <input type="checkbox"
                                           id="enable_all_settings"
                                           name="modern_admin_ui_settings[enable_all_settings]"
                                           value="1"
                                           <?php checked(1, $enable_all); ?>
                                           class="modern-toggle-checkbox">
                                    <span class="modern-toggle-slider"></span>
                                </label>
                                <span class="modern-toggle-label"><?php echo $enable_all ? 'All Enabled' : 'Disabled'; ?></span>
                            </div>
                        </div>

                        <!-- Individual Settings -->
                        <div class="modern-settings-list">
                            <?php foreach ($pages as $key => $page) :
                                $value = isset($options[$key]) ? $options[$key] : 0;
                            ?>
                            <div class="modern-setting-item">
                                <div class="modern-setting-info">
                                    <h4><?php echo esc_html($page['title']); ?></h4>
                                    <p><?php echo esc_html($page['description']); ?></p>
                                </div>
                                <div class="modern-toggle-wrapper">
                                    <label class="modern-toggle">
                                        <input type="checkbox"
                                               id="<?php echo esc_attr($key); ?>"
                                               name="modern_admin_ui_settings[<?php echo esc_attr($key); ?>]"
                                               value="1"
                                               <?php checked(1, $value); ?>
                                               class="modern-toggle-checkbox">
                                        <span class="modern-toggle-slider"></span>
                                    </label>
                                    <span class="modern-toggle-label"><?php echo $value ? 'Enabled' : 'Disabled'; ?></span>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>

                        <div class="modern-form-actions">
                            <?php submit_button('Save Settings', 'primary', 'submit', false); ?>
                        </div>
                    </div>

                    <!-- Tab 2: Style Options -->
                    <div class="modern-settings-panel" data-tab="styles">
                        <?php
                        $style_defaults = $this->get_default_styles();
                        $color_scheme = isset($options['color_scheme']) ? $options['color_scheme'] : $style_defaults['color_scheme'];
                        $primary_color = isset($options['primary_color']) ? $options['primary_color'] : $style_defaults['primary_color'];
                        $rounded_corners = isset($options['rounded_corners']) ? $options['rounded_corners'] : $style_defaults['rounded_corners'];
                        $compact_mode = isset($options['compact_mode']) ? $options['compact_mode'] : $style_defaults['compact_mode'];
                        $smooth_animations = isset($options['smooth_animations']) ? $options['smooth_animations'] : $style_defaults['smooth_animations'];
                        $modern_buttons = isset($options['modern_buttons']) ? $options['modern_buttons'] : $style_defaults['modern_buttons'];
                        $enhanced_focus = isset($options['enhanced_focus']) ? $options['enhanced_focus'] : $style_defaults['enhanced_focus'];
                        ?>

                        <!-- Color Scheme Section -->
                        <div class="modern-style-section">
                            <h3>Color Scheme</h3>
                            <p class="section-description">Choose a preset color scheme or customize your own primary color.</p>

                            <div class="modern-color-schemes">
                                <?php
                                $schemes = array(
                                    'default' => array('name' => 'Default', 'color' => '#2271b1'),
                                    'blue' => array('name' => 'Ocean Blue', 'color' => '#0073aa'),
                                    'green' => array('name' => 'Forest Green', 'color' => '#00a32a'),
                                    'purple' => array('name' => 'Royal Purple', 'color' => '#7c3aed'),
                                    'orange' => array('name' => 'Sunset Orange', 'color' => '#d97706'),
                                    'dark' => array('name' => 'Dark Mode', 'color' => '#1e293b')
                                );
                                foreach ($schemes as $key => $scheme) :
                                ?>
                                <label class="color-scheme-option <?php echo $color_scheme === $key ? 'selected' : ''; ?>">
                                    <input type="radio"
                                           name="modern_admin_ui_settings[color_scheme]"
                                           value="<?php echo esc_attr($key); ?>"
                                           <?php checked($color_scheme, $key); ?>
                                           class="color-scheme-radio">
                                    <span class="color-swatch" style="background-color: <?php echo esc_attr($scheme['color']); ?>;"></span>
                                    <span class="color-name"><?php echo esc_html($scheme['name']); ?></span>
                                </label>
                                <?php endforeach; ?>
                            </div>

                            <div class="custom-color-picker" id="custom-color-section">
                                <label for="primary_color">
                                    <strong>Custom Primary Color</strong>
                                    <span class="color-hint">(Only applies when "Default" scheme is selected)</span>
                                </label>
                                <div class="color-input-wrapper">
                                    <input type="color"
                                           id="primary_color"
                                           name="modern_admin_ui_settings[primary_color]"
                                           value="<?php echo esc_attr($primary_color); ?>"
                                           class="color-picker-input">
                                    <input type="text"
                                           id="primary_color_hex"
                                           value="<?php echo esc_attr($primary_color); ?>"
                                           class="color-hex-input"
                                           pattern="^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$"
                                           placeholder="#2271b1">
                                </div>
                            </div>
                        </div>

                        <!-- UI Options Section -->
                        <div class="modern-style-section">
                            <h3>UI Options</h3>
                            <p class="section-description">Customize the appearance and behavior of the modern interface.</p>

                            <div class="modern-style-options">
                                <div class="style-option-item">
                                    <div class="style-option-info">
                                        <h4>Rounded Corners</h4>
                                        <p>Apply rounded corners to cards, buttons, and form elements</p>
                                    </div>
                                    <div class="modern-toggle-wrapper">
                                        <label class="modern-toggle">
                                            <input type="checkbox"
                                                   name="modern_admin_ui_settings[rounded_corners]"
                                                   value="1"
                                                   <?php checked(1, $rounded_corners); ?>
                                                   class="modern-toggle-checkbox">
                                            <span class="modern-toggle-slider"></span>
                                        </label>
                                        <span class="modern-toggle-label"><?php echo $rounded_corners ? 'Enabled' : 'Disabled'; ?></span>
                                    </div>
                                </div>

                                <div class="style-option-item">
                                    <div class="style-option-info">
                                        <h4>Compact Mode</h4>
                                        <p>Reduce padding and spacing for a more compact view</p>
                                    </div>
                                    <div class="modern-toggle-wrapper">
                                        <label class="modern-toggle">
                                            <input type="checkbox"
                                                   name="modern_admin_ui_settings[compact_mode]"
                                                   value="1"
                                                   <?php checked(1, $compact_mode); ?>
                                                   class="modern-toggle-checkbox">
                                            <span class="modern-toggle-slider"></span>
                                        </label>
                                        <span class="modern-toggle-label"><?php echo $compact_mode ? 'Enabled' : 'Disabled'; ?></span>
                                    </div>
                                </div>

                                <div class="style-option-item">
                                    <div class="style-option-info">
                                        <h4>Smooth Animations</h4>
                                        <p>Enable smooth transitions and animations throughout the UI</p>
                                    </div>
                                    <div class="modern-toggle-wrapper">
                                        <label class="modern-toggle">
                                            <input type="checkbox"
                                                   name="modern_admin_ui_settings[smooth_animations]"
                                                   value="1"
                                                   <?php checked(1, $smooth_animations); ?>
                                                   class="modern-toggle-checkbox">
                                            <span class="modern-toggle-slider"></span>
                                        </label>
                                        <span class="modern-toggle-label"><?php echo $smooth_animations ? 'Enabled' : 'Disabled'; ?></span>
                                    </div>
                                </div>

                                <div class="style-option-item">
                                    <div class="style-option-info">
                                        <h4>Modern Buttons</h4>
                                        <p>Apply modern styling to buttons with your chosen color scheme</p>
                                    </div>
                                    <div class="modern-toggle-wrapper">
                                        <label class="modern-toggle">
                                            <input type="checkbox"
                                                   name="modern_admin_ui_settings[modern_buttons]"
                                                   value="1"
                                                   <?php checked(1, $modern_buttons); ?>
                                                   class="modern-toggle-checkbox">
                                            <span class="modern-toggle-slider"></span>
                                        </label>
                                        <span class="modern-toggle-label"><?php echo $modern_buttons ? 'Enabled' : 'Disabled'; ?></span>
                                    </div>
                                </div>

                                <div class="style-option-item">
                                    <div class="style-option-info">
                                        <h4>Enhanced Focus States</h4>
                                        <p>Improve visibility of focused form elements for better accessibility</p>
                                    </div>
                                    <div class="modern-toggle-wrapper">
                                        <label class="modern-toggle">
                                            <input type="checkbox"
                                                   name="modern_admin_ui_settings[enhanced_focus]"
                                                   value="1"
                                                   <?php checked(1, $enhanced_focus); ?>
                                                   class="modern-toggle-checkbox">
                                            <span class="modern-toggle-slider"></span>
                                        </label>
                                        <span class="modern-toggle-label"><?php echo $enhanced_focus ? 'Enabled' : 'Disabled'; ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modern-info-box">
                            <h3>Live Preview</h3>
                            <p>Changes will apply to all modernized admin pages after saving. The styles affect buttons, form elements, tabs, and info boxes throughout the WordPress admin.</p>
                        </div>

                        <div class="modern-form-actions">
                            <?php submit_button('Save Style Settings', 'primary', 'submit', false); ?>
                        </div>
                    </div>

                    <!-- Tab 3: About -->
                    <div class="modern-settings-panel" data-tab="about">
                        <div class="modern-info-box" style="margin-top:0;">
                            <h3>What does this plugin do?</h3>
                            <p>Modern Admin UI transforms WordPress admin pages with:</p>
                            <ul>
                                <li>Clean, organized tabbed interfaces</li>
                                <li>Improved visual hierarchy and spacing</li>
                                <li>Modern form styling with better focus states</li>
                                <li>Responsive design that works on all devices</li>
                            </ul>
                            <p style="margin-top: 12px;"><strong>Note:</strong> All WordPress functionality is preserved - we only improve the visual design and user experience.</p>
                        </div>

                        <div class="modern-info-box">
                            <h3>Plugin Information</h3>
                            <p><strong>Version:</strong> <?php echo MODERN_ADMIN_UI_VERSION; ?></p>
                            <p><strong>Author:</strong> JBMD Creations</p>
                            <p><strong>Developed for:</strong> Bernadette Smith for U.S. Senate Campaign</p>
                        </div>
                    </div>
                </form>
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

        // Load Writing Settings modernizer if enabled
        if (isset($options['enable_writing_settings']) && $options['enable_writing_settings']) {
            require_once MODERN_ADMIN_UI_PATH . 'includes/class-writing-settings.php';
            new Modern_Writing_Settings();
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

        // Load List Tables modernizer if enabled
        if (isset($options['enable_list_tables']) && $options['enable_list_tables']) {
            require_once MODERN_ADMIN_UI_PATH . 'includes/class-list-tables.php';
            new Modern_List_Tables();
        }

        // Load Profile Page modernizer if enabled
        if (isset($options['enable_profile_page']) && $options['enable_profile_page']) {
            require_once MODERN_ADMIN_UI_PATH . 'includes/class-profile-settings.php';
            new Modern_Profile_Settings();
        }

        // Load Admin Menu modernizer if enabled
        if (isset($options['enable_admin_menu']) && $options['enable_admin_menu']) {
            require_once MODERN_ADMIN_UI_PATH . 'includes/class-admin-menu.php';
            new Modern_Admin_Menu();
        }
    }
}

// Initialize the plugin
function modern_admin_ui_init() {
    return Modern_Admin_UI_Plugin::get_instance();
}

// Start the plugin
add_action('plugins_loaded', 'modern_admin_ui_init');
