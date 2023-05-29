<?php

class XP_WP_Core_Component_Loader
{
    public static function get_template_path( XP_WP_Core_Component $component, XP_WP_Core $instance ){
        // Find the component root folder
        $template_root = $instance->get_plugin_folder() . 'components/' . $component->get_name() . '/';

        // Search for the component html (also verify html override)
        $template_path = static::locate_template( $component->get_name(), $template_root, $instance->get_text_domain() );

        return $template_path;
    }

    public static function enqueue( XP_WP_Core $instance, $components ){
        if ( $components ){
            foreach ( $components as $component ){
                if ( $component instanceof XP_WP_Core_Component ){
                    $template_path = static::get_template_path( $component, $instance );

                    if ( $template_path ){

                        // Enqueue the component css and js dependencies
                        static::enqueue_template( $template_path['path_url'], $component, $instance );
                    }
                }
            }
        }
    }

    public static function render( XP_WP_Core_Component $component, XP_WP_Core $instance ){
        $template_path = static::get_template_path( $component, $instance );

        if ( $template_path ){
            $args = array();

            // Get the callback component data
            $render_data = $component->get_render_data();

            if ( is_array( $render_data ) ){
                $args = array_merge( $args, $render_data );
            }

            $args['text_domain'] = $instance->get_text_domain();

            // Recover the component HTML content
            load_template( $template_path['file_path'], false, $args );
        }
    }

    public static function enqueue_template( $template_path, XP_WP_Core_Component $component, XP_WP_Core $instance ){
        add_action( 'wp_enqueue_scripts', function() use ($instance, $component, $template_path) {
            $component_tag_id = $instance->get_text_domain() . '-' . $component->get_name();
            $component_hook_name = $instance->get_plugin_prefix() . '_' . str_replace('-','_', $component->get_name());

            if ( $component->has_css() ){
                wp_register_style( $component_tag_id, $template_path . 'assets/css/main.css' );
                wp_enqueue_style( $component_tag_id );

                // Call the "after enqueue hook" ( {xp_wp_core}_quotes_bar_after_style_enqueue )
                do_action( $component_hook_name . '_after_style_enqueue' );
            }

            if ( $component->has_js() ){
                wp_register_script( $component_tag_id, $template_path . 'assets/js/main.js', array('jquery') );
                wp_enqueue_script( $component_tag_id );

                // Call the "after enqueue hook" ( {xp_wp_core}_quotes_bar_after_script_enqueue )
                do_action($component_hook_name . '_after_script_enqueue' );
            }
        });
    }

    /**
     * Locate a given template and return its path
     *
     * Search Order:
     * 1. /themes/xp-theme/xp-wp-{core/child}/templates/$template_name
     * 2. /plugins/xp-wp-{core/child}/templates/$template_name.
     *
     * @since 0.1
     * @param 	string 	$template_name	Template to load.
     * @param 	string 	$template_path	Path to templates.
     * @param 	string 	$text_domain	Path to templates.
     * @return  array
     */
    public static function locate_template( $template_name, $template_path , $text_domain ) {
        $component_folder = $text_domain . '/components/' . $template_name . '/';

        $theme_template_path = $component_folder . $template_name . '.php';

        $final_path_url = plugins_url( $component_folder );

        // Search template file in theme folder.
        $template = locate_template( array(
            $theme_template_path,
            false,
            false
        ) );

        // Get plugins template file.
        $file = $template_path . $template_name . '.php';

        // If the file exists in the plugin folder
        if (file_exists($file)){
            // If the template is not on theme folder, get from plugin folder
            if (!$template){
                $template = $file;
            }
        }
        else{
            $template = false;
        }

        // If the template file doesnt exists, shows the message box default component
        if ( ! $template ){
            return null;
        }

        return array(
            'file_path' => $template,
            'path_url' => $final_path_url
        );
    }
}