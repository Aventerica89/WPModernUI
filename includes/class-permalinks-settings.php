<?php
/**
 * Modern Permalinks Settings Page
 *
 * Transforms the WordPress Permalinks Settings page with a modern tabbed interface
 *
 * @package Modern_Admin_UI
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class Modern_Permalinks_Settings {

    public function __construct() {
        add_action('admin_head-options-permalink.php', array($this, 'enqueue_styles'));
        add_action('admin_footer-options-permalink.php', array($this, 'enqueue_scripts'));
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

            .recommended-badge {
                background: #d7f1e6;
                color: #00a32a;
            }

            /* Permalink Options */
            .modern-permalink-options {
                display: flex;
                flex-direction: column;
                gap: 12px;
            }

            .modern-permalink-option {
                display: flex;
                align-items: flex-start;
                gap: 16px;
                padding: 16px 20px;
                background: #f6f7f7;
                border-radius: 8px;
                border: 2px solid transparent;
                cursor: pointer;
                transition: all 0.2s ease;
            }

            .modern-permalink-option:hover {
                background: #f0f0f1;
            }

            .modern-permalink-option.selected {
                background: #f0f6fc;
                border-color: #2271b1;
            }

            .modern-permalink-option input[type="radio"] {
                margin-top: 4px;
                width: 18px;
                height: 18px;
            }

            .modern-permalink-option-content {
                flex: 1;
            }

            .modern-permalink-option-content label {
                font-weight: 600;
                color: #1d2327;
                font-size: 14px;
                cursor: pointer;
                display: block;
                margin-bottom: 4px;
            }

            .modern-permalink-option-content .example {
                font-family: monospace;
                font-size: 13px;
                color: #2271b1;
                background: rgba(34, 113, 177, 0.1);
                padding: 4px 8px;
                border-radius: 4px;
                display: inline-block;
                margin-top: 6px;
            }

            .modern-permalink-option-content .description {
                font-size: 13px;
                color: #646970;
                margin-top: 6px;
            }

            /* Custom Structure Input */
            .modern-custom-structure {
                margin-top: 16px;
                padding: 20px;
                background: #fff;
                border: 1px solid #e0e0e0;
                border-radius: 8px;
            }

            .modern-custom-structure label {
                display: block;
                font-weight: 600;
                margin-bottom: 8px;
            }

            .modern-custom-structure input[type="text"] {
                width: 100%;
                max-width: 500px;
                padding: 10px 14px;
                border: 1px solid #8c8f94;
                border-radius: 4px;
                font-family: monospace;
                font-size: 14px;
            }

            .modern-custom-structure input[type="text"]:focus {
                border-color: #2271b1;
                box-shadow: 0 0 0 1px #2271b1;
                outline: none;
            }

            /* Structure Tags */
            .modern-structure-tags {
                margin-top: 16px;
            }

            .modern-structure-tags h4 {
                font-size: 13px;
                font-weight: 600;
                color: #646970;
                margin-bottom: 12px;
            }

            .modern-tag-buttons {
                display: flex;
                flex-wrap: wrap;
                gap: 8px;
            }

            .modern-tag-button {
                padding: 6px 12px;
                background: #f0f0f1;
                border: 1px solid #ddd;
                border-radius: 4px;
                font-family: monospace;
                font-size: 12px;
                color: #1d2327;
                cursor: pointer;
                transition: all 0.2s ease;
            }

            .modern-tag-button:hover {
                background: #2271b1;
                color: white;
                border-color: #2271b1;
            }

            /* Optional Settings */
            .modern-optional-field {
                margin-bottom: 20px;
            }

            .modern-optional-field label {
                display: block;
                font-weight: 600;
                color: #1d2327;
                margin-bottom: 8px;
            }

            .modern-optional-field input[type="text"] {
                width: 100%;
                max-width: 300px;
                padding: 10px 14px;
                border: 1px solid #8c8f94;
                border-radius: 4px;
                font-size: 14px;
            }

            .modern-optional-field input[type="text"]:focus {
                border-color: #2271b1;
                box-shadow: 0 0 0 1px #2271b1;
                outline: none;
            }

            .modern-optional-field .description {
                margin-top: 6px;
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
                margin-top: 24px;
            }

            .modern-info-box p {
                margin: 0;
                font-size: 13px;
                color: #1d2327;
            }

            .modern-info-box code {
                background: rgba(0,0,0,0.05);
                padding: 2px 6px;
                border-radius: 3px;
            }

            /* Warning Box */
            .modern-warning-box {
                background: #fcf9e8;
                border-left: 4px solid #dba617;
                padding: 16px;
                border-radius: 6px;
                margin-top: 16px;
            }

            .modern-warning-box p {
                margin: 0;
                font-size: 13px;
                color: #1d2327;
            }

            @media (max-width: 768px) {
                .modern-tab-navigation {
                    overflow-x: auto;
                }

                .modern-tag-buttons {
                    max-width: 100%;
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
                var $form = $('form#permalink-form, form[action="options-permalink.php"]');
                if ($form.length === 0) return;

                // Add description to h1
                var $h1 = $('#wpbody-content .wrap > h1').first();
                $h1.after('<p class="settings-description" style="color: #646970; font-size: 14px; margin-top: 8px;">Configure how your site URLs are structured for better SEO and usability</p>');

                // Create wrapper
                $form.wrap('<div class="modern-settings-wrapper"></div>');
                var $wrapper = $('.modern-settings-wrapper');

                // Move any notices into the wrapper
                $('.notice, .updated, .error').appendTo($wrapper);

                // Create tab navigation
                var $tabNav = $('<div class="modern-tab-navigation"></div>');
                $tabNav.append('<button type="button" class="modern-tab-button active" data-tab="structure">URL Structure</button>');
                $tabNav.append('<button type="button" class="modern-tab-button" data-tab="optional">Optional Settings</button>');

                $form.prepend($tabNav);

                // Get current permalink structure
                var currentStructure = '';
                $form.find('input[name="selection"]:checked, input[name="permalink_structure"]:checked').each(function() {
                    currentStructure = $(this).val();
                });

                // If custom structure input exists, get its value
                var customStructure = $form.find('input[name="permalink_structure"]').val() || '';

                // Get category and tag bases
                var categoryBase = $form.find('input[name="category_base"]').val() || '';
                var tagBase = $form.find('input[name="tag_base"]').val() || '';

                // Tab 1: URL Structure
                var $tab1 = $('<div class="modern-tab-content active" data-tab="structure"></div>');

                var $section1 = $('<div class="modern-form-section"><h2 class="modern-form-section-title">Permalink Structure</h2></div>');
                $section1.append('<p style="color:#646970;margin-bottom:20px;">Choose how you want your post URLs to be structured. This affects both SEO and user experience.</p>');

                var $options = $('<div class="modern-permalink-options"></div>');

                // Define permalink options
                var structures = [
                    {value: '', label: 'Plain', example: '?p=123', description: 'Default WordPress URLs with query strings'},
                    {value: '/%year%/%monthnum%/%day%/%postname%/', label: 'Day and name', example: '/2024/01/15/sample-post/', description: 'Includes full date in the URL'},
                    {value: '/%year%/%monthnum%/%postname%/', label: 'Month and name', example: '/2024/01/sample-post/', description: 'Includes year and month in the URL'},
                    {value: '/archives/%post_id%', label: 'Numeric', example: '/archives/123', description: 'Simple numeric post IDs'},
                    {value: '/%postname%/', label: 'Post name', example: '/sample-post/', description: 'Clean, SEO-friendly URLs', recommended: true},
                    {value: 'custom', label: 'Custom Structure', example: 'Build your own', description: 'Create a custom URL structure using tags'}
                ];

                structures.forEach(function(struct) {
                    var isSelected = (struct.value === currentStructure) ||
                                   (struct.value === 'custom' && currentStructure && !structures.slice(0, 5).some(function(s) { return s.value === currentStructure; }));
                    var selectedClass = isSelected ? 'selected' : '';
                    var recommendedBadge = struct.recommended ? '<span class="info-badge recommended-badge">RECOMMENDED</span>' : '';

                    $options.append(
                        '<div class="modern-permalink-option ' + selectedClass + '" data-value="' + struct.value + '">' +
                        '<input type="radio" name="selection" id="permalink_' + struct.label.toLowerCase().replace(/\s+/g, '_') + '" value="' + struct.value + '" ' + (isSelected ? 'checked' : '') + '>' +
                        '<div class="modern-permalink-option-content">' +
                        '<label for="permalink_' + struct.label.toLowerCase().replace(/\s+/g, '_') + '">' + struct.label + ' ' + recommendedBadge + '</label>' +
                        '<span class="example">' + struct.example + '</span>' +
                        '<p class="description">' + struct.description + '</p>' +
                        '</div>' +
                        '</div>'
                    );
                });

                $section1.append($options);

                // Custom structure input
                var customValue = customStructure || '/%postname%/';
                $section1.append(
                    '<div class="modern-custom-structure" id="custom-structure-wrapper" style="' + (currentStructure && !structures.slice(0, 5).some(function(s) { return s.value === currentStructure; }) ? '' : 'display:none;') + '">' +
                    '<label for="permalink_structure">Custom Structure</label>' +
                    '<input type="text" name="permalink_structure" id="permalink_structure" value="' + customValue + '">' +
                    '<div class="modern-structure-tags">' +
                    '<h4>Available Tags (click to insert)</h4>' +
                    '<div class="modern-tag-buttons">' +
                    '<button type="button" class="modern-tag-button" data-tag="%year%">%year%</button>' +
                    '<button type="button" class="modern-tag-button" data-tag="%monthnum%">%monthnum%</button>' +
                    '<button type="button" class="modern-tag-button" data-tag="%day%">%day%</button>' +
                    '<button type="button" class="modern-tag-button" data-tag="%hour%">%hour%</button>' +
                    '<button type="button" class="modern-tag-button" data-tag="%minute%">%minute%</button>' +
                    '<button type="button" class="modern-tag-button" data-tag="%second%">%second%</button>' +
                    '<button type="button" class="modern-tag-button" data-tag="%post_id%">%post_id%</button>' +
                    '<button type="button" class="modern-tag-button" data-tag="%postname%">%postname%</button>' +
                    '<button type="button" class="modern-tag-button" data-tag="%category%">%category%</button>' +
                    '<button type="button" class="modern-tag-button" data-tag="%author%">%author%</button>' +
                    '</div>' +
                    '</div>' +
                    '</div>'
                );

                $section1.append(
                    '<div class="modern-info-box">' +
                    '<p><strong>SEO Tip:</strong> The "Post name" structure (<code>/%postname%/</code>) is generally the best choice for SEO as it creates clean, readable URLs that include your keywords.</p>' +
                    '</div>'
                );

                $tab1.append($section1);

                // Tab 2: Optional Settings
                var $tab2 = $('<div class="modern-tab-content" data-tab="optional"></div>');

                var $section2 = $('<div class="modern-form-section"><h2 class="modern-form-section-title">Optional URL Bases</h2></div>');
                $section2.append('<p style="color:#646970;margin-bottom:20px;">Customize the base slug for category and tag archive pages. Leave blank to use defaults.</p>');

                $section2.append(
                    '<div class="modern-optional-field">' +
                    '<label for="category_base">Category Base</label>' +
                    '<input type="text" name="category_base" id="category_base" value="' + categoryBase + '" placeholder="category">' +
                    '<p class="description">The default is <code>category</code>. Your category archives will be at: <code>/category/your-category/</code></p>' +
                    '</div>'
                );

                $section2.append(
                    '<div class="modern-optional-field">' +
                    '<label for="tag_base">Tag Base</label>' +
                    '<input type="text" name="tag_base" id="tag_base" value="' + tagBase + '" placeholder="tag">' +
                    '<p class="description">The default is <code>tag</code>. Your tag archives will be at: <code>/tag/your-tag/</code></p>' +
                    '</div>'
                );

                $section2.append(
                    '<div class="modern-warning-box">' +
                    '<p><strong>Note:</strong> Changing permalink settings may break existing links. If you have an established site, consider setting up redirects from old URLs to new ones.</p>' +
                    '</div>'
                );

                $tab2.append($section2);

                // Add form actions to each tab
                var $submitClone = $form.find('.submit, p.submit').first().clone();
                var $formActions = $('<div class="modern-form-actions"></div>').append($submitClone);

                $tab1.append($formActions.clone());
                $tab2.append($formActions.clone());

                // Remove original content and add tabs
                $form.find('.form-table, h2, p:not(.submit)').not('.modern-settings-wrapper *').remove();
                $form.find('table, fieldset').remove();
                $form.append($tab1).append($tab2);

                // Tab switching functionality
                $('.modern-tab-button').on('click', function() {
                    var tab = $(this).data('tab');

                    $('.modern-tab-button').removeClass('active');
                    $(this).addClass('active');

                    $('.modern-tab-content').removeClass('active');
                    $('.modern-tab-content[data-tab="' + tab + '"]').addClass('active');
                });

                // Permalink option selection
                $('.modern-permalink-option').on('click', function() {
                    $('.modern-permalink-option').removeClass('selected');
                    $(this).addClass('selected');
                    $(this).find('input[type="radio"]').prop('checked', true);

                    var value = $(this).data('value');
                    if (value === 'custom') {
                        $('#custom-structure-wrapper').slideDown();
                    } else {
                        $('#custom-structure-wrapper').slideUp();
                        $('#permalink_structure').val(value);
                    }
                });

                // Tag button insertion
                $('.modern-tag-button').on('click', function(e) {
                    e.preventDefault();
                    var tag = $(this).data('tag');
                    var $input = $('#permalink_structure');
                    var currentVal = $input.val();
                    $input.val(currentVal + tag);
                    $input.focus();
                });
            }
        });
        </script>
        <?php
    }
}
