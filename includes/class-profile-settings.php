<?php
/**
 * Modern Profile Settings
 *
 * Modernizes the WordPress user profile page with a tabbed interface.
 *
 * @package Modern_Admin_UI
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class Modern_Profile_Settings {

    /**
     * Constructor
     */
    public function __construct() {
        add_action('admin_enqueue_scripts', array($this, 'enqueue_styles'));
        add_action('admin_footer', array($this, 'output_scripts'));
    }

    /**
     * Check if we're on the profile page
     */
    private function is_profile_page() {
        global $pagenow;
        return in_array($pagenow, array('profile.php', 'user-edit.php'));
    }

    /**
     * Enqueue styles
     */
    public function enqueue_styles($hook) {
        if (!$this->is_profile_page()) {
            return;
        }

        wp_add_inline_style('wp-admin', $this->get_styles());
    }

    /**
     * Output JavaScript for tab functionality and DOM reorganization
     */
    public function output_scripts() {
        if (!$this->is_profile_page()) {
            return;
        }
        ?>
        <script type="text/javascript">
        jQuery(document).ready(function($) {
            // Add modern class to body
            $('body').addClass('modern-profile-page');

            var $form = $('#your-profile');
            if (!$form.length) return;

            // Create the modern wrapper structure
            var $wrapper = $('<div class="modern-profile-wrapper"></div>');
            var $header = $('<div class="modern-profile-header"><h1>Edit Profile</h1><p>Manage your personal information, preferences, and account security</p></div>');
            var $content = $('<div class="modern-profile-content"></div>');

            // Create tab navigation
            var $tabNav = $('<div class="modern-profile-tabs">' +
                '<button type="button" class="modern-profile-tab active" data-tab="preferences">Preferences</button>' +
                '<button type="button" class="modern-profile-tab" data-tab="identity">Name & Contact</button>' +
                '<button type="button" class="modern-profile-tab" data-tab="about">About You</button>' +
                '<button type="button" class="modern-profile-tab" data-tab="security">Account Security</button>' +
                '</div>');

            // Create tab panels
            var $preferencesPanel = $('<div class="modern-profile-panel active" data-tab="preferences"></div>');
            var $identityPanel = $('<div class="modern-profile-panel" data-tab="identity"></div>');
            var $aboutPanel = $('<div class="modern-profile-panel" data-tab="about"></div>');
            var $securityPanel = $('<div class="modern-profile-panel" data-tab="security"></div>');

            // Find and move sections to appropriate tabs
            var $tables = $form.find('table.form-table');

            // Personal Options section (first table usually)
            var $personalOptions = $form.find('h2:contains("Personal Options"), h3:contains("Personal Options")').first();
            if ($personalOptions.length) {
                var $personalSection = $('<div class="modern-form-section"><h3>Personal Options</h3><p class="section-desc">Customize your WordPress admin experience</p></div>');
                var $personalTable = $personalOptions.next('table.form-table');
                if ($personalTable.length) {
                    $personalSection.append($personalTable.clone());
                    $personalTable.remove();
                }
                $personalOptions.remove();
                $preferencesPanel.append($personalSection);
            }

            // Admin Color Scheme - keep in preferences
            var $colorScheme = $form.find('tr.user-admin-color-wrap, tr:has(#admin_color)');
            // Already handled by personal options table

            // Name section
            var $nameSection = $form.find('h2:contains("Name"), h3:contains("Name")').filter(function() {
                return $(this).text().trim() === 'Name';
            }).first();
            if ($nameSection.length) {
                var $nameFormSection = $('<div class="modern-form-section"><h3>Name</h3><p class="section-desc">How your name appears across the site</p></div>');
                var $nameTable = $nameSection.next('table.form-table');
                if ($nameTable.length) {
                    $nameFormSection.append($nameTable.clone());
                    $nameTable.remove();
                }
                $nameSection.remove();
                $identityPanel.append($nameFormSection);
            }

            // Contact Info section
            var $contactSection = $form.find('h2:contains("Contact Info"), h3:contains("Contact Info")').first();
            if ($contactSection.length) {
                var $contactFormSection = $('<div class="modern-form-section"><h3>Contact Information</h3><p class="section-desc">Your email and website details</p></div>');
                var $contactTable = $contactSection.next('table.form-table');
                if ($contactTable.length) {
                    $contactFormSection.append($contactTable.clone());
                    $contactTable.remove();
                }
                $contactSection.remove();
                $identityPanel.append($contactFormSection);
            }

            // About Yourself section
            var $aboutSection = $form.find('h2:contains("About Yourself"), h3:contains("About Yourself"), h2:contains("About the user"), h3:contains("About the user")').first();
            if ($aboutSection.length) {
                var $aboutFormSection = $('<div class="modern-form-section"><h3>About You</h3><p class="section-desc">Share a bit about yourself</p></div>');
                var $aboutTable = $aboutSection.next('table.form-table');
                if ($aboutTable.length) {
                    $aboutFormSection.append($aboutTable.clone());
                    $aboutTable.remove();
                }
                $aboutSection.remove();
                $aboutPanel.append($aboutFormSection);
            }

            // Profile Picture section
            var $pictureRow = $form.find('tr.user-profile-picture, tr:has(.avatar)').first();
            if ($pictureRow.length) {
                var $pictureSection = $('<div class="modern-form-section modern-avatar-section"><h3>Profile Picture</h3><p class="section-desc">Your avatar displayed on the site</p></div>');
                var $pictureTable = $('<table class="form-table"></table>');
                $pictureTable.append($pictureRow.clone());
                $pictureRow.closest('table').length && $pictureRow.remove();
                $pictureSection.append($pictureTable);
                $aboutPanel.prepend($pictureSection);
            }

            // Account Management section
            var $accountSection = $form.find('h2:contains("Account Management"), h3:contains("Account Management")').first();
            if ($accountSection.length) {
                var $accountFormSection = $('<div class="modern-form-section"><h3>Password</h3><p class="section-desc">Update your password to keep your account secure</p></div>');
                var $accountTable = $accountSection.next('table.form-table');
                if ($accountTable.length) {
                    $accountFormSection.append($accountTable.clone());
                    $accountTable.remove();
                }
                $accountSection.remove();
                $securityPanel.append($accountFormSection);
            }

            // Sessions section
            var $sessionsSection = $form.find('.sessions-section, #sessions-section, h3:contains("Sessions")').first();
            var $sessionsWrapper = $form.find('.destroy-sessions');
            if ($sessionsWrapper.length) {
                var $sessionsFormSection = $('<div class="modern-form-section"><h3>Sessions</h3><p class="section-desc">Manage your active login sessions</p></div>');
                $sessionsFormSection.append($sessionsWrapper.clone());
                $sessionsWrapper.remove();
                $securityPanel.append($sessionsFormSection);
            }

            // Application Passwords section
            var $appPasswords = $form.find('h2:contains("Application Passwords"), h3:contains("Application Passwords"), .application-passwords').first();
            if ($appPasswords.length) {
                var $appSection = $('<div class="modern-form-section"><h3>Application Passwords</h3><p class="section-desc">Manage passwords for external applications</p></div>');
                var $appContent = $appPasswords.nextUntil('h2, h3, .submit');
                $appSection.append($appPasswords.clone());
                $appSection.append($appContent.clone());
                $appPasswords.remove();
                $appContent.remove();
                $securityPanel.append($appSection);
            }

            // Get the submit button
            var $submitBtn = $form.find('input[type="submit"], .submit, p.submit').last();
            var $submitWrapper = $('<div class="modern-form-actions"></div>');
            if ($submitBtn.length) {
                $submitWrapper.append($submitBtn.clone());
                $submitBtn.remove();
            }

            // Add submit button to each panel
            $preferencesPanel.append($submitWrapper.clone());
            $identityPanel.append($submitWrapper.clone());
            $aboutPanel.append($submitWrapper.clone());
            $securityPanel.append($submitWrapper.clone());

            // Hide original page title
            $('.wrap > h1').first().hide();

            // Build the new structure
            $content.append($tabNav);
            $content.append($preferencesPanel);
            $content.append($identityPanel);
            $content.append($aboutPanel);
            $content.append($securityPanel);

            $wrapper.append($header);
            $wrapper.append($content);

            // Insert before the form and move form contents
            $form.before($wrapper);

            // Move remaining form elements and keep form functional
            $form.addClass('modern-profile-form');
            $content.find('.modern-profile-panel').wrapInner('<div class="panel-inner"></div>');

            // Actually, let's restructure - wrap panels in the form
            $form.empty();
            $form.append($content.children());

            // Remove empty elements
            $form.find('h2:empty, h3:empty, table.form-table:empty').remove();

            // Clean up any remaining unwrapped tables
            $form.find('> table.form-table').each(function() {
                var $t = $(this);
                if (!$t.closest('.modern-form-section').length) {
                    $t.wrap('<div class="modern-form-section"></div>');
                }
            });

            // Tab switching functionality
            $(document).on('click', '.modern-profile-tab', function() {
                var tab = $(this).data('tab');
                $('.modern-profile-tab').removeClass('active');
                $(this).addClass('active');
                $('.modern-profile-panel').removeClass('active');
                $('.modern-profile-panel[data-tab="' + tab + '"]').addClass('active');
            });

            // Style enhancements
            $form.find('input[type="text"], input[type="email"], input[type="url"], input[type="password"], textarea, select').addClass('modern-input');
            $form.find('input[type="checkbox"]').addClass('modern-checkbox');
            $form.find('.button-primary, input[type="submit"]').addClass('modern-btn-primary');
            $form.find('.button-secondary, .button:not(.button-primary)').addClass('modern-btn-secondary');

            // Enhance color picker if exists
            $('.color-palette').each(function() {
                $(this).closest('td').addClass('modern-color-options');
            });

        });
        </script>
        <?php
    }

    /**
     * Get CSS styles
     */
    private function get_styles() {
        return "
        /* Modern Profile Page Styles */
        .modern-profile-page .wrap {
            max-width: 1200px;
            margin: 20px auto;
        }

        .modern-profile-page #wpcontent {
            padding-left: 20px;
        }

        /* Header */
        .modern-profile-wrapper {
            margin-bottom: 20px;
        }

        .modern-profile-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 32px;
            border-radius: 12px;
            color: white;
            margin-bottom: 24px;
        }

        .modern-profile-header h1 {
            font-size: 28px;
            font-weight: 600;
            margin: 0 0 8px 0;
            color: white;
        }

        .modern-profile-header p {
            margin: 0;
            opacity: 0.9;
            font-size: 15px;
        }

        /* Content Container */
        .modern-profile-content,
        .modern-profile-form {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            overflow: hidden;
        }

        /* Tab Navigation */
        .modern-profile-tabs {
            display: flex;
            background: #fafafa;
            border-bottom: 1px solid #e0e0e0;
            padding: 0 24px;
            gap: 0;
            overflow-x: auto;
        }

        .modern-profile-tab {
            padding: 16px 24px;
            background: none;
            border: none;
            border-bottom: 3px solid transparent;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            color: #646970;
            transition: all 0.2s ease;
            white-space: nowrap;
            position: relative;
            top: 1px;
        }

        .modern-profile-tab:hover {
            color: #2271b1;
            background: rgba(34, 113, 177, 0.05);
        }

        .modern-profile-tab.active {
            color: #2271b1;
            border-bottom-color: #2271b1;
            background: white;
        }

        /* Tab Panels */
        .modern-profile-panel {
            display: none;
            padding: 32px;
        }

        .modern-profile-panel.active {
            display: block;
        }

        /* Form Sections */
        .modern-form-section {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 24px;
            margin-bottom: 24px;
        }

        .modern-form-section:last-of-type {
            margin-bottom: 0;
        }

        .modern-form-section h3 {
            font-size: 16px;
            font-weight: 600;
            color: #1d2327;
            margin: 0 0 4px 0;
        }

        .modern-form-section .section-desc {
            color: #646970;
            font-size: 13px;
            margin: 0 0 20px 0;
        }

        /* Form Table Styling */
        .modern-profile-page .form-table {
            margin: 0;
        }

        .modern-profile-page .form-table th {
            padding: 12px 16px 12px 0;
            font-weight: 500;
            color: #1d2327;
            font-size: 14px;
            vertical-align: top;
            width: 200px;
        }

        .modern-profile-page .form-table td {
            padding: 12px 0;
        }

        .modern-profile-page .form-table tr {
            border-bottom: 1px solid #e8e8e8;
        }

        .modern-profile-page .form-table tr:last-child {
            border-bottom: none;
        }

        /* Input Styling */
        .modern-profile-page .modern-input,
        .modern-profile-page input[type='text'],
        .modern-profile-page input[type='email'],
        .modern-profile-page input[type='url'],
        .modern-profile-page input[type='password'],
        .modern-profile-page textarea,
        .modern-profile-page select {
            width: 100%;
            max-width: 400px;
            padding: 10px 14px;
            border: 1px solid #e0e0e0;
            border-radius: 6px;
            font-size: 14px;
            transition: all 0.2s ease;
            background: white;
        }

        .modern-profile-page .modern-input:focus,
        .modern-profile-page input[type='text']:focus,
        .modern-profile-page input[type='email']:focus,
        .modern-profile-page input[type='url']:focus,
        .modern-profile-page input[type='password']:focus,
        .modern-profile-page textarea:focus,
        .modern-profile-page select:focus {
            outline: none;
            border-color: #2271b1;
            box-shadow: 0 0 0 2px rgba(34, 113, 177, 0.15);
        }

        .modern-profile-page textarea {
            min-height: 120px;
            max-width: 100%;
        }

        .modern-profile-page .description {
            color: #646970;
            font-size: 13px;
            margin-top: 6px;
        }

        /* Checkbox Styling */
        .modern-profile-page input[type='checkbox'] {
            width: 18px;
            height: 18px;
            border: 2px solid #c3c4c7;
            border-radius: 4px;
            cursor: pointer;
            margin-right: 8px;
            vertical-align: middle;
        }

        .modern-profile-page input[type='checkbox']:checked {
            background: #2271b1;
            border-color: #2271b1;
        }

        /* Admin Color Scheme */
        .modern-profile-page .color-palette {
            display: flex;
            gap: 4px;
            margin-right: 12px;
        }

        .modern-profile-page .color-palette td {
            width: 24px !important;
            height: 24px !important;
            border-radius: 4px;
            padding: 0 !important;
        }

        .modern-profile-page .color-option {
            display: inline-flex;
            align-items: center;
            padding: 8px 16px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            margin-right: 8px;
            margin-bottom: 8px;
            cursor: pointer;
            transition: all 0.2s ease;
            background: white;
        }

        .modern-profile-page .color-option:hover {
            border-color: #2271b1;
        }

        .modern-profile-page .color-option input[type='radio']:checked + .color-palette {
            /* Indicate selected */
        }

        .modern-profile-page .color-option label {
            display: flex;
            align-items: center;
            cursor: pointer;
        }

        .modern-color-options {
            display: flex;
            flex-wrap: wrap;
        }

        /* Avatar Section */
        .modern-avatar-section .avatar {
            border-radius: 50%;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .modern-profile-page tr.user-profile-picture td {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .modern-profile-page tr.user-profile-picture .description {
            margin: 0;
        }

        /* Button Styling */
        .modern-profile-page .button,
        .modern-profile-page input[type='submit'] {
            padding: 10px 20px !important;
            border-radius: 6px !important;
            font-size: 14px !important;
            font-weight: 500 !important;
            height: auto !important;
            line-height: 1.4 !important;
            transition: all 0.2s ease !important;
        }

        .modern-profile-page .button-primary,
        .modern-profile-page .modern-btn-primary,
        .modern-profile-page input[type='submit'] {
            background: #2271b1 !important;
            border-color: #2271b1 !important;
            color: white !important;
        }

        .modern-profile-page .button-primary:hover,
        .modern-profile-page .modern-btn-primary:hover,
        .modern-profile-page input[type='submit']:hover {
            background: #135e96 !important;
            border-color: #135e96 !important;
        }

        .modern-profile-page .button-secondary,
        .modern-profile-page .modern-btn-secondary {
            background: #f0f0f1 !important;
            border: 1px solid #e0e0e0 !important;
            color: #1d2327 !important;
        }

        .modern-profile-page .button-secondary:hover,
        .modern-profile-page .modern-btn-secondary:hover {
            background: #e0e0e0 !important;
        }

        /* Form Actions */
        .modern-form-actions {
            margin-top: 24px;
            padding-top: 24px;
            border-top: 1px solid #e0e0e0;
        }

        .modern-form-actions .submit,
        .modern-form-actions p.submit {
            margin: 0;
            padding: 0;
        }

        /* Password Strength Meter */
        .modern-profile-page #pass-strength-result {
            padding: 8px 12px;
            border-radius: 4px;
            font-size: 13px;
            font-weight: 500;
            margin-top: 8px;
            display: inline-block;
        }

        .modern-profile-page #pass-strength-result.short {
            background: #f8d7da;
            color: #721c24;
        }

        .modern-profile-page #pass-strength-result.bad {
            background: #fff3cd;
            color: #856404;
        }

        .modern-profile-page #pass-strength-result.good {
            background: #d4edda;
            color: #155724;
        }

        .modern-profile-page #pass-strength-result.strong {
            background: #d1e7dd;
            color: #0f5132;
        }

        /* Sessions Section */
        .modern-profile-page .destroy-sessions {
            background: #fff8e5;
            border: 1px solid #f0d58e;
            border-radius: 6px;
            padding: 16px;
            margin-top: 12px;
        }

        .modern-profile-page .destroy-sessions p {
            margin: 0 0 12px 0;
        }

        /* Application Passwords */
        .modern-profile-page .application-passwords {
            margin-top: 16px;
        }

        .modern-profile-page #new-application-password-form {
            background: #f8f9fa;
            padding: 16px;
            border-radius: 6px;
            margin-bottom: 20px;
        }

        .modern-profile-page .application-passwords-list-table {
            margin-top: 16px;
        }

        /* Hide default elements we've replaced */
        .modern-profile-page .wrap > h1:first-of-type {
            display: none !important;
        }

        .modern-profile-page #your-profile > h2,
        .modern-profile-page #your-profile > h3 {
            display: none;
        }

        .modern-profile-page #your-profile > table.form-table:not(.modern-form-section table) {
            display: none;
        }

        /* Responsive Design */
        @media screen and (max-width: 782px) {
            .modern-profile-tabs {
                padding: 0 12px;
            }

            .modern-profile-tab {
                padding: 12px 16px;
                font-size: 13px;
            }

            .modern-profile-panel {
                padding: 20px;
            }

            .modern-form-section {
                padding: 16px;
            }

            .modern-profile-page .form-table th,
            .modern-profile-page .form-table td {
                display: block;
                width: 100%;
                padding: 8px 0;
            }

            .modern-profile-page .form-table th {
                padding-bottom: 4px;
            }

            .modern-profile-page .modern-input,
            .modern-profile-page input[type='text'],
            .modern-profile-page input[type='email'],
            .modern-profile-page input[type='url'],
            .modern-profile-page input[type='password'],
            .modern-profile-page select {
                max-width: 100%;
            }
        }

        /* Username field (readonly styling) */
        .modern-profile-page input[name='user_login'] {
            background: #f0f0f1;
            color: #646970;
            cursor: not-allowed;
        }

        /* Display name select */
        .modern-profile-page select#display_name {
            max-width: 300px;
        }

        /* Language select */
        .modern-profile-page select#locale {
            max-width: 300px;
        }

        /* Remove extra spacing */
        .modern-profile-page #your-profile .form-table + h2,
        .modern-profile-page #your-profile .form-table + h3 {
            margin-top: 0;
        }
        ";
    }
}
