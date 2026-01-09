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
            // Add modern class to body
            $('body').addClass('modern-admin-menu');

            // Enhance menu items with smooth transitions
            $('#adminmenu li.menu-top').each(function() {
                var $item = $(this);

                // Add ripple effect container
                if (!$item.find('.menu-ripple').length) {
                    $item.find('> a').append('<span class="menu-ripple"></span>');
                }
            });

            // Smooth submenu transitions
            $('#adminmenu li.menu-top').on('mouseenter', function() {
                var $submenu = $(this).find('.wp-submenu');
                if ($submenu.length && !$(this).hasClass('wp-menu-open')) {
                    $submenu.stop().css('opacity', 0).animate({opacity: 1}, 150);
                }
            });

            // Add active indicator animation
            var $currentItem = $('#adminmenu li.current');
            if ($currentItem.length) {
                $currentItem.addClass('modern-active');
            }

            // Toolbar enhancements
            $('#wpadminbar').addClass('modern-toolbar');

            // Add hover effects to toolbar items
            $('#wp-admin-bar-root-default > li, #wp-admin-bar-top-secondary > li').each(function() {
                $(this).addClass('modern-toolbar-item');
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
        /* =====================================================
           MODERN ADMIN SIDEBAR MENU
           ===================================================== */

        /* Sidebar Container */
        .modern-admin-menu #adminmenuback,
        .modern-admin-menu #adminmenuwrap {
            background: linear-gradient(180deg, #1e1e2f 0%, #191927 100%);
        }

        .modern-admin-menu #adminmenu {
            margin: 12px 0;
        }

        /* Menu Separator */
        .modern-admin-menu #adminmenu li.wp-menu-separator {
            height: 1px;
            margin: 8px 12px;
            background: rgba(255,255,255,0.08);
        }

        /* Top Level Menu Items */
        .modern-admin-menu #adminmenu li.menu-top > a {
            display: flex;
            align-items: center;
            margin: 2px 8px;
            padding: 10px 12px;
            border-radius: 8px;
            transition: all 0.2s ease;
            position: relative;
            overflow: hidden;
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
        .modern-admin-menu #adminmenu li.wp-menu-open > a {
            background: linear-gradient(135deg, rgba(34, 113, 177, 0.9) 0%, rgba(19, 94, 150, 0.9) 100%);
            color: white;
        }

        .modern-admin-menu #adminmenu li.current > a.menu-top::before,
        .modern-admin-menu #adminmenu li.wp-has-current-submenu > a::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 3px;
            height: 24px;
            background: white;
            border-radius: 0 3px 3px 0;
        }

        /* Menu Icons */
        .modern-admin-menu #adminmenu .wp-menu-image {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            margin-right: 10px;
            border-radius: 8px;
            background: rgba(255,255,255,0.05);
            transition: all 0.2s ease;
        }

        .modern-admin-menu #adminmenu li.menu-top:hover .wp-menu-image {
            background: rgba(255,255,255,0.1);
        }

        .modern-admin-menu #adminmenu li.current .wp-menu-image,
        .modern-admin-menu #adminmenu li.wp-has-current-submenu .wp-menu-image {
            background: rgba(255,255,255,0.2);
        }

        .modern-admin-menu #adminmenu .wp-menu-image img {
            padding: 0;
        }

        .modern-admin-menu #adminmenu .wp-menu-image::before {
            color: rgba(255,255,255,0.7);
            transition: color 0.2s ease;
        }

        .modern-admin-menu #adminmenu li.menu-top:hover .wp-menu-image::before,
        .modern-admin-menu #adminmenu li.current .wp-menu-image::before,
        .modern-admin-menu #adminmenu li.wp-has-current-submenu .wp-menu-image::before {
            color: white;
        }

        /* Menu Text */
        .modern-admin-menu #adminmenu .wp-menu-name {
            color: rgba(255,255,255,0.8);
            font-size: 13px;
            font-weight: 500;
            transition: color 0.2s ease;
        }

        .modern-admin-menu #adminmenu li.menu-top:hover .wp-menu-name,
        .modern-admin-menu #adminmenu li.current .wp-menu-name,
        .modern-admin-menu #adminmenu li.wp-has-current-submenu .wp-menu-name {
            color: white;
        }

        /* Notification Bubbles */
        .modern-admin-menu #adminmenu .awaiting-mod,
        .modern-admin-menu #adminmenu .update-plugins {
            background: #e74c3c;
            color: white;
            font-size: 10px;
            font-weight: 600;
            padding: 2px 6px;
            border-radius: 10px;
            margin-left: auto;
            min-width: auto;
        }

        .modern-admin-menu #adminmenu .update-plugins {
            background: #f39c12;
        }

        /* Submenu Styling */
        .modern-admin-menu #adminmenu .wp-submenu {
            background: #252536;
            border-radius: 0 8px 8px 0;
            box-shadow: 4px 4px 20px rgba(0,0,0,0.3);
            padding: 8px 0;
            margin-left: 0;
            left: 160px;
        }

        .modern-admin-menu #adminmenu .wp-submenu-wrap {
            border-radius: 0 8px 8px 0;
            overflow: hidden;
        }

        .modern-admin-menu #adminmenu .wp-submenu li {
            margin: 0;
        }

        .modern-admin-menu #adminmenu .wp-submenu a {
            color: rgba(255,255,255,0.7);
            padding: 8px 16px;
            font-size: 13px;
            transition: all 0.15s ease;
        }

        .modern-admin-menu #adminmenu .wp-submenu a:hover {
            color: white;
            background: rgba(255,255,255,0.08);
        }

        .modern-admin-menu #adminmenu .wp-submenu li.current a {
            color: #5eb5ef;
            font-weight: 500;
        }

        /* Submenu Header */
        .modern-admin-menu #adminmenu .wp-submenu .wp-submenu-head {
            color: rgba(255,255,255,0.5);
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 10px 16px 6px;
            border-bottom: 1px solid rgba(255,255,255,0.08);
            margin-bottom: 4px;
        }

        /* Open Submenu (Flyout) */
        .modern-admin-menu #adminmenu .wp-menu-open .wp-submenu {
            position: relative;
            left: 0;
            top: 0;
            box-shadow: none;
            background: rgba(0,0,0,0.15);
            border-radius: 0;
            margin: 4px 8px 0 8px;
            padding: 4px 0;
            border-radius: 6px;
        }

        .modern-admin-menu #adminmenu .wp-menu-open .wp-submenu a {
            padding: 6px 12px 6px 24px;
        }

        /* Collapse Button */
        .modern-admin-menu #collapse-menu {
            margin: 8px;
            border-radius: 8px;
            transition: all 0.2s ease;
        }

        .modern-admin-menu #collapse-menu:hover {
            background: rgba(255,255,255,0.08);
        }

        .modern-admin-menu #collapse-button {
            border-radius: 6px;
        }

        .modern-admin-menu #collapse-button div::after {
            color: rgba(255,255,255,0.5);
        }

        /* Collapsed Menu State */
        .modern-admin-menu.folded #adminmenu li.menu-top > a {
            margin: 2px 4px;
            padding: 8px;
            justify-content: center;
        }

        .modern-admin-menu.folded #adminmenu .wp-menu-image {
            margin-right: 0;
        }

        .modern-admin-menu.folded #adminmenu .wp-submenu {
            left: 36px;
        }

        /* =====================================================
           MODERN ADMIN TOOLBAR
           ===================================================== */

        /* Toolbar Container */
        .modern-admin-menu #wpadminbar {
            background: linear-gradient(90deg, #1e1e2f 0%, #252538 100%);
            box-shadow: 0 2px 10px rgba(0,0,0,0.15);
        }

        .modern-admin-menu #wpadminbar .ab-top-menu > li {
            margin: 0 2px;
        }

        /* Toolbar Items */
        .modern-admin-menu #wpadminbar .ab-top-menu > li > a,
        .modern-admin-menu #wpadminbar .ab-top-menu > li > .ab-item {
            padding: 0 12px;
            height: 32px;
            line-height: 32px;
            border-radius: 6px;
            transition: all 0.2s ease;
            color: rgba(255,255,255,0.8);
        }

        .modern-admin-menu #wpadminbar .ab-top-menu > li:hover > a,
        .modern-admin-menu #wpadminbar .ab-top-menu > li:hover > .ab-item,
        .modern-admin-menu #wpadminbar .ab-top-menu > li.hover > a {
            background: rgba(255,255,255,0.1);
            color: white;
        }

        /* Toolbar Icons */
        .modern-admin-menu #wpadminbar .ab-icon::before,
        .modern-admin-menu #wpadminbar .ab-item::before {
            color: rgba(255,255,255,0.7);
            transition: color 0.2s ease;
        }

        .modern-admin-menu #wpadminbar li:hover .ab-icon::before,
        .modern-admin-menu #wpadminbar li:hover .ab-item::before {
            color: white;
        }

        /* WordPress Logo */
        .modern-admin-menu #wpadminbar #wp-admin-bar-wp-logo > .ab-item {
            padding: 0 8px;
        }

        .modern-admin-menu #wpadminbar #wp-admin-bar-wp-logo > .ab-item .ab-icon::before {
            font-size: 20px;
        }

        /* Site Name */
        .modern-admin-menu #wpadminbar #wp-admin-bar-site-name > a {
            font-weight: 500;
        }

        /* Toolbar Submenus */
        .modern-admin-menu #wpadminbar .ab-sub-wrapper {
            background: #252536;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.25);
            overflow: hidden;
            margin-top: 4px;
        }

        .modern-admin-menu #wpadminbar .ab-submenu {
            background: transparent;
            padding: 6px 0;
        }

        .modern-admin-menu #wpadminbar .ab-submenu .ab-item {
            padding: 8px 16px;
            color: rgba(255,255,255,0.8);
            transition: all 0.15s ease;
        }

        .modern-admin-menu #wpadminbar .ab-submenu .ab-item:hover {
            background: rgba(255,255,255,0.1);
            color: white;
        }

        /* Search in Toolbar */
        .modern-admin-menu #wpadminbar #adminbarsearch {
            padding: 0 8px;
        }

        .modern-admin-menu #wpadminbar #adminbarsearch .adminbar-input {
            background: rgba(255,255,255,0.1);
            border: none;
            border-radius: 6px;
            padding: 4px 12px;
            color: white;
            transition: all 0.2s ease;
        }

        .modern-admin-menu #wpadminbar #adminbarsearch .adminbar-input:focus {
            background: rgba(255,255,255,0.15);
            outline: none;
            box-shadow: 0 0 0 2px rgba(255,255,255,0.2);
        }

        /* User Account Menu */
        .modern-admin-menu #wpadminbar #wp-admin-bar-my-account {
            margin-right: 4px;
        }

        .modern-admin-menu #wpadminbar #wp-admin-bar-my-account > a {
            padding: 0 8px;
        }

        .modern-admin-menu #wpadminbar #wp-admin-bar-my-account img.avatar {
            border: 2px solid rgba(255,255,255,0.2);
            border-radius: 50%;
            margin: -2px 6px -2px 0;
            width: 26px;
            height: 26px;
        }

        /* Notification Badges in Toolbar */
        .modern-admin-menu #wpadminbar .ab-item .ab-label,
        .modern-admin-menu #wpadminbar #wp-admin-bar-updates .ab-label {
            background: #e74c3c;
            border-radius: 10px;
            font-size: 10px;
            padding: 2px 6px;
            margin-left: 4px;
        }

        .modern-admin-menu #wpadminbar #wp-admin-bar-comments .ab-icon {
            margin-right: 0;
        }

        /* Howdy Text */
        .modern-admin-menu #wpadminbar #wp-admin-bar-my-account .display-name {
            color: rgba(255,255,255,0.9);
        }

        /* Right Side Secondary Menu */
        .modern-admin-menu #wpadminbar #wp-admin-bar-top-secondary {
            display: flex;
            align-items: center;
        }

        /* New Content Button */
        .modern-admin-menu #wpadminbar #wp-admin-bar-new-content > a {
            background: rgba(34, 113, 177, 0.3);
        }

        .modern-admin-menu #wpadminbar #wp-admin-bar-new-content:hover > a {
            background: rgba(34, 113, 177, 0.5);
        }

        /* View Site Button */
        .modern-admin-menu #wpadminbar #wp-admin-bar-view-site > a,
        .modern-admin-menu #wpadminbar #wp-admin-bar-site-name > a {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        /* =====================================================
           RESPONSIVE ADJUSTMENTS
           ===================================================== */

        @media screen and (max-width: 782px) {
            .modern-admin-menu #wpadminbar {
                position: fixed;
            }

            .modern-admin-menu #adminmenu li.menu-top > a {
                margin: 2px 4px;
                padding: 12px 8px;
            }

            .modern-admin-menu #adminmenu .wp-submenu {
                left: 0;
                position: relative;
                border-radius: 0;
            }
        }

        /* =====================================================
           ANIMATIONS
           ===================================================== */

        @keyframes menuItemPulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.02); }
            100% { transform: scale(1); }
        }

        .modern-admin-menu #adminmenu li.menu-top.modern-active > a {
            animation: menuItemPulse 0.3s ease;
        }

        /* Ripple Effect */
        .modern-admin-menu .menu-ripple {
            position: absolute;
            border-radius: 50%;
            background: rgba(255,255,255,0.3);
            transform: scale(0);
            animation: ripple 0.6s linear;
            pointer-events: none;
        }

        @keyframes ripple {
            to {
                transform: scale(4);
                opacity: 0;
            }
        }

        /* =====================================================
           PLUGIN-SPECIFIC FIXES
           ===================================================== */

        /* Fix for plugins with custom icons */
        .modern-admin-menu #adminmenu .wp-menu-image.svg {
            background-size: 20px 20px;
            background-position: center;
        }

        /* Ensure dashicons display properly */
        .modern-admin-menu #adminmenu .wp-menu-image.dashicons-before::before {
            font-size: 20px;
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Admin Menu Arrow */
        .modern-admin-menu #adminmenu .wp-menu-arrow,
        .modern-admin-menu #adminmenu .wp-has-current-submenu .wp-menu-arrow {
            display: none;
        }

        /* Fix submenu positioning for collapsed state */
        .modern-admin-menu.folded #adminmenu li.menu-top:hover > .wp-submenu,
        .modern-admin-menu.folded #adminmenu li.menu-top.opensub > .wp-submenu {
            left: 36px;
            top: -8px;
        }

        /* Adjust content area */
        .modern-admin-menu #wpcontent,
        .modern-admin-menu #wpfooter {
            margin-left: 160px;
        }

        .modern-admin-menu.folded #wpcontent,
        .modern-admin-menu.folded #wpfooter {
            margin-left: 36px;
        }

        /* Site Icon in Toolbar */
        .modern-admin-menu #wpadminbar .blavatar {
            border-radius: 4px;
        }
        ";
    }
}
