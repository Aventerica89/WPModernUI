<?php
/**
 * Modern List Tables
 *
 * Modernizes WordPress admin list tables (Posts, Pages, Categories, Tags, Users, etc.)
 *
 * @package Modern_Admin_UI
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class Modern_List_Tables {

    /**
     * List of admin pages with list tables
     */
    private $list_table_pages = array(
        'edit.php',           // Posts, Pages, Custom Post Types
        'edit-tags.php',      // Categories, Tags, Custom Taxonomies
        'users.php',          // Users
        'edit-comments.php',  // Comments
        'upload.php',         // Media Library (list mode)
        'plugins.php',        // Plugins
        'themes.php',         // Themes (list)
        'tools.php',          // Tools
        'link-manager.php',   // Links (if enabled)
    );

    /**
     * Constructor
     */
    public function __construct() {
        add_action('admin_enqueue_scripts', array($this, 'enqueue_styles'));
        add_action('admin_footer', array($this, 'output_scripts'));
    }

    /**
     * Check if current page has a list table
     */
    private function is_list_table_page() {
        global $pagenow;

        // Check if current page is in our list
        if (in_array($pagenow, $this->list_table_pages)) {
            return true;
        }

        return false;
    }

    /**
     * Enqueue styles for list table pages
     */
    public function enqueue_styles($hook) {
        if (!$this->is_list_table_page()) {
            return;
        }

        wp_add_inline_style('wp-admin', $this->get_list_table_styles());
    }

    /**
     * Output JavaScript for list table enhancements
     */
    public function output_scripts() {
        if (!$this->is_list_table_page()) {
            return;
        }
        ?>
        <script type="text/javascript">
        jQuery(document).ready(function($) {
            // Add wrapper class to body for scoping
            $('body').addClass('modern-list-tables');

            // Enhance checkboxes with modern styling
            $('.wp-list-table .check-column input[type="checkbox"]').addClass('modern-cb');

            // Convert subsubsub links to tabs
            var $subsubsub = $('.subsubsub');
            if ($subsubsub.length) {
                $subsubsub.addClass('modern-filter-tabs');
                $subsubsub.find('li').each(function() {
                    var $li = $(this);
                    var $link = $li.find('a');
                    var count = $link.find('.count').text();

                    // Add active class to current filter
                    if ($link.hasClass('current')) {
                        $link.addClass('active');
                    }

                    // Style the count
                    if (count) {
                        $link.find('.count').addClass('modern-count');
                    }
                });
            }

            // Enhance row actions - show on hover via CSS, but ensure proper structure
            $('.wp-list-table .row-actions').each(function() {
                $(this).addClass('modern-row-actions');
            });

            // Add modern class to status indicators
            $('.wp-list-table .post-state').addClass('modern-post-state');

            // Enhance bulk actions dropdown
            $('#bulk-action-selector-top, #bulk-action-selector-bottom').addClass('modern-select');

            // Enhance filter dropdowns
            $('.tablenav select').not('.modern-select').addClass('modern-select');

            // Enhance search box
            $('.search-box').addClass('modern-search-box');

            // Add modern class to pagination
            $('.tablenav-pages').addClass('modern-pagination');

            // Wrap table in modern container if not already
            var $table = $('.wp-list-table');
            if ($table.length && !$table.parent().hasClass('modern-table-wrapper')) {
                $table.wrap('<div class="modern-table-wrapper"></div>');
            }

            // Enhance category/tag pill display
            $('.wp-list-table .tags, .wp-list-table td.categories, td.tags').each(function() {
                var $cell = $(this);
                var links = $cell.find('a');
                if (links.length > 0) {
                    $cell.addClass('modern-term-cell');
                    links.addClass('modern-term-pill');
                }
            });
        });
        </script>
        <?php
    }

    /**
     * Get CSS styles for list tables
     */
    private function get_list_table_styles() {
        return "
        /* Modern List Tables - Global Styles */
        .modern-list-tables .wrap {
            max-width: 100%;
            margin: 20px 20px 20px 0;
        }

        /* Page Title Area */
        .modern-list-tables .wrap > h1,
        .modern-list-tables .wrap > h1.wp-heading-inline {
            font-size: 24px;
            font-weight: 600;
            color: #1d2327;
            margin-bottom: 20px;
            display: inline-flex;
            align-items: center;
            gap: 12px;
        }

        .modern-list-tables .page-title-action {
            background: #2271b1 !important;
            color: white !important;
            border: none !important;
            padding: 8px 16px !important;
            border-radius: 6px !important;
            font-size: 13px !important;
            font-weight: 500 !important;
            text-decoration: none !important;
            transition: all 0.2s ease !important;
            position: relative !important;
            top: 0 !important;
        }

        .modern-list-tables .page-title-action:hover {
            background: #135e96 !important;
        }

        /* Filter Tabs (subsubsub) */
        .modern-list-tables .subsubsub {
            display: flex;
            flex-wrap: wrap;
            gap: 4px;
            margin: 16px 0;
            padding: 4px;
            background: #f0f0f1;
            border-radius: 8px;
            list-style: none;
            float: none !important;
        }

        .modern-list-tables .subsubsub li {
            margin: 0;
        }

        .modern-list-tables .subsubsub li::after {
            content: none;
        }

        .modern-list-tables .subsubsub li .separator {
            display: none;
        }

        .modern-list-tables .subsubsub a {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 500;
            color: #646970;
            text-decoration: none;
            transition: all 0.2s ease;
            background: transparent;
        }

        .modern-list-tables .subsubsub a:hover {
            background: rgba(255,255,255,0.5);
            color: #1d2327;
        }

        .modern-list-tables .subsubsub a.current {
            background: white;
            color: #1d2327;
            box-shadow: 0 1px 3px rgba(0,0,0,0.08);
        }

        .modern-list-tables .subsubsub .count {
            background: #ddd;
            color: #646970;
            font-size: 11px;
            padding: 2px 8px;
            border-radius: 10px;
            font-weight: 600;
        }

        .modern-list-tables .subsubsub a.current .count {
            background: #2271b1;
            color: white;
        }

        /* Table Navigation Bar */
        .modern-list-tables .tablenav {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 12px;
            margin: 16px 0;
            padding: 12px 16px;
            background: #fafafa;
            border-radius: 8px;
            height: auto;
        }

        .modern-list-tables .tablenav.top {
            border-bottom: 1px solid #e0e0e0;
        }

        .modern-list-tables .tablenav .actions {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 0;
        }

        /* Modern Select Dropdowns */
        .modern-list-tables select,
        .modern-list-tables .modern-select {
            appearance: none;
            -webkit-appearance: none;
            background: white;
            border: 1px solid #e0e0e0;
            border-radius: 6px;
            padding: 8px 36px 8px 12px;
            font-size: 13px;
            color: #1d2327;
            cursor: pointer;
            transition: all 0.2s;
            background-image: url(\"data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%23646970' d='M6 8L1 3h10z'/%3E%3C/svg%3E\");
            background-repeat: no-repeat;
            background-position: right 10px center;
            min-width: 120px;
            height: auto !important;
            line-height: 1.4;
        }

        .modern-list-tables select:hover,
        .modern-list-tables .modern-select:hover {
            border-color: #2271b1;
        }

        .modern-list-tables select:focus,
        .modern-list-tables .modern-select:focus {
            outline: none;
            border-color: #2271b1;
            box-shadow: 0 0 0 2px rgba(34, 113, 177, 0.15);
        }

        /* Buttons in tablenav */
        .modern-list-tables .tablenav .button,
        .modern-list-tables .tablenav input[type='submit'] {
            background: #f0f0f1 !important;
            border: 1px solid #e0e0e0 !important;
            padding: 8px 16px !important;
            border-radius: 6px !important;
            font-size: 13px !important;
            font-weight: 500 !important;
            color: #1d2327 !important;
            cursor: pointer !important;
            transition: all 0.2s !important;
            height: auto !important;
            line-height: 1.4 !important;
        }

        .modern-list-tables .tablenav .button:hover,
        .modern-list-tables .tablenav input[type='submit']:hover {
            background: #e0e0e0 !important;
            border-color: #c3c4c7 !important;
        }

        /* Search Box */
        .modern-list-tables .search-box {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-left: auto;
        }

        .modern-list-tables .search-box input[type='search'],
        .modern-list-tables .search-box input[type='text'] {
            background: white;
            border: 1px solid #e0e0e0;
            border-radius: 6px;
            padding: 8px 12px;
            font-size: 13px;
            width: 200px;
            transition: all 0.2s;
        }

        .modern-list-tables .search-box input[type='search']:focus,
        .modern-list-tables .search-box input[type='text']:focus {
            outline: none;
            border-color: #2271b1;
            box-shadow: 0 0 0 2px rgba(34, 113, 177, 0.15);
        }

        .modern-list-tables .search-box .button {
            background: #2271b1 !important;
            color: white !important;
            border: none !important;
        }

        .modern-list-tables .search-box .button:hover {
            background: #135e96 !important;
        }

        /* Table Wrapper */
        .modern-list-tables .modern-table-wrapper {
            background: white;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.08);
            overflow: hidden;
        }

        /* Table Styles */
        .modern-list-tables .wp-list-table {
            border: none;
            border-collapse: collapse;
            width: 100%;
        }

        .modern-list-tables .wp-list-table thead th,
        .modern-list-tables .wp-list-table thead td {
            background: #f8f9fa;
            padding: 14px 12px;
            font-size: 12px;
            font-weight: 600;
            color: #646970;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 2px solid #e0e0e0;
        }

        .modern-list-tables .wp-list-table thead th:first-child {
            padding-left: 16px;
        }

        .modern-list-tables .wp-list-table thead th a {
            color: #646970;
            text-decoration: none;
        }

        .modern-list-tables .wp-list-table thead th a:hover {
            color: #2271b1;
        }

        .modern-list-tables .wp-list-table thead th.sorted a,
        .modern-list-tables .wp-list-table thead th.sorted {
            color: #1d2327;
        }

        /* Table Body */
        .modern-list-tables .wp-list-table tbody tr {
            background: white;
            transition: background 0.15s ease;
        }

        .modern-list-tables .wp-list-table tbody tr:hover {
            background: #f8fbfd;
        }

        .modern-list-tables .wp-list-table tbody tr.alternate {
            background: white;
        }

        .modern-list-tables .wp-list-table tbody tr.alternate:hover {
            background: #f8fbfd;
        }

        .modern-list-tables .wp-list-table tbody td {
            padding: 12px;
            font-size: 14px;
            color: #1d2327;
            border-bottom: 1px solid #f0f0f1;
            vertical-align: middle;
        }

        .modern-list-tables .wp-list-table tbody td:first-child {
            padding-left: 16px;
        }

        .modern-list-tables .wp-list-table tbody tr:last-child td {
            border-bottom: none;
        }

        /* Checkbox Column */
        .modern-list-tables .wp-list-table .check-column {
            width: 40px;
            padding: 12px 8px 12px 16px !important;
        }

        .modern-list-tables .wp-list-table .check-column input[type='checkbox'] {
            width: 18px;
            height: 18px;
            border: 2px solid #c3c4c7;
            border-radius: 4px;
            cursor: pointer;
            margin: 0;
            transition: all 0.2s;
        }

        .modern-list-tables .wp-list-table .check-column input[type='checkbox']:hover {
            border-color: #2271b1;
        }

        .modern-list-tables .wp-list-table .check-column input[type='checkbox']:checked {
            background: #2271b1;
            border-color: #2271b1;
        }

        /* Title/Name Column */
        .modern-list-tables .wp-list-table .row-title,
        .modern-list-tables .wp-list-table .column-title strong a,
        .modern-list-tables .wp-list-table .column-name strong a,
        .modern-list-tables .wp-list-table .column-username strong a {
            font-weight: 600;
            color: #2271b1;
            text-decoration: none;
            font-size: 14px;
        }

        .modern-list-tables .wp-list-table .row-title:hover,
        .modern-list-tables .wp-list-table .column-title strong a:hover,
        .modern-list-tables .wp-list-table .column-name strong a:hover,
        .modern-list-tables .wp-list-table .column-username strong a:hover {
            color: #135e96;
        }

        /* Post States (Draft, Pending, etc.) */
        .modern-list-tables .post-state {
            font-weight: 500;
            font-size: 12px;
        }

        /* Row Actions */
        .modern-list-tables .wp-list-table .row-actions {
            padding: 4px 0 0 0;
            visibility: visible;
            opacity: 0;
            transition: opacity 0.15s ease;
        }

        .modern-list-tables .wp-list-table tr:hover .row-actions {
            opacity: 1;
        }

        .modern-list-tables .wp-list-table .row-actions span {
            font-size: 12px;
        }

        .modern-list-tables .wp-list-table .row-actions a {
            color: #2271b1;
            text-decoration: none;
        }

        .modern-list-tables .wp-list-table .row-actions a:hover {
            color: #135e96;
        }

        .modern-list-tables .wp-list-table .row-actions .trash a,
        .modern-list-tables .wp-list-table .row-actions .delete a {
            color: #d63638;
        }

        .modern-list-tables .wp-list-table .row-actions .trash a:hover,
        .modern-list-tables .wp-list-table .row-actions .delete a:hover {
            color: #a00;
        }

        /* Category/Tag Pills */
        .modern-list-tables .modern-term-cell a,
        .modern-list-tables td.tags a,
        .modern-list-tables td.categories a,
        .modern-list-tables .column-categories a,
        .modern-list-tables .column-tags a {
            display: inline-block;
            background: #f0f6fc;
            color: #2271b1;
            padding: 3px 10px;
            border-radius: 12px;
            font-size: 12px;
            text-decoration: none;
            margin: 2px 4px 2px 0;
            transition: all 0.2s;
        }

        .modern-list-tables .modern-term-cell a:hover,
        .modern-list-tables td.tags a:hover,
        .modern-list-tables td.categories a:hover,
        .modern-list-tables .column-categories a:hover,
        .modern-list-tables .column-tags a:hover {
            background: #2271b1;
            color: white;
        }

        /* Date Column */
        .modern-list-tables .wp-list-table .column-date {
            color: #646970;
            font-size: 13px;
        }

        .modern-list-tables .wp-list-table .column-date abbr {
            border: none;
            text-decoration: none;
        }

        /* Status Badges */
        .modern-list-tables .status-draft .post-state,
        .modern-list-tables .post-state-draft {
            color: #646970;
        }

        .modern-list-tables .status-publish .post-state,
        .modern-list-tables .post-state-publish {
            color: #00a32a;
        }

        .modern-list-tables .status-pending .post-state,
        .modern-list-tables .post-state-pending {
            color: #996800;
        }

        .modern-list-tables .status-future .post-state,
        .modern-list-tables .post-state-scheduled {
            color: #0073aa;
        }

        /* Pagination */
        .modern-list-tables .tablenav-pages {
            margin-left: auto;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .modern-list-tables .tablenav-pages .displaying-num {
            color: #646970;
            font-size: 13px;
        }

        .modern-list-tables .tablenav-pages .pagination-links {
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .modern-list-tables .tablenav-pages .pagination-links a,
        .modern-list-tables .tablenav-pages .pagination-links span {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 32px;
            height: 32px;
            padding: 0 8px;
            border: 1px solid #e0e0e0;
            border-radius: 6px;
            background: white;
            color: #1d2327;
            font-size: 13px;
            text-decoration: none;
            transition: all 0.2s;
        }

        .modern-list-tables .tablenav-pages .pagination-links a:hover {
            background: #f0f0f1;
            border-color: #c3c4c7;
        }

        .modern-list-tables .tablenav-pages .pagination-links .current-page {
            width: 50px;
            text-align: center;
        }

        .modern-list-tables .tablenav-pages .pagination-links .tablenav-paging-text {
            border: none;
            background: transparent;
        }

        /* Number column */
        .modern-list-tables .wp-list-table .column-posts,
        .modern-list-tables .wp-list-table .column-users,
        .modern-list-tables .wp-list-table .column-count {
            text-align: center;
        }

        .modern-list-tables .wp-list-table .column-posts a,
        .modern-list-tables .wp-list-table .column-users a,
        .modern-list-tables .wp-list-table .column-count a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 28px;
            height: 28px;
            background: #f0f0f1;
            border-radius: 14px;
            color: #1d2327;
            font-weight: 500;
            font-size: 13px;
            text-decoration: none;
            transition: all 0.2s;
        }

        .modern-list-tables .wp-list-table .column-posts a:hover,
        .modern-list-tables .wp-list-table .column-users a:hover,
        .modern-list-tables .wp-list-table .column-count a:hover {
            background: #2271b1;
            color: white;
        }

        /* Featured Image / Thumbnail Column */
        .modern-list-tables .wp-list-table .column-thumb img,
        .modern-list-tables .wp-list-table .media-icon img {
            border-radius: 6px;
            max-width: 60px;
            height: auto;
        }

        /* Author Column */
        .modern-list-tables .wp-list-table .column-author a {
            color: #1d2327;
            text-decoration: none;
        }

        .modern-list-tables .wp-list-table .column-author a:hover {
            color: #2271b1;
        }

        /* Comments Column */
        .modern-list-tables .wp-list-table .column-comments {
            text-align: center;
        }

        .modern-list-tables .wp-list-table .column-comments .post-com-count {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 28px;
            height: 28px;
            background: #f0f0f1;
            border-radius: 14px;
            padding: 0 8px;
        }

        .modern-list-tables .wp-list-table .column-comments .post-com-count:hover {
            background: #e0e0e0;
        }

        /* Empty table message */
        .modern-list-tables .wp-list-table .no-items td {
            padding: 40px;
            text-align: center;
            color: #646970;
            font-size: 14px;
        }

        /* Screen options button */
        .modern-list-tables #screen-meta-links .show-settings {
            border-radius: 0 0 6px 6px;
        }

        /* Bottom tablenav */
        .modern-list-tables .tablenav.bottom {
            background: #fafafa;
            border-top: 1px solid #e0e0e0;
            border-radius: 0 0 8px 8px;
        }

        /* Inline editor row */
        .modern-list-tables .wp-list-table .inline-edit-row {
            background: #f8fbfd;
        }

        .modern-list-tables .wp-list-table .inline-edit-row fieldset {
            padding: 16px;
        }

        /* Quick Edit / Bulk Edit */
        .modern-list-tables .inline-edit-wrapper {
            padding: 20px;
        }

        .modern-list-tables .inline-edit-wrapper .inline-edit-col {
            padding: 12px;
        }

        .modern-list-tables .inline-edit-save .button {
            margin-right: 8px !important;
        }

        /* Locked indicator */
        .modern-list-tables .wp-list-table .locked-indicator {
            margin-right: 8px;
        }

        /* Users page specific */
        .modern-list-tables .wp-list-table .column-role {
            min-width: 100px;
        }

        /* Media library specific */
        .modern-list-tables .wp-list-table .column-title .media-icon {
            margin-right: 12px;
        }

        /* Plugins page specific */
        .modern-list-tables .wp-list-table .plugin-title strong {
            font-size: 14px;
        }

        .modern-list-tables .wp-list-table .plugin-description {
            padding: 8px 0;
        }

        .modern-list-tables .wp-list-table .active td {
            background: #f0f6fc;
        }

        .modern-list-tables .wp-list-table .active th.check-column {
            border-left: 4px solid #2271b1;
        }

        .modern-list-tables .wp-list-table .inactive td {
            background: #fafafa;
        }

        /* Update indicator */
        .modern-list-tables .wp-list-table .update td {
            border-left: 4px solid #d97706;
        }

        /* Items count in tablenav */
        .modern-list-tables .displaying-num {
            font-size: 13px;
            color: #646970;
            margin-left: auto;
        }

        /* Fix for bulk actions alignment */
        .modern-list-tables .tablenav .alignleft {
            float: none;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* Responsive improvements */
        @media screen and (max-width: 782px) {
            .modern-list-tables .subsubsub {
                flex-direction: column;
            }

            .modern-list-tables .tablenav {
                flex-direction: column;
                align-items: stretch;
            }

            .modern-list-tables .search-box {
                margin-left: 0;
                margin-top: 12px;
            }

            .modern-list-tables .search-box input[type='search'],
            .modern-list-tables .search-box input[type='text'] {
                width: 100%;
            }

            .modern-list-tables .tablenav-pages {
                margin: 12px 0 0 0;
                justify-content: center;
            }
        }

        /* ID column monospace */
        .modern-list-tables .wp-list-table .column-id {
            font-family: monospace;
            color: #646970;
            font-size: 13px;
        }

        /* Hide default table navigation extras we don't need */
        .modern-list-tables .tablenav br {
            display: none;
        }
        ";
    }
}
