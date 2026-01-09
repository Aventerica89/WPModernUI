<?php
/**
 * Modern General Settings Page
 * 
 * Transforms the WordPress General Settings page with a modern tabbed interface
 * 
 * @package Modern_Admin_UI
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class Modern_General_Settings {
    
    public function __construct() {
        add_action('admin_head-options-general.php', array($this, 'enqueue_styles'));
        add_action('admin_footer-options-general.php', array($this, 'enqueue_scripts'));
    }
    
    /**
     * Enqueue custom CSS for the settings page
     */
    public function enqueue_styles() {
        ?>
        <style>
            /* Reset and Base Styles */
            #wpbody-content .wrap > h1 {
                font-size: 28px;
                font-weight: 400;
                margin-bottom: 8px;
            }
            
            #wpbody-content .wrap > h1:after {
                content: '';
                display: block;
                font-size: 14px;
                color: #646970;
                font-weight: 400;
                margin-top: 8px;
            }
            
            /* Settings Container */
            .modern-settings-wrapper {
                background: white;
                border-radius: 8px;
                box-shadow: 0 1px 3px rgba(0,0,0,0.1);
                margin-top: 20px;
            }
            
            /* Success Notice Styling */
            .modern-settings-wrapper .updated,
            .modern-settings-wrapper .notice-success {
                margin: 20px 32px 0 !important;
                padding: 12px 16px !important;
                background: #d7f1e6 !important;
                border-left: 4px solid #00a32a !important;
                border-radius: 4px !important;
            }
            
            /* Tab Navigation */
            .modern-tab-navigation {
                display: flex;
                border-bottom: 1px solid #e0e0e0;
                padding: 0 32px;
                margin: 20px 0 0 0;
                gap: 8px;
                flex-wrap: wrap;
            }
            
            .modern-tab-button {
                padding: 14px 24px;
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
            
            .modern-tab-button:hover {
                color: #2271b1;
            }
            
            .modern-tab-button.active {
                color: #2271b1;
                border-bottom-color: #2271b1;
            }
            
            /* Tab Content */
            .modern-tab-content {
                display: none;
                padding: 32px;
                animation: fadeIn 0.3s ease;
            }
            
            .modern-tab-content.active {
                display: block;
            }
            
            @keyframes fadeIn {
                from { opacity: 0; transform: translateY(-10px); }
                to { opacity: 1; transform: translateY(0); }
            }
            
            /* Form Sections */
            .modern-form-section {
                margin-bottom: 32px;
            }
            
            .modern-form-section:last-child {
                margin-bottom: 0;
            }
            
            .modern-form-section-title {
                font-size: 16px;
                font-weight: 600;
                color: #1d2327;
                margin-bottom: 16px;
                padding-bottom: 8px;
                border-bottom: 1px solid #f0f0f1;
            }
            
            .info-badge {
                display: inline-block;
                padding: 2px 8px;
                background: #f0f6fc;
                color: #0969da;
                border-radius: 12px;
                font-size: 11px;
                font-weight: 600;
                margin-left: 8px;
                vertical-align: middle;
            }
            
            /* Form Fields */
            .modern-tab-content .form-table {
                margin: 0;
            }
            
            .modern-tab-content .form-table th {
                padding: 0 0 8px 0;
                font-weight: 600;
                color: #1d2327;
                font-size: 14px;
                width: auto;
            }
            
            .modern-tab-content .form-table td {
                padding: 0 0 24px 0;
            }
            
            .modern-tab-content input[type="text"],
            .modern-tab-content input[type="email"],
            .modern-tab-content input[type="url"],
            .modern-tab-content select {
                max-width: 500px;
                padding: 10px 14px;
                border: 1px solid #8c8f94;
                border-radius: 4px;
                font-size: 14px;
                transition: border-color 0.2s ease;
            }
            
            .modern-tab-content input[type="text"]:focus,
            .modern-tab-content input[type="email"]:focus,
            .modern-tab-content input[type="url"]:focus,
            .modern-tab-content select:focus {
                border-color: #2271b1;
                box-shadow: 0 0 0 1px #2271b1;
                outline: none;
            }
            
            .modern-tab-content .description {
                margin-top: 6px;
                font-size: 13px;
                color: #646970;
                line-height: 1.5;
                display: block;
            }
            
            /* Site Icon Styling */
            #site-icon-preview {
                display: flex;
                align-items: center;
                gap: 16px;
                padding: 16px;
                background: #f6f7f7;
                border-radius: 6px;
                margin: 8px 0;
                max-width: 500px;
            }
            
            #site-icon-preview .site-icon {
                border-radius: 6px;
                border: 2px solid #ddd;
            }
            
            .button {
                padding: 8px 16px;
                border-radius: 4px;
                font-size: 13px;
                font-weight: 500;
                transition: all 0.2s ease;
            }
            
            /* Checkbox Fields */
            .modern-checkbox-field {
                display: flex;
                align-items: center;
                gap: 10px;
                padding: 12px;
                background: #f6f7f7;
                border-radius: 6px;
                max-width: 500px;
            }
            
            .modern-checkbox-field input[type="checkbox"] {
                width: 18px;
                height: 18px;
                margin: 0;
            }
            
            .modern-checkbox-field label {
                margin: 0;
                padding: 0;
                font-weight: 500;
                display: inline;
            }
            
            /* Form Actions */
            .modern-form-actions {
                margin-top: 32px;
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
            
            /* Time Preview Box */
            .time-preview-box {
                margin-top: 24px;
                padding: 16px;
                background: #f6f7f7;
                border-radius: 6px;
                border-left: 4px solid #2271b1;
            }
            
            .time-preview-box p {
                font-size: 13px;
                color: #1d2327;
                margin: 4px 0;
            }
            
            .time-preview-box strong {
                color: #1d2327;
            }
            
            /* Grid Layout for Date/Time */
            .modern-grid-2col {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 24px;
            }
            
            @media (max-width: 768px) {
                .modern-grid-2col {
                    grid-template-columns: 1fr;
                }
                
                .modern-tab-navigation {
                    overflow-x: auto;
                }
            }
            
            /* Hide original submit button (we'll add our own in each tab) */
            .modern-settings-wrapper .submit {
                display: none;
            }
            
            /* Show submit in form actions */
            .modern-form-actions .submit {
                display: block !important;
                margin: 0;
                padding: 0;
            }
        </style>
        <?php
    }
    
    /**
     * Enqueue JavaScript for tab functionality
     */
    public function enqueue_scripts() {
        ?>
        <script>
        jQuery(document).ready(function($) {
            // Initialize the modern settings interface
            initModernSettings();
            
            function initModernSettings() {
                // Wrap the form in our custom container
                var $form = $('form[action="options.php"]');
                if ($form.length === 0) return;
                
                // Add description to h1
                var $h1 = $('#wpbody-content .wrap > h1').first();
                $h1.after('<p class="settings-description" style="color: #646970; font-size: 14px; margin-top: 8px;">Configure your site\'s basic information and preferences</p>');
                
                // Create wrapper
                $form.wrap('<div class="modern-settings-wrapper"></div>');
                var $wrapper = $('.modern-settings-wrapper');
                
                // Move any notices into the wrapper
                $('.notice, .updated, .error').appendTo($wrapper);
                
                // Create tab navigation
                var $tabNav = $('<div class="modern-tab-navigation"></div>');
                $tabNav.append('<button type="button" class="modern-tab-button active" data-tab="identity">Site Identity &amp; URLs</button>');
                $tabNav.append('<button type="button" class="modern-tab-button" data-tab="users">Users &amp; Access</button>');
                $tabNav.append('<button type="button" class="modern-tab-button" data-tab="localization">Localization</button>');
                
                $form.prepend($tabNav);
                
                // Create tab content containers
                var $table = $form.find('.form-table').first();
                
                // Tab 1: Site Identity & URLs
                var $tab1 = $('<div class="modern-tab-content active" data-tab="identity"></div>');
                var $section1 = $('<div class="modern-form-section"><h2 class="modern-form-section-title">Site Identity</h2><table class="form-table" role="presentation"></table></div>');
                $section1.find('table').append($table.find('tr').has('[name="blogname"]').clone());
                $section1.find('table').append($table.find('tr').has('[name="blogdescription"]').clone());
                $section1.find('table').append($table.find('tr').has('[name="site_icon"]').clone());
                $tab1.append($section1);
                
                var $section2 = $('<div class="modern-form-section"><h2 class="modern-form-section-title">Site URLs <span class="info-badge">TECHNICAL</span></h2><table class="form-table" role="presentation"></table></div>');
                $section2.find('table').append($table.find('tr').has('[name="siteurl"]').clone());
                $section2.find('table').append($table.find('tr').has('[name="home"]').clone());
                $tab1.append($section2);
                
                // Tab 2: Users & Access
                var $tab2 = $('<div class="modern-tab-content" data-tab="users"></div>');
                var $section3 = $('<div class="modern-form-section"><h2 class="modern-form-section-title">Administration</h2><table class="form-table" role="presentation"></table></div>');
                $section3.find('table').append($table.find('tr').has('[name="new_admin_email"], [name="admin_email"]').clone());
                $tab2.append($section3);
                
                var $section4 = $('<div class="modern-form-section"><h2 class="modern-form-section-title">User Registration</h2><table class="form-table" role="presentation"></table></div>');
                $section4.find('table').append($table.find('tr').has('[name="users_can_register"]').clone());
                $section4.find('table').append($table.find('tr').has('[name="default_role"]').clone());
                
                // Wrap checkbox in modern styling
                var $checkbox = $section4.find('input[name="users_can_register"]');
                if ($checkbox.length) {
                    var $label = $checkbox.closest('label');
                    $label.wrap('<div class="modern-checkbox-field"></div>');
                }
                $tab2.append($section4);
                
                // Tab 3: Localization
                var $tab3 = $('<div class="modern-tab-content" data-tab="localization"></div>');
                var $section5 = $('<div class="modern-form-section"><h2 class="modern-form-section-title">Language &amp; Location</h2><table class="form-table" role="presentation"></table></div>');
                $section5.find('table').append($table.find('tr').has('[name="WPLANG"]').clone());
                $section5.find('table').append($table.find('tr').has('[name="timezone_string"]').clone());
                $tab3.append($section5);
                
                var $section6 = $('<div class="modern-form-section"><h2 class="modern-form-section-title">Date &amp; Time Formats</h2></div>');
                
                // Create grid for date/time
                var $grid = $('<div class="modern-grid-2col"></div>');
                
                var $dateCol = $('<div><table class="form-table" role="presentation"></table></div>');
                $dateCol.find('table').append($table.find('tr').has('[name="date_format"]').clone());
                $grid.append($dateCol);
                
                var $timeCol = $('<div><table class="form-table" role="presentation"></table></div>');
                $timeCol.find('table').append($table.find('tr').has('[name="time_format"]').clone());
                $grid.append($timeCol);
                
                $section6.append($grid);
                
                var $weekTable = $('<table class="form-table" role="presentation"></table>');
                $weekTable.append($table.find('tr').has('[name="start_of_week"]').clone());
                $section6.append($weekTable);
                
                // Add time preview
                var timePreview = $table.find('tr:last').text();
                if (timePreview.indexOf('Universal time') > -1) {
                    var $preview = $('<div class="time-preview-box"><p style="font-weight: 600; margin-bottom: 8px;">Current Time Preview</p></div>');
                    var previewText = $table.find('tr:last td').html();
                    $preview.append('<div>' + previewText + '</div>');
                    $section6.append($preview);
                }
                
                $tab3.append($section6);
                
                // Add form actions to each tab
                var $submitClone = $form.find('.submit').first().clone();
                var $formActions = $('<div class="modern-form-actions"></div>').append($submitClone);
                
                $tab1.append($formActions.clone());
                $tab2.append($formActions.clone());
                $tab3.append($formActions.clone());
                
                // Replace original table with tabs
                $table.replaceWith($tab1.add($tab2).add($tab3));
                
                // Tab switching functionality
                $('.modern-tab-button').on('click', function() {
                    var tab = $(this).data('tab');
                    
                    $('.modern-tab-button').removeClass('active');
                    $(this).addClass('active');
                    
                    $('.modern-tab-content').removeClass('active');
                    $('.modern-tab-content[data-tab="' + tab + '"]').addClass('active');
                });
            }
        });
        </script>
        <?php
    }
}
