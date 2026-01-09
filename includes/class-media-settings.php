<?php
/**
 * Modern Media Settings Page
 *
 * Transforms the WordPress Media Settings page with a modern tabbed interface
 *
 * @package Modern_Admin_UI
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class Modern_Media_Settings {

    public function __construct() {
        add_action('admin_head-options-media.php', array($this, 'enqueue_styles'));
        add_action('admin_footer-options-media.php', array($this, 'enqueue_scripts'));
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

            /* Size Cards */
            .modern-size-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
                gap: 24px;
            }

            .modern-size-card {
                background: #f6f7f7;
                border-radius: 8px;
                padding: 24px;
                transition: all 0.2s ease;
            }

            .modern-size-card:hover {
                background: #f0f0f1;
            }

            .modern-size-card h3 {
                margin: 0 0 16px 0;
                font-size: 15px;
                font-weight: 600;
                color: #1d2327;
                display: flex;
                align-items: center;
                gap: 8px;
            }

            .modern-size-card .size-icon {
                width: 32px;
                height: 32px;
                background: #2271b1;
                border-radius: 6px;
                display: flex;
                align-items: center;
                justify-content: center;
                color: white;
                font-size: 14px;
                font-weight: 600;
            }

            .modern-size-card .size-icon.medium {
                background: #00a32a;
            }

            .modern-size-card .size-icon.large {
                background: #dba617;
            }

            .modern-size-inputs {
                display: flex;
                gap: 16px;
                align-items: center;
            }

            .modern-size-input-group {
                flex: 1;
            }

            .modern-size-input-group label {
                display: block;
                font-size: 13px;
                font-weight: 500;
                color: #646970;
                margin-bottom: 6px;
            }

            .modern-size-input-group input {
                width: 100%;
                padding: 10px 14px;
                border: 1px solid #8c8f94;
                border-radius: 4px;
                font-size: 14px;
                transition: border-color 0.2s ease;
            }

            .modern-size-input-group input:focus {
                border-color: #2271b1;
                box-shadow: 0 0 0 1px #2271b1;
                outline: none;
            }

            .modern-size-separator {
                font-size: 18px;
                color: #8c8f94;
                padding-top: 24px;
            }

            /* Checkbox Fields */
            .modern-checkbox-field {
                display: flex;
                align-items: flex-start;
                gap: 10px;
                padding: 16px;
                background: #f6f7f7;
                border-radius: 6px;
                margin-top: 16px;
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
                font-size: 13px;
                color: #646970;
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

            /* Preview Box */
            .modern-preview-box {
                margin-top: 24px;
                padding: 20px;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                border-radius: 8px;
                color: white;
            }

            .modern-preview-box h4 {
                margin: 0 0 12px 0;
                font-size: 14px;
                font-weight: 600;
                opacity: 0.9;
            }

            .modern-preview-sizes {
                display: flex;
                gap: 16px;
                flex-wrap: wrap;
            }

            .modern-preview-size {
                background: rgba(255,255,255,0.2);
                border-radius: 6px;
                padding: 12px 16px;
                text-align: center;
            }

            .modern-preview-size .size-name {
                font-size: 12px;
                opacity: 0.8;
                margin-bottom: 4px;
            }

            .modern-preview-size .size-value {
                font-size: 16px;
                font-weight: 600;
            }

            @media (max-width: 768px) {
                .modern-size-grid {
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
                $h1.after('<p class="settings-description" style="color: #646970; font-size: 14px; margin-top: 8px;">Configure image sizes and file upload organization</p>');

                // Create wrapper
                $form.wrap('<div class="modern-settings-wrapper"></div>');
                var $wrapper = $('.modern-settings-wrapper');

                // Move any notices into the wrapper
                $('.notice, .updated, .error').appendTo($wrapper);

                // Create tab navigation
                var $tabNav = $('<div class="modern-tab-navigation"></div>');
                $tabNav.append('<button type="button" class="modern-tab-button active" data-tab="sizes">Image Sizes</button>');
                $tabNav.append('<button type="button" class="modern-tab-button" data-tab="uploads">File Uploads</button>');

                $form.prepend($tabNav);

                // Get values from original form
                var thumbW = $form.find('input[name="thumbnail_size_w"]').val() || '150';
                var thumbH = $form.find('input[name="thumbnail_size_h"]').val() || '150';
                var thumbCrop = $form.find('input[name="thumbnail_crop"]').is(':checked');

                var mediumW = $form.find('input[name="medium_size_w"]').val() || '300';
                var mediumH = $form.find('input[name="medium_size_h"]').val() || '300';

                var largeW = $form.find('input[name="large_size_w"]').val() || '1024';
                var largeH = $form.find('input[name="large_size_h"]').val() || '1024';

                var uploads_use_yearmonth_folders = $form.find('input[name="uploads_use_yearmonth_folders"]').is(':checked');

                // Tab 1: Image Sizes
                var $tab1 = $('<div class="modern-tab-content active" data-tab="sizes"></div>');

                var $section1 = $('<div class="modern-form-section"><h2 class="modern-form-section-title">Image Sizes <span class="info-badge">AUTO-GENERATED</span></h2></div>');
                $section1.append('<p style="color:#646970;margin-bottom:24px;">When you upload images, WordPress automatically creates these additional sizes. Set dimensions to 0 to disable auto-generation for that size.</p>');

                var $sizeGrid = $('<div class="modern-size-grid"></div>');

                // Thumbnail card
                $sizeGrid.append(
                    '<div class="modern-size-card">' +
                    '<h3><span class="size-icon">T</span> Thumbnail</h3>' +
                    '<div class="modern-size-inputs">' +
                    '<div class="modern-size-input-group">' +
                    '<label for="thumbnail_size_w">Width</label>' +
                    '<input type="number" name="thumbnail_size_w" id="thumbnail_size_w" value="' + thumbW + '">' +
                    '</div>' +
                    '<span class="modern-size-separator">×</span>' +
                    '<div class="modern-size-input-group">' +
                    '<label for="thumbnail_size_h">Height</label>' +
                    '<input type="number" name="thumbnail_size_h" id="thumbnail_size_h" value="' + thumbH + '">' +
                    '</div>' +
                    '</div>' +
                    '<div class="modern-checkbox-field" style="margin-top:16px;padding:12px;">' +
                    '<input type="checkbox" name="thumbnail_crop" id="thumbnail_crop" value="1" ' + (thumbCrop ? 'checked' : '') + '>' +
                    '<div><label for="thumbnail_crop">Crop to exact dimensions</label></div>' +
                    '</div>' +
                    '</div>'
                );

                // Medium card
                $sizeGrid.append(
                    '<div class="modern-size-card">' +
                    '<h3><span class="size-icon medium">M</span> Medium</h3>' +
                    '<div class="modern-size-inputs">' +
                    '<div class="modern-size-input-group">' +
                    '<label for="medium_size_w">Max Width</label>' +
                    '<input type="number" name="medium_size_w" id="medium_size_w" value="' + mediumW + '">' +
                    '</div>' +
                    '<span class="modern-size-separator">×</span>' +
                    '<div class="modern-size-input-group">' +
                    '<label for="medium_size_h">Max Height</label>' +
                    '<input type="number" name="medium_size_h" id="medium_size_h" value="' + mediumH + '">' +
                    '</div>' +
                    '</div>' +
                    '</div>'
                );

                // Large card
                $sizeGrid.append(
                    '<div class="modern-size-card">' +
                    '<h3><span class="size-icon large">L</span> Large</h3>' +
                    '<div class="modern-size-inputs">' +
                    '<div class="modern-size-input-group">' +
                    '<label for="large_size_w">Max Width</label>' +
                    '<input type="number" name="large_size_w" id="large_size_w" value="' + largeW + '">' +
                    '</div>' +
                    '<span class="modern-size-separator">×</span>' +
                    '<div class="modern-size-input-group">' +
                    '<label for="large_size_h">Max Height</label>' +
                    '<input type="number" name="large_size_h" id="large_size_h" value="' + largeH + '">' +
                    '</div>' +
                    '</div>' +
                    '</div>'
                );

                $section1.append($sizeGrid);

                // Preview box
                $section1.append(
                    '<div class="modern-preview-box">' +
                    '<h4>Current Size Configuration</h4>' +
                    '<div class="modern-preview-sizes">' +
                    '<div class="modern-preview-size"><div class="size-name">Thumbnail</div><div class="size-value" id="preview-thumb">' + thumbW + '×' + thumbH + '</div></div>' +
                    '<div class="modern-preview-size"><div class="size-name">Medium</div><div class="size-value" id="preview-medium">' + mediumW + '×' + mediumH + '</div></div>' +
                    '<div class="modern-preview-size"><div class="size-name">Large</div><div class="size-value" id="preview-large">' + largeW + '×' + largeH + '</div></div>' +
                    '</div>' +
                    '</div>'
                );

                $tab1.append($section1);

                // Tab 2: File Uploads
                var $tab2 = $('<div class="modern-tab-content" data-tab="uploads"></div>');

                var $section2 = $('<div class="modern-form-section"><h2 class="modern-form-section-title">Uploading Files</h2></div>');

                $section2.append(
                    '<div class="modern-checkbox-field">' +
                    '<input type="checkbox" name="uploads_use_yearmonth_folders" id="uploads_use_yearmonth_folders" value="1" ' + (uploads_use_yearmonth_folders ? 'checked' : '') + '>' +
                    '<div><label for="uploads_use_yearmonth_folders">Organize my uploads into month- and year-based folders</label>' +
                    '<p class="description">When enabled, uploads will be stored in folders like /wp-content/uploads/2024/01/</p></div>' +
                    '</div>'
                );

                $section2.append(
                    '<div class="modern-info-box">' +
                    '<p><strong>Tip:</strong> Organizing uploads by date makes it easier to manage large media libraries and can improve server performance. This is the recommended setting for most sites.</p>' +
                    '</div>'
                );

                $tab2.append($section2);

                // Add form actions to each tab
                var $submitClone = $form.find('.submit').first().clone();
                var $formActions = $('<div class="modern-form-actions"></div>').append($submitClone);

                $tab1.append($formActions.clone());
                $tab2.append($formActions.clone());

                // Remove original content and add tabs
                $form.find('.form-table, h2').remove();
                $form.append($tab1).append($tab2);

                // Tab switching functionality
                $('.modern-tab-button').on('click', function() {
                    var tab = $(this).data('tab');

                    $('.modern-tab-button').removeClass('active');
                    $(this).addClass('active');

                    $('.modern-tab-content').removeClass('active');
                    $('.modern-tab-content[data-tab="' + tab + '"]').addClass('active');
                });

                // Live preview updates
                $('input[name="thumbnail_size_w"], input[name="thumbnail_size_h"]').on('input', function() {
                    var w = $('input[name="thumbnail_size_w"]').val() || '0';
                    var h = $('input[name="thumbnail_size_h"]').val() || '0';
                    $('#preview-thumb').text(w + '×' + h);
                });

                $('input[name="medium_size_w"], input[name="medium_size_h"]').on('input', function() {
                    var w = $('input[name="medium_size_w"]').val() || '0';
                    var h = $('input[name="medium_size_h"]').val() || '0';
                    $('#preview-medium').text(w + '×' + h);
                });

                $('input[name="large_size_w"], input[name="large_size_h"]').on('input', function() {
                    var w = $('input[name="large_size_w"]').val() || '0';
                    var h = $('input[name="large_size_h"]').val() || '0';
                    $('#preview-large').text(w + '×' + h);
                });
            }
        });
        </script>
        <?php
    }
}
