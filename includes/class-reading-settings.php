<?php
/**
 * Modern Reading Settings Page
 *
 * Transforms the WordPress Reading Settings page with a modern tabbed interface
 *
 * @package Modern_Admin_UI
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class Modern_Reading_Settings {

    public function __construct() {
        add_action('admin_head-options-reading.php', array($this, 'enqueue_styles'));
        add_action('admin_footer-options-reading.php', array($this, 'enqueue_scripts'));
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

            .warning-badge {
                background: #fcf0f0;
                color: #d63638;
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
            .modern-tab-content input[type="number"],
            .modern-tab-content select {
                max-width: 500px;
                padding: 10px 14px;
                border: 1px solid #8c8f94;
                border-radius: 4px;
                font-size: 14px;
                transition: border-color 0.2s ease;
            }

            .modern-tab-content input[type="text"]:focus,
            .modern-tab-content input[type="number"]:focus,
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

            /* Radio Fields */
            .modern-radio-group {
                display: flex;
                flex-direction: column;
                gap: 12px;
            }

            .modern-radio-item {
                display: flex;
                align-items: flex-start;
                gap: 10px;
                padding: 12px 16px;
                background: #f6f7f7;
                border-radius: 6px;
                transition: background 0.2s ease;
            }

            .modern-radio-item:hover {
                background: #f0f0f1;
            }

            .modern-radio-item.selected {
                background: #f0f6fc;
                border: 1px solid #2271b1;
            }

            .modern-radio-item input[type="radio"] {
                margin-top: 2px;
            }

            .modern-radio-item label {
                font-weight: 500;
                color: #1d2327;
            }

            /* Checkbox Fields */
            .modern-checkbox-field {
                display: flex;
                align-items: center;
                gap: 10px;
                padding: 12px 16px;
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

            /* Page Selection Styling */
            .modern-page-selector {
                margin-top: 16px;
                padding: 16px;
                background: #fff;
                border: 1px solid #e0e0e0;
                border-radius: 6px;
                max-width: 500px;
            }

            .modern-page-selector label {
                display: block;
                margin-bottom: 8px;
                font-weight: 500;
            }

            .modern-page-selector select {
                width: 100%;
            }

            /* Warning Box */
            .modern-warning-box {
                padding: 16px;
                background: #fcf0f0;
                border-left: 4px solid #d63638;
                border-radius: 6px;
                margin-top: 16px;
            }

            .modern-warning-box p {
                margin: 0;
                color: #1d2327;
                font-size: 13px;
            }

            @media (max-width: 768px) {
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
                $h1.after('<p class="settings-description" style="color: #646970; font-size: 14px; margin-top: 8px;">Configure how your site displays content and feeds</p>');

                // Create wrapper
                $form.wrap('<div class="modern-settings-wrapper"></div>');
                var $wrapper = $('.modern-settings-wrapper');

                // Move any notices into the wrapper
                $('.notice, .updated, .error').appendTo($wrapper);

                // Create tab navigation
                var $tabNav = $('<div class="modern-tab-navigation"></div>');
                $tabNav.append('<button type="button" class="modern-tab-button active" data-tab="homepage">Homepage Display</button>');
                $tabNav.append('<button type="button" class="modern-tab-button" data-tab="feed">Feed Settings</button>');
                $tabNav.append('<button type="button" class="modern-tab-button" data-tab="visibility">Search Visibility</button>');

                $form.prepend($tabNav);

                // Get original content
                var $table = $form.find('.form-table').first();

                // Tab 1: Homepage Display
                var $tab1 = $('<div class="modern-tab-content active" data-tab="homepage"></div>');
                var $section1 = $('<div class="modern-form-section"><h2 class="modern-form-section-title">Your Homepage Displays</h2></div>');

                // Find the homepage display rows
                var $homepageRow = $table.find('tr').filter(function() {
                    return $(this).find('input[name="show_on_front"]').length > 0 ||
                           $(this).find('label:contains("Your homepage displays")').length > 0;
                });

                if ($homepageRow.length) {
                    var $radioGroup = $('<div class="modern-radio-group"></div>');

                    // Latest posts option
                    var $postsRadio = $table.find('input[name="show_on_front"][value="posts"]');
                    var postsChecked = $postsRadio.is(':checked') ? 'selected' : '';
                    $radioGroup.append(
                        '<div class="modern-radio-item ' + postsChecked + '">' +
                        '<input type="radio" name="show_on_front" id="show_on_front_posts" value="posts" ' + ($postsRadio.is(':checked') ? 'checked' : '') + '>' +
                        '<div><label for="show_on_front_posts">Your latest posts</label>' +
                        '<p class="description">Display your most recent blog posts on the homepage</p></div>' +
                        '</div>'
                    );

                    // Static page option
                    var $pageRadio = $table.find('input[name="show_on_front"][value="page"]');
                    var pageChecked = $pageRadio.is(':checked') ? 'selected' : '';
                    $radioGroup.append(
                        '<div class="modern-radio-item ' + pageChecked + '">' +
                        '<input type="radio" name="show_on_front" id="show_on_front_page" value="page" ' + ($pageRadio.is(':checked') ? 'checked' : '') + '>' +
                        '<div><label for="show_on_front_page">A static page</label>' +
                        '<p class="description">Choose specific pages for your homepage and posts page</p></div>' +
                        '</div>'
                    );

                    $section1.append($radioGroup);

                    // Page selectors (show only when static page is selected)
                    var $pageSelectors = $('<div class="modern-page-selectors" style="' + ($pageRadio.is(':checked') ? '' : 'display:none;') + '"></div>');

                    var $homepageSelect = $table.find('select[name="page_on_front"]').clone();
                    var $postsPageSelect = $table.find('select[name="page_for_posts"]').clone();

                    $pageSelectors.append(
                        '<div class="modern-page-selector">' +
                        '<label for="page_on_front">Homepage:</label>' +
                        '</div>'
                    );
                    $pageSelectors.find('.modern-page-selector').last().append($homepageSelect);

                    $pageSelectors.append(
                        '<div class="modern-page-selector">' +
                        '<label for="page_for_posts">Posts page:</label>' +
                        '</div>'
                    );
                    $pageSelectors.find('.modern-page-selector').last().append($postsPageSelect);

                    $section1.append($pageSelectors);
                }

                $tab1.append($section1);

                // Tab 2: Feed Settings
                var $tab2 = $('<div class="modern-tab-content" data-tab="feed"></div>');

                var $section2 = $('<div class="modern-form-section"><h2 class="modern-form-section-title">Blog Pages Settings</h2><table class="form-table" role="presentation"></table></div>');
                var $postsPerPageRow = $table.find('tr').has('[name="posts_per_page"]').clone();
                $section2.find('table').append($postsPerPageRow);
                $tab2.append($section2);

                var $section3 = $('<div class="modern-form-section"><h2 class="modern-form-section-title">Syndication Feeds</h2><table class="form-table" role="presentation"></table></div>');
                var $rssItemsRow = $table.find('tr').has('[name="posts_per_rss"]').clone();
                var $rssExcerptRow = $table.find('tr').has('[name="rss_use_excerpt"]').clone();
                $section3.find('table').append($rssItemsRow);
                $section3.find('table').append($rssExcerptRow);
                $tab2.append($section3);

                // Tab 3: Search Engine Visibility
                var $tab3 = $('<div class="modern-tab-content" data-tab="visibility"></div>');
                var $section4 = $('<div class="modern-form-section"><h2 class="modern-form-section-title">Search Engine Visibility <span class="info-badge warning-badge">IMPORTANT</span></h2></div>');

                var $visibilityRow = $table.find('tr').has('[name="blog_public"]');
                var $checkbox = $visibilityRow.find('input[type="checkbox"]');
                var isChecked = $checkbox.is(':checked');

                var $checkboxField = $(
                    '<div class="modern-checkbox-field">' +
                    '<input type="checkbox" name="blog_public" id="blog_public" value="0" ' + (isChecked ? 'checked' : '') + '>' +
                    '<label for="blog_public">Discourage search engines from indexing this site</label>' +
                    '</div>'
                );
                $section4.append($checkboxField);

                $section4.append(
                    '<div class="modern-warning-box">' +
                    '<p><strong>Note:</strong> It is up to search engines to honor this request. This does not block access to your site — it only asks search engines to not index it. For full privacy, you would need additional security measures.</p>' +
                    '</div>'
                );

                $tab3.append($section4);

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

                // Radio group interactivity
                $('.modern-radio-item input[type="radio"]').on('change', function() {
                    $('.modern-radio-item').removeClass('selected');
                    $(this).closest('.modern-radio-item').addClass('selected');

                    if ($(this).val() === 'page') {
                        $('.modern-page-selectors').slideDown();
                    } else {
                        $('.modern-page-selectors').slideUp();
                    }
                });
            }
        });
        </script>
        <?php
    }
}
