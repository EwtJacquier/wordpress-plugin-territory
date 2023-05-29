<?php

class XP_WP_Core_Admin_Page_Loader {

    public static function load_options_page( XP_WP_Core_Admin_Page $page, XP_WP_Core $instance ){
        $admin_page_root = plugins_url( $instance->get_text_domain() . '/admin-pages/' . $page->get_page_slug() . '/' );

        add_action( 'admin_enqueue_scripts', function() use ( $page, $admin_page_root, $instance ){
            global $current_screen;

            // Load scripts only in the correct screen
            if ( $current_screen->base === 'settings_page_' . $page->get_page_slug() ){
                $page_tag_id = $instance->get_text_domain() . '-' . $page->get_page_slug();
                $page_hook_name = $instance->get_plugin_prefix() . '_' . str_replace('-','_', $page->get_page_slug());

                if ( $page->has_css() ){
                    wp_register_style( $page_tag_id , $admin_page_root . 'assets/css/main.css' );
                    wp_enqueue_style( $page_tag_id );

                    // Call the "after enqueue hook"
                    do_action( $page_hook_name . '_after_style_enqueue' );
                }

                if ( $page->has_js() ){
                    wp_register_script( $page_tag_id, $admin_page_root . 'assets/js/main.js', array('jquery') );
                    wp_enqueue_script( $page_tag_id );

                    // Call the "after enqueue hook"
                    do_action($page_hook_name . '_after_script_enqueue' );
                }

            }
        } );

        add_action('admin_menu', function() use ( $page , $admin_page_root ){
            add_submenu_page(
                $page->get_parent_slug(),
                $page->get_page_title(),
                $page->get_menu_title(),
                $page->get_capability(),
                $page->get_page_slug(),
                function() use ( $page , $admin_page_root ){
                    $admin_page_file =  $admin_page_root. $page->get_page_slug() . '.php';

                    if (file_exists( $admin_page_file )){
                        require_once $admin_page_file;
                    }
                }
            );
        });
    }
}