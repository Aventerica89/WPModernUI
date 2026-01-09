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

        return $sanitized;
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

                    <!-- Tab 2: Style Options (placeholder for future) -->
                    <div class="modern-settings-panel" data-tab="styles">
                        <div class="modern-info-box" style="margin-top:0;">
                            <h3>Coming Soon</h3>
                            <p>Style customization options will be available in a future update. You'll be able to customize colors, fonts, and other visual elements.</p>
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
    }
}

// Initialize the plugin
function modern_admin_ui_init() {
    return Modern_Admin_UI_Plugin::get_instance();
}

// Start the plugin
add_action('plugins_loaded', 'modern_admin_ui_init');
