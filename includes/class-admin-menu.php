<?php
/**
 * Modern Admin Menu
 *
 * Modernizes the WordPress admin sidebar menu and top toolbar.
 *
 * @package Modern_Admin_UI
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class Modern_Admin_Menu {

    /**
     * Constructor
     */
    public function __construct() {
        add_action('admin_enqueue_scripts', array($this, 'enqueue_styles'));
        add_action('admin_footer', array($this, 'output_scripts'));
    }

    /**
     * Enqueue styles
     */
    public function enqueue_styles() {
        wp_add_inline_style('wp-admin', $this->get_styles());
    }

    /**
     * Output JavaScript enhancements
     */
    public function output_scripts() {
        ?>
        <script type="text/javascript">
        jQuery(document).ready(function($) {
            $('body').addClass('modern-admin-menu');
        });
        </script>
        <?php
    }

    /**
     * Get CSS styles
     */
    private function get_styles() {
        return "
        /* =====================================================
           MODERN ADMIN SIDEBAR MENU
           ===================================================== */

        /* Sidebar Container */
        .modern-admin-menu #adminmenuback,
        .modern-admin-menu #adminmenuwrap {
            background: linear-gradient(180deg, #1e1e2f 0%, #191927 100%);
        }

        .modern-admin-menu #adminmenu {
            margin: 6px 0;
        }

        /* Menu Separator */
        .modern-admin-menu #adminmenu li.wp-menu-separator {
            background: rgba(255,255,255,0.08);
            margin: 6px 8px;
        }

        /* Top Level Menu Items - Small padding */
        .modern-admin-menu #adminmenu li.menu-top > a {
            margin: 1px 6px;
            padding: 6px 8px;
            border-radius: 4px;
            transition: background 0.15s ease, color 0.15s ease;
        }

        .modern-admin-menu #adminmenu li.menu-top > a:hover {
            background: rgba(255,255,255,0.08);
        }

        .modern-admin-menu #adminmenu li.menu-top > a:focus {
            box-shadow: none;
        }

        /* Current/Active Menu Item */
        .modern-admin-menu #adminmenu li.current > a.menu-top,
        .modern-admin-menu #adminmenu li.wp-has-current-submenu > a.wp-has-current-submenu,
        .modern-admin-menu #adminmenu li.wp-menu-open > a.menu-top {
            background: linear-gradient(135deg, rgba(34, 113, 177, 0.9) 0%, rgba(19, 94, 150, 0.9) 100%);
            color: white;
        }

        /* Menu Icons */
        .modern-admin-menu #adminmenu .wp-menu-image::before {
            color: rgba(255,255,255,0.7);
            transition: color 0.15s ease;
        }

        .modern-admin-menu #adminmenu li.menu-top:hover .wp-menu-image::before,
        .modern-admin-menu #adminmenu li.current .wp-menu-image::before,
        .modern-admin-menu #adminmenu li.wp-has-current-submenu .wp-menu-image::before {
            color: white;
        }

        /* Menu Text */
        .modern-admin-menu #adminmenu .wp-menu-name {
            color: rgba(255,255,255,0.85);
            font-size: 13px;
            transition: color 0.15s ease;
        }

        .modern-admin-menu #adminmenu li.menu-top:hover .wp-menu-name,
        .modern-admin-menu #adminmenu li.current .wp-menu-name,
        .modern-admin-menu #adminmenu li.wp-has-current-submenu .wp-menu-name {
            color: white;
        }

        /* Notification Bubbles */
        .modern-admin-menu #adminmenu .awaiting-mod,
        .modern-admin-menu #adminmenu .update-plugins {
            background: #e74c3c !important;
            color: white !important;
            font-size: 9px;
            font-weight: 600;
            padding: 0 5px;
            min-width: 16px;
            height: 16px;
            line-height: 16px;
            border-radius: 8px;
            text-align: center;
        }

        .modern-admin-menu #adminmenu .update-plugins {
            background: #f39c12 !important;
        }

        /* Submenu Styling */
        .modern-admin-menu #adminmenu .wp-submenu {
            background: #252536;
            padding: 4px 0;
        }

        .modern-admin-menu #adminmenu .wp-not-current-submenu .wp-submenu {
            background: #252536;
            box-shadow: 2px 2px 8px rgba(0,0,0,0.25);
            border-radius: 0 6px 6px 0;
        }

        .modern-admin-menu #adminmenu .wp-submenu a {
            color: rgba(255,255,255,0.7) !important;
            padding: 5px 12px;
            font-size: 13px;
            transition: color 0.15s ease, background 0.15s ease;
        }

        .modern-admin-menu #adminmenu .wp-submenu a:hover {
            color: white !important;
            background: rgba(255,255,255,0.05);
        }

        .modern-admin-menu #adminmenu .wp-submenu li.current a {
            color: #72aee6 !important;
        }

        /* Open Submenu */
        .modern-admin-menu #adminmenu .wp-has-current-submenu .wp-submenu,
        .modern-admin-menu #adminmenu .wp-menu-open .wp-submenu {
            background: rgba(0,0,0,0.2);
            margin: 2px 6px 0 6px;
            padding: 4px 0;
            border-radius: 4px;
        }

        .modern-admin-menu #adminmenu .wp-has-current-submenu .wp-submenu a,
        .modern-admin-menu #adminmenu .wp-menu-open .wp-submenu a {
            padding: 4px 8px 4px 14px;
        }

        /* Collapse Button */
        .modern-admin-menu #collapse-menu {
            margin: 4px 6px;
            border-radius: 4px;
        }

        .modern-admin-menu #collapse-menu:hover {
            background: rgba(255,255,255,0.08);
        }

        .modern-admin-menu #collapse-button div::after {
            color: rgba(255,255,255,0.5);
        }

        /* Hide menu arrow */
        .modern-admin-menu #adminmenu .wp-menu-arrow,
        .modern-admin-menu #adminmenu .wp-has-current-submenu .wp-menu-arrow {
            display: none;
        }

        /* =====================================================
           MODERN ADMIN TOOLBAR - Rounded Dropdowns
           ===================================================== */

        /* Toolbar Container */
        .modern-admin-menu #wpadminbar {
            background: linear-gradient(90deg, #1e1e2f 0%, #252538 100%);
            box-shadow: 0 1px 4px rgba(0,0,0,0.15);
        }

        /* Toolbar Items */
        .modern-admin-menu #wpadminbar .ab-top-menu > li > a,
        .modern-admin-menu #wpadminbar .ab-top-menu > li > .ab-item {
            color: rgba(255,255,255,0.85) !important;
            transition: all 0.15s ease;
            border-radius: 4px;
            margin: 0 2px;
        }

        .modern-admin-menu #wpadminbar .ab-top-menu > li:hover > a,
        .modern-admin-menu #wpadminbar .ab-top-menu > li:hover > .ab-item,
        .modern-admin-menu #wpadminbar .ab-top-menu > li.hover > a {
            background: rgba(255,255,255,0.1) !important;
            color: white !important;
        }

        /* Toolbar Icons */
        .modern-admin-menu #wpadminbar .ab-icon::before,
        .modern-admin-menu #wpadminbar .ab-item::before {
            color: rgba(255,255,255,0.75) !important;
            transition: color 0.15s ease;
        }

        .modern-admin-menu #wpadminbar li:hover .ab-icon::before,
        .modern-admin-menu #wpadminbar li:hover .ab-item::before {
            color: white !important;
        }

        /* Toolbar Dropdowns - ROUNDED with spacing */
        .modern-admin-menu #wpadminbar .ab-sub-wrapper {
            background: #252536;
            box-shadow: 0 4px 20px rgba(0,0,0,0.3);
            border-radius: 8px;
            margin-top: 6px;
            overflow: hidden;
        }

        .modern-admin-menu #wpadminbar .ab-submenu {
            background: transparent;
            padding: 6px 0;
        }

        .modern-admin-menu #wpadminbar .ab-submenu .ab-item {
            color: rgba(255,255,255,0.85) !important;
            padding: 8px 14px !important;
            transition: all 0.15s ease;
        }

        .modern-admin-menu #wpadminbar .ab-submenu .ab-item:hover {
            background: rgba(255,255,255,0.08) !important;
            color: white !important;
        }

        /* User Avatar */
        .modern-admin-menu #wpadminbar #wp-admin-bar-my-account img.avatar {
            border: 2px solid rgba(255,255,255,0.2);
            border-radius: 50%;
        }

        /* Notification Badges in Toolbar */
        .modern-admin-menu #wpadminbar .ab-item .ab-label,
        .modern-admin-menu #wpadminbar #wp-admin-bar-updates .ab-label {
            background: #e74c3c !important;
            border-radius: 8px;
            font-size: 9px;
            padding: 2px 5px;
        }

        /* Howdy Text */
        .modern-admin-menu #wpadminbar #wp-admin-bar-my-account .display-name {
            color: rgba(255,255,255,0.9);
        }

        /* New Content Button */
        .modern-admin-menu #wpadminbar #wp-admin-bar-new-content:hover > a {
            background: rgba(34, 113, 177, 0.4) !important;
        }

        /* =====================================================
           PLUGIN ICON FIXES
           ===================================================== */

        .modern-admin-menu #adminmenu .wp-menu-image.svg {
            background-size: 20px 20px;
            background-position: center;
        }

        .modern-admin-menu #wpadminbar .blavatar {
            border-radius: 3px;
        }

        /* =====================================================
           RESPONSIVE
           ===================================================== */

        @media screen and (max-width: 782px) {
            .modern-admin-menu #wpadminbar {
                position: fixed;
            }
        }
        ";
    }
}
