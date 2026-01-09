<?php
/**
 * Modern Discussion Settings Page
 *
 * Transforms the WordPress Discussion Settings page with a modern tabbed interface
 *
 * @package Modern_Admin_UI
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class Modern_Discussion_Settings {

    public function __construct() {
        add_action('admin_head-options-discussion.php', array($this, 'enqueue_styles'));
        add_action('admin_footer-options-discussion.php', array($this, 'enqueue_scripts'));
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
                vertical-align: top;
            }

            .modern-tab-content .form-table td {
                padding: 0 0 24px 0;
            }

            .modern-tab-content input[type="text"],
            .modern-tab-content input[type="number"],
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
                min-height: 150px;
                width: 100%;
                font-family: monospace;
            }

            .modern-tab-content input[type="text"]:focus,
            .modern-tab-content input[type="number"]:focus,
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

            /* Checkbox Groups */
            .modern-checkbox-group {
                display: flex;
                flex-direction: column;
                gap: 12px;
            }

            .modern-checkbox-item {
                display: flex;
                align-items: flex-start;
                gap: 10px;
                padding: 12px 16px;
                background: #f6f7f7;
                border-radius: 6px;
                transition: background 0.2s ease;
            }

            .modern-checkbox-item:hover {
                background: #f0f0f1;
            }

            .modern-checkbox-item input[type="checkbox"] {
                margin-top: 2px;
                width: 18px;
                height: 18px;
            }

            .modern-checkbox-item label {
                font-weight: 500;
                color: #1d2327;
            }

            .modern-checkbox-item .description {
                margin-top: 4px;
            }

            /* Nested checkboxes */
            .modern-checkbox-nested {
                margin-left: 28px;
                margin-top: 8px;
                padding-left: 16px;
                border-left: 2px solid #e0e0e0;
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

            /* Avatar Display */
            .modern-avatar-preview {
                display: flex;
                gap: 16px;
                flex-wrap: wrap;
                margin-top: 16px;
            }

            .modern-avatar-option {
                display: flex;
                align-items: center;
                gap: 12px;
                padding: 12px 16px;
                background: #f6f7f7;
                border-radius: 6px;
                border: 2px solid transparent;
                cursor: pointer;
                transition: all 0.2s ease;
            }

            .modern-avatar-option:hover {
                background: #f0f0f1;
            }

            .modern-avatar-option.selected {
                background: #f0f6fc;
                border-color: #2271b1;
            }

            .modern-avatar-option img {
                border-radius: 50%;
            }

            .modern-avatar-option label {
                font-weight: 500;
                cursor: pointer;
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

            @media (max-width: 768px) {
                .modern-tab-navigation {
                    overflow-x: auto;
                }

                .modern-avatar-preview {
                    flex-direction: column;
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
                $h1.after('<p class="settings-description" style="color: #646970; font-size: 14px; margin-top: 8px;">Configure comments, pingbacks, and user avatars</p>');

                // Create wrapper
                $form.wrap('<div class="modern-settings-wrapper"></div>');
                var $wrapper = $('.modern-settings-wrapper');

                // Move any notices into the wrapper
                $('.notice, .updated, .error').appendTo($wrapper);

                // Create tab navigation
                var $tabNav = $('<div class="modern-tab-navigation"></div>');
                $tabNav.append('<button type="button" class="modern-tab-button active" data-tab="comments">Comments</button>');
                $tabNav.append('<button type="button" class="modern-tab-button" data-tab="moderation">Moderation</button>');
                $tabNav.append('<button type="button" class="modern-tab-button" data-tab="avatars">Avatars</button>');

                $form.prepend($tabNav);

                // Tab 1: Comments Settings
                var $tab1 = $('<div class="modern-tab-content active" data-tab="comments"></div>');

                // Default article settings section
                var $section1 = $('<div class="modern-form-section"><h2 class="modern-form-section-title">Default Post Settings</h2></div>');
                var $checkGroup1 = $('<div class="modern-checkbox-group"></div>');

                // Find and rebuild checkboxes
                $form.find('input[name="default_pingback_flag"]').each(function() {
                    var $cb = $(this);
                    var checked = $cb.is(':checked') ? 'checked' : '';
                    $checkGroup1.append(
                        '<div class="modern-checkbox-item">' +
                        '<input type="checkbox" name="default_pingback_flag" id="default_pingback_flag" value="1" ' + checked + '>' +
                        '<div><label for="default_pingback_flag">Attempt to notify any blogs linked to from the post</label></div>' +
                        '</div>'
                    );
                });

                $form.find('input[name="default_ping_status"]').each(function() {
                    var $cb = $(this);
                    var checked = $cb.is(':checked') ? 'checked' : '';
                    $checkGroup1.append(
                        '<div class="modern-checkbox-item">' +
                        '<input type="checkbox" name="default_ping_status" id="default_ping_status" value="open" ' + checked + '>' +
                        '<div><label for="default_ping_status">Allow link notifications from other blogs (pingbacks and trackbacks)</label></div>' +
                        '</div>'
                    );
                });

                $form.find('input[name="default_comment_status"]').each(function() {
                    var $cb = $(this);
                    var checked = $cb.is(':checked') ? 'checked' : '';
                    $checkGroup1.append(
                        '<div class="modern-checkbox-item">' +
                        '<input type="checkbox" name="default_comment_status" id="default_comment_status" value="open" ' + checked + '>' +
                        '<div><label for="default_comment_status">Allow people to submit comments on new posts</label>' +
                        '<p class="description">These settings can be overridden for individual posts.</p></div>' +
                        '</div>'
                    );
                });

                $section1.append($checkGroup1);
                $tab1.append($section1);

                // Other comment settings
                var $section2 = $('<div class="modern-form-section"><h2 class="modern-form-section-title">Other Comment Settings</h2></div>');
                var $checkGroup2 = $('<div class="modern-checkbox-group"></div>');

                $form.find('input[name="require_name_email"]').each(function() {
                    var checked = $(this).is(':checked') ? 'checked' : '';
                    $checkGroup2.append(
                        '<div class="modern-checkbox-item">' +
                        '<input type="checkbox" name="require_name_email" id="require_name_email" value="1" ' + checked + '>' +
                        '<div><label for="require_name_email">Comment author must fill out name and email</label></div>' +
                        '</div>'
                    );
                });

                $form.find('input[name="comment_registration"]').each(function() {
                    var checked = $(this).is(':checked') ? 'checked' : '';
                    $checkGroup2.append(
                        '<div class="modern-checkbox-item">' +
                        '<input type="checkbox" name="comment_registration" id="comment_registration" value="1" ' + checked + '>' +
                        '<div><label for="comment_registration">Users must be registered and logged in to comment</label></div>' +
                        '</div>'
                    );
                });

                $form.find('input[name="close_comments_for_old_posts"]').each(function() {
                    var checked = $(this).is(':checked') ? 'checked' : '';
                    var days = $form.find('input[name="close_comments_days_old"]').val() || '14';
                    $checkGroup2.append(
                        '<div class="modern-checkbox-item">' +
                        '<input type="checkbox" name="close_comments_for_old_posts" id="close_comments_for_old_posts" value="1" ' + checked + '>' +
                        '<div><label for="close_comments_for_old_posts">Automatically close comments on posts older than</label>' +
                        ' <input type="number" name="close_comments_days_old" value="' + days + '" style="width:60px;padding:5px 10px;"> days</div>' +
                        '</div>'
                    );
                });

                $form.find('input[name="show_comments_cookies_opt_in"]').each(function() {
                    var checked = $(this).is(':checked') ? 'checked' : '';
                    $checkGroup2.append(
                        '<div class="modern-checkbox-item">' +
                        '<input type="checkbox" name="show_comments_cookies_opt_in" id="show_comments_cookies_opt_in" value="1" ' + checked + '>' +
                        '<div><label for="show_comments_cookies_opt_in">Show comments cookies opt-in checkbox</label></div>' +
                        '</div>'
                    );
                });

                $form.find('input[name="thread_comments"]').each(function() {
                    var checked = $(this).is(':checked') ? 'checked' : '';
                    var levels = $form.find('select[name="thread_comments_depth"]').val() || '5';
                    var $select = $form.find('select[name="thread_comments_depth"]').clone();
                    $checkGroup2.append(
                        '<div class="modern-checkbox-item">' +
                        '<input type="checkbox" name="thread_comments" id="thread_comments" value="1" ' + checked + '>' +
                        '<div><label for="thread_comments">Enable threaded (nested) comments</label>' +
                        ' <span id="thread_comments_depth_wrapper"></span> levels deep</div>' +
                        '</div>'
                    );
                    $checkGroup2.find('#thread_comments_depth_wrapper').append($select);
                });

                $form.find('input[name="page_comments"]').each(function() {
                    var checked = $(this).is(':checked') ? 'checked' : '';
                    var perPage = $form.find('input[name="comments_per_page"]').val() || '50';
                    $checkGroup2.append(
                        '<div class="modern-checkbox-item">' +
                        '<input type="checkbox" name="page_comments" id="page_comments" value="1" ' + checked + '>' +
                        '<div><label for="page_comments">Break comments into pages</label>' +
                        ' with <input type="number" name="comments_per_page" value="' + perPage + '" style="width:60px;padding:5px 10px;"> top level comments per page</div>' +
                        '</div>'
                    );
                });

                $section2.append($checkGroup2);
                $tab1.append($section2);

                // Email notifications
                var $section3 = $('<div class="modern-form-section"><h2 class="modern-form-section-title">Email Me Whenever</h2></div>');
                var $checkGroup3 = $('<div class="modern-checkbox-group"></div>');

                $form.find('input[name="comments_notify"]').each(function() {
                    var checked = $(this).is(':checked') ? 'checked' : '';
                    $checkGroup3.append(
                        '<div class="modern-checkbox-item">' +
                        '<input type="checkbox" name="comments_notify" id="comments_notify" value="1" ' + checked + '>' +
                        '<div><label for="comments_notify">Anyone posts a comment</label></div>' +
                        '</div>'
                    );
                });

                $form.find('input[name="moderation_notify"]').each(function() {
                    var checked = $(this).is(':checked') ? 'checked' : '';
                    $checkGroup3.append(
                        '<div class="modern-checkbox-item">' +
                        '<input type="checkbox" name="moderation_notify" id="moderation_notify" value="1" ' + checked + '>' +
                        '<div><label for="moderation_notify">A comment is held for moderation</label></div>' +
                        '</div>'
                    );
                });

                $section3.append($checkGroup3);
                $tab1.append($section3);

                // Tab 2: Moderation
                var $tab2 = $('<div class="modern-tab-content" data-tab="moderation"></div>');

                var $section4 = $('<div class="modern-form-section"><h2 class="modern-form-section-title">Before a Comment Appears</h2></div>');
                var $checkGroup4 = $('<div class="modern-checkbox-group"></div>');

                $form.find('input[name="comment_moderation"]').each(function() {
                    var checked = $(this).is(':checked') ? 'checked' : '';
                    $checkGroup4.append(
                        '<div class="modern-checkbox-item">' +
                        '<input type="checkbox" name="comment_moderation" id="comment_moderation" value="1" ' + checked + '>' +
                        '<div><label for="comment_moderation">Comment must be manually approved</label></div>' +
                        '</div>'
                    );
                });

                $form.find('input[name="comment_previously_approved"]').each(function() {
                    var checked = $(this).is(':checked') ? 'checked' : '';
                    $checkGroup4.append(
                        '<div class="modern-checkbox-item">' +
                        '<input type="checkbox" name="comment_previously_approved" id="comment_previously_approved" value="1" ' + checked + '>' +
                        '<div><label for="comment_previously_approved">Comment author must have a previously approved comment</label></div>' +
                        '</div>'
                    );
                });

                $section4.append($checkGroup4);
                $tab2.append($section4);

                // Comment Moderation
                var $section5 = $('<div class="modern-form-section"><h2 class="modern-form-section-title">Comment Moderation <span class="info-badge">SPAM PROTECTION</span></h2></div>');

                var linkCount = $form.find('input[name="comment_max_links"]').val() || '2';
                $section5.append(
                    '<p style="margin-bottom:16px;">Hold a comment in the queue if it contains ' +
                    '<input type="number" name="comment_max_links" value="' + linkCount + '" style="width:60px;padding:5px 10px;margin:0 4px;"> or more links.</p>'
                );

                var moderationKeys = $form.find('textarea[name="moderation_keys"]').val() || '';
                $section5.append(
                    '<label for="moderation_keys" style="display:block;font-weight:500;margin-bottom:8px;">Moderation Keywords:</label>' +
                    '<textarea name="moderation_keys" id="moderation_keys" style="width:100%;max-width:600px;min-height:150px;">' + moderationKeys + '</textarea>' +
                    '<p class="description">When a comment contains any of these words in its content, author name, URL, email, IP address, or browser info, it will be held in the moderation queue. One word or IP per line.</p>'
                );

                $tab2.append($section5);

                // Disallowed Comment Keys
                var $section6 = $('<div class="modern-form-section"><h2 class="modern-form-section-title">Disallowed Comment Keys</h2></div>');
                var disallowedKeys = $form.find('textarea[name="disallowed_keys"]').val() || '';
                $section6.append(
                    '<textarea name="disallowed_keys" id="disallowed_keys" style="width:100%;max-width:600px;min-height:150px;">' + disallowedKeys + '</textarea>' +
                    '<p class="description">When a comment contains any of these words, it will be moved to Trash. One word or IP per line.</p>'
                );

                $tab2.append($section6);

                // Tab 3: Avatars
                var $tab3 = $('<div class="modern-tab-content" data-tab="avatars"></div>');

                var $section7 = $('<div class="modern-form-section"><h2 class="modern-form-section-title">Avatar Display</h2></div>');
                var $checkGroup7 = $('<div class="modern-checkbox-group"></div>');

                $form.find('input[name="show_avatars"]').each(function() {
                    var checked = $(this).is(':checked') ? 'checked' : '';
                    $checkGroup7.append(
                        '<div class="modern-checkbox-item">' +
                        '<input type="checkbox" name="show_avatars" id="show_avatars" value="1" ' + checked + '>' +
                        '<div><label for="show_avatars">Show Avatars</label>' +
                        '<p class="description">Display user profile pictures next to their comments and in the admin.</p></div>' +
                        '</div>'
                    );
                });

                $section7.append($checkGroup7);

                $section7.append('<div class="modern-info-box"><p>Avatars are provided by <strong>Gravatar</strong>. Users can set their avatar at gravatar.com using their email address.</p></div>');

                $tab3.append($section7);

                // Maximum Rating
                var $section8 = $('<div class="modern-form-section"><h2 class="modern-form-section-title">Maximum Rating</h2></div>');
                var $ratingGroup = $('<div class="modern-checkbox-group"></div>');

                var ratings = [
                    {value: 'G', label: 'G — Suitable for all audiences'},
                    {value: 'PG', label: 'PG — Possibly offensive, usually for audiences 13 and above'},
                    {value: 'R', label: 'R — Intended for adult audiences above 17'},
                    {value: 'X', label: 'X — Mature audiences only'}
                ];

                var currentRating = $form.find('input[name="avatar_rating"]:checked').val() || 'G';

                ratings.forEach(function(rating) {
                    var checked = (rating.value === currentRating) ? 'checked' : '';
                    $ratingGroup.append(
                        '<div class="modern-checkbox-item">' +
                        '<input type="radio" name="avatar_rating" id="avatar_rating_' + rating.value + '" value="' + rating.value + '" ' + checked + '>' +
                        '<label for="avatar_rating_' + rating.value + '">' + rating.label + '</label>' +
                        '</div>'
                    );
                });

                $section8.append($ratingGroup);
                $tab3.append($section8);

                // Default Avatar
                var $section9 = $('<div class="modern-form-section"><h2 class="modern-form-section-title">Default Avatar</h2></div>');
                $section9.append('<p style="margin-bottom:16px;">For users without a custom avatar, display:</p>');

                var $avatarPreview = $('<div class="modern-avatar-preview"></div>');

                // Get current default avatar
                var currentAvatar = $form.find('input[name="avatar_default"]:checked').val() || 'mystery';

                // Clone avatar options from original form
                $form.find('input[name="avatar_default"]').each(function() {
                    var $radio = $(this);
                    var value = $radio.val();
                    var $img = $radio.closest('label, fieldset').find('img').first();
                    var imgSrc = $img.attr('src') || '';
                    var label = $radio.parent().text().trim();
                    var checked = (value === currentAvatar) ? 'checked' : '';
                    var selectedClass = (value === currentAvatar) ? 'selected' : '';

                    var avatarHtml = '<div class="modern-avatar-option ' + selectedClass + '">' +
                        '<input type="radio" name="avatar_default" id="avatar_default_' + value + '" value="' + value + '" ' + checked + ' style="display:none;">';

                    if (imgSrc) {
                        avatarHtml += '<img src="' + imgSrc + '" width="32" height="32">';
                    }

                    avatarHtml += '<label for="avatar_default_' + value + '">' + label + '</label></div>';

                    $avatarPreview.append(avatarHtml);
                });

                $section9.append($avatarPreview);
                $tab3.append($section9);

                // Add form actions to each tab
                var $submitClone = $form.find('.submit').first().clone();
                var $formActions = $('<div class="modern-form-actions"></div>').append($submitClone);

                $tab1.append($formActions.clone());
                $tab2.append($formActions.clone());
                $tab3.append($formActions.clone());

                // Remove original content and add tabs
                $form.find('.form-table, fieldset, h2, br').remove();
                $form.append($tab1).append($tab2).append($tab3);

                // Tab switching functionality
                $('.modern-tab-button').on('click', function() {
                    var tab = $(this).data('tab');

                    $('.modern-tab-button').removeClass('active');
                    $(this).addClass('active');

                    $('.modern-tab-content').removeClass('active');
                    $('.modern-tab-content[data-tab="' + tab + '"]').addClass('active');
                });

                // Avatar option selection
                $('.modern-avatar-option').on('click', function() {
                    $('.modern-avatar-option').removeClass('selected');
                    $(this).addClass('selected');
                    $(this).find('input[type="radio"]').prop('checked', true);
                });
            }
        });
        </script>
        <?php
    }
}
