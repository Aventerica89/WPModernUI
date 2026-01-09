<?php
/**
 * Modern Writing Settings Page
 *
 * Transforms the WordPress Writing Settings page with a modern tabbed interface
 *
 * @package Modern_Admin_UI
 * @since 1.2.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class Modern_Writing_Settings {

    public function __construct() {
        add_action('admin_head-options-writing.php', array($this, 'enqueue_styles'));
        add_action('admin_footer-options-writing.php', array($this, 'enqueue_scripts'));
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
            .modern-tab-content input[type="number"],
            .modern-tab-content input[type="password"],
            .modern-tab-content textarea,
            .modern-tab-content select {
                max-width: 500px;
                padding: 10px 14px;
                border: 1px solid #8c8f94;
                border-radius: 4px;
                font-size: 14px;
                transition: border-color 0.2s ease;
            }

            .modern-tab-content textarea {
                width: 100%;
                min-height: 120px;
                font-family: monospace;
            }

            .modern-tab-content input[type="text"]:focus,
            .modern-tab-content input[type="email"]:focus,
            .modern-tab-content input[type="number"]:focus,
            .modern-tab-content input[type="password"]:focus,
            .modern-tab-content textarea:focus,
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

            /* Select Styling */
            .modern-select-wrapper {
                position: relative;
                display: inline-block;
                max-width: 500px;
            }

            .modern-select-wrapper select {
                width: 100%;
                appearance: none;
                padding-right: 40px;
            }

            /* Checkbox Fields */
            .modern-checkbox-field {
                display: flex;
                align-items: flex-start;
                gap: 10px;
                padding: 16px;
                background: #f6f7f7;
                border-radius: 6px;
                margin-bottom: 12px;
            }

            .modern-checkbox-field:last-child {
                margin-bottom: 0;
            }

            .modern-checkbox-field input[type="checkbox"] {
                width: 18px;
                height: 18px;
                margin-top: 2px;
            }

            .modern-checkbox-field label {
                font-weight: 500;
                color: #1d2327;
            }

            .modern-checkbox-field .description {
                margin-top: 4px;
            }

            /* Field Groups */
            .modern-field-group {
                background: #f6f7f7;
                border-radius: 8px;
                padding: 20px;
                margin-bottom: 16px;
            }

            .modern-field-group:last-child {
                margin-bottom: 0;
            }

            .modern-field-group label {
                display: block;
                font-weight: 600;
                color: #1d2327;
                margin-bottom: 8px;
            }

            .modern-field-group select,
            .modern-field-group input {
                width: 100%;
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

            /* Info Box */
            .modern-info-box {
                background: #f0f6fc;
                border-left: 4px solid #2271b1;
                padding: 16px;
                border-radius: 6px;
                margin-top: 16px;
            }

            .modern-info-box p {
                margin: 0;
                font-size: 13px;
                color: #1d2327;
            }

            /* Grid Layout */
            .modern-grid-2col {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 20px;
            }

            @media (max-width: 768px) {
                .modern-grid-2col {
                    grid-template-columns: 1fr;
                }

                .modern-tab-navigation {
                    overflow-x: auto;
                }
            }

            /* Hide original submit button */
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
            initModernSettings();

            function initModernSettings() {
                var $form = $('form[action="options.php"]');
                if ($form.length === 0) return;

                // Add description to h1
                var $h1 = $('#wpbody-content .wrap > h1').first();
                $h1.after('<p class="settings-description" style="color: #646970; font-size: 14px; margin-top: 8px;">Configure default post settings and publishing options</p>');

                // Create wrapper
                $form.wrap('<div class="modern-settings-wrapper"></div>');
                var $wrapper = $('.modern-settings-wrapper');

                // Move any notices into the wrapper
                $('.notice, .updated, .error').appendTo($wrapper);

                // Create tab navigation
                var $tabNav = $('<div class="modern-tab-navigation"></div>');
                $tabNav.append('<button type="button" class="modern-tab-button active" data-tab="defaults">Post Defaults</button>');
                $tabNav.append('<button type="button" class="modern-tab-button" data-tab="formatting">Formatting</button>');
                $tabNav.append('<button type="button" class="modern-tab-button" data-tab="services">Update Services</button>');

                $form.prepend($tabNav);

                // Get original table
                var $table = $form.find('.form-table').first();

                // Tab 1: Post Defaults
                var $tab1 = $('<div class="modern-tab-content active" data-tab="defaults"></div>');

                var $section1 = $('<div class="modern-form-section"><h2 class="modern-form-section-title">Default Post Settings</h2></div>');

                var $grid = $('<div class="modern-grid-2col"></div>');

                // Default Category
                var $categorySelect = $form.find('select[name="default_category"]').clone();
                $grid.append(
                    '<div class="modern-field-group">' +
                    '<label for="default_category">Default Post Category</label>' +
                    '<div id="category-select-wrapper"></div>' +
                    '<p class="description">New posts will be assigned to this category by default</p>' +
                    '</div>'
                );
                $grid.find('#category-select-wrapper').append($categorySelect);

                // Default Post Format (if available)
                var $formatSelect = $form.find('select[name="default_post_format"]');
                if ($formatSelect.length) {
                    var $formatClone = $formatSelect.clone();
                    $grid.append(
                        '<div class="modern-field-group">' +
                        '<label for="default_post_format">Default Post Format</label>' +
                        '<div id="format-select-wrapper"></div>' +
                        '<p class="description">The format applied to new posts by default</p>' +
                        '</div>'
                    );
                    $grid.find('#format-select-wrapper').append($formatClone);
                }

                $section1.append($grid);
                $tab1.append($section1);

                // Post via Email section (if visible)
                var $emailSection = $form.find('h2:contains("Post via email")').next('.form-table');
                if ($emailSection.length) {
                    var $section2 = $('<div class="modern-form-section"><h2 class="modern-form-section-title">Post via Email <span class="info-badge">ADVANCED</span></h2></div>');
                    $section2.append('<p style="color:#646970;margin-bottom:16px;">Configure settings to post to your blog by sending an email.</p>');

                    var $emailGrid = $('<div class="modern-grid-2col"></div>');

                    // Mail Server
                    var mailServer = $form.find('input[name="mailserver_url"]').val() || '';
                    var mailPort = $form.find('input[name="mailserver_port"]').val() || '';
                    $emailGrid.append(
                        '<div class="modern-field-group">' +
                        '<label for="mailserver_url">Mail Server</label>' +
                        '<input type="text" name="mailserver_url" id="mailserver_url" value="' + mailServer + '" placeholder="mail.example.com">' +
                        '<p class="description">Port: <input type="number" name="mailserver_port" value="' + mailPort + '" style="width:80px;padding:5px 10px;margin-left:4px;"></p>' +
                        '</div>'
                    );

                    // Login
                    var mailLogin = $form.find('input[name="mailserver_login"]').val() || '';
                    $emailGrid.append(
                        '<div class="modern-field-group">' +
                        '<label for="mailserver_login">Login Name</label>' +
                        '<input type="text" name="mailserver_login" id="mailserver_login" value="' + mailLogin + '">' +
                        '</div>'
                    );

                    // Password
                    var mailPass = $form.find('input[name="mailserver_pass"]').val() || '';
                    $emailGrid.append(
                        '<div class="modern-field-group">' +
                        '<label for="mailserver_pass">Password</label>' +
                        '<input type="password" name="mailserver_pass" id="mailserver_pass" value="' + mailPass + '">' +
                        '</div>'
                    );

                    // Default Category for email posts
                    var $emailCatSelect = $form.find('select[name="default_email_category"]').clone();
                    if ($emailCatSelect.length) {
                        $emailGrid.append(
                            '<div class="modern-field-group">' +
                            '<label for="default_email_category">Default Email Category</label>' +
                            '<div id="email-category-wrapper"></div>' +
                            '</div>'
                        );
                        $emailGrid.find('#email-category-wrapper').append($emailCatSelect);
                    }

                    $section2.append($emailGrid);
                    $tab1.append($section2);
                }

                // Tab 2: Formatting
                var $tab2 = $('<div class="modern-tab-content" data-tab="formatting"></div>');

                var $section3 = $('<div class="modern-form-section"><h2 class="modern-form-section-title">Formatting Options</h2></div>');

                // Convert emoticons checkbox
                var emoticonChecked = $form.find('input[name="use_smilies"]').is(':checked');
                $section3.append(
                    '<div class="modern-checkbox-field">' +
                    '<input type="checkbox" name="use_smilies" id="use_smilies" value="1" ' + (emoticonChecked ? 'checked' : '') + '>' +
                    '<div><label for="use_smilies">Convert emoticons like :-) and :-P to graphics</label>' +
                    '<p class="description">Automatically convert text emoticons to emoji images in your posts</p></div>' +
                    '</div>'
                );

                // Auto-paragraph checkbox
                var balanceChecked = $form.find('input[name="use_balanceTags"]').is(':checked');
                $section3.append(
                    '<div class="modern-checkbox-field">' +
                    '<input type="checkbox" name="use_balanceTags" id="use_balanceTags" value="1" ' + (balanceChecked ? 'checked' : '') + '>' +
                    '<div><label for="use_balanceTags">WordPress should correct invalidly nested XHTML automatically</label>' +
                    '<p class="description">Fix common HTML errors in your posts</p></div>' +
                    '</div>'
                );

                $tab2.append($section3);

                // Tab 3: Update Services
                var $tab3 = $('<div class="modern-tab-content" data-tab="services"></div>');

                var $section4 = $('<div class="modern-form-section"><h2 class="modern-form-section-title">Update Services</h2></div>');
                $section4.append('<p style="color:#646970;margin-bottom:16px;">When you publish a new post, WordPress automatically notifies the following site update services:</p>');

                var pingServices = $form.find('textarea[name="ping_sites"]').val() || '';
                $section4.append(
                    '<textarea name="ping_sites" id="ping_sites" style="width:100%;max-width:600px;min-height:150px;">' + pingServices + '</textarea>' +
                    '<p class="description">Enter one service URL per line. These services will be pinged each time you publish a new post.</p>'
                );

                $section4.append(
                    '<div class="modern-info-box">' +
                    '<p><strong>Note:</strong> Update services notify search engines and directories when you publish new content. The default service (<code>http://rpc.pingomatic.com/</code>) handles most major services automatically.</p>' +
                    '</div>'
                );

                $tab3.append($section4);

                // Add form actions to each tab
                var $submitClone = $form.find('.submit').first().clone();
                var $formActions = $('<div class="modern-form-actions"></div>').append($submitClone);

                $tab1.append($formActions.clone());
                $tab2.append($formActions.clone());
                $tab3.append($formActions.clone());

                // Remove original content and add tabs
                $form.find('.form-table, h2').remove();
                $form.append($tab1).append($tab2).append($tab3);

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
