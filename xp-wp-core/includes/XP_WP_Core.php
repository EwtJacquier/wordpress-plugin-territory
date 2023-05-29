<?php

/**
 * XP WP Core
 *
 * @author Ewerton
 */
class XP_WP_Core{
    /**
     * Instances of this class and child classes.
     *
     * @var array
     */
    protected static $instances = array();

    /**
     * Registered visual components
     *
     * @var array
     */
    protected $registered_components;

    /**
     * Plugin Prefix
     *
     * @var string
     */
    protected $plugin_prefix;

    /**
     * Text Domain
     *
     * @var string
     */
    protected $text_domain;

    /**
     * Initialize the plugin
     *
     * @var array
     * @var array
     */
    protected function __construct() {
        // Set prefix and textdomain names based on the class called
        $prefix = strtolower(get_called_class());

        $this->set_plugin_prefix($prefix);
        $this->set_text_domain(str_replace('_','-', $prefix));

        // Load plugin text domain file
        $this->load_textdomain();

        // Load support classes
        $this->load_helpers();

        // Load admin pages
        $this->load_admin_pages();

        // Load components
        $this->load_components();

        // Register activation, deactivation and uninstall hooks (for parent and child classes)
        register_activation_hook( __FILE__, function(){
            $this->on_deactivation();
        } );

        register_deactivation_hook( __FILE__, function(){
            $this->on_activation();
        } );

        /* Verificar, está com erro
        register_uninstall_hook( __FILE__, function(){
            $this->on_uninstall();
        } );
        */
    }

    public function get_plugin_folder(){
        $dir = WP_PLUGIN_DIR . '/' . $this->get_text_domain() . '/';

        if ( file_exists($dir) ){
            return $dir;
        }
        else{
            return false;
        }
    }

    /**
     * Load the plugin text domain for translation.
     *
     * @return void
     */
    protected function load_textdomain() {
        $languages_directory = $this->get_plugin_folder() . 'languages/';

        load_plugin_textdomain( $this->get_text_domain(), false, $languages_directory );
    }

    /**
     * Return an instance of this class.
     *
     * @return object A single instance of this class.
     */
    public static function get_instance(){
        // Get the current child / parent class
        $instance_class = get_called_class();

        // If the called class are not instantiated, instantiate
        if ( !isset( static::$instances[$instance_class] ) ){
            static::$instances[$instance_class] = new $instance_class();
        }

        // Return the request class without redeclare it (construct it)
        return static::$instances[$instance_class];
    }

    /**
     * When plugin is activated
     */
    protected function on_activation(){
        if ( ! current_user_can( 'activate_plugins' ) )
            return;

        $plugin = isset( $_REQUEST['plugin'] ) ? $_REQUEST['plugin'] : '';

        if ( ! $plugin )
            return;

        check_admin_referer( "activate-plugin_{$plugin}" );
    }

    /**
     * When plugin is deactivated
     */
    protected function on_deactivation(){
        if ( ! current_user_can( 'activate_plugins' ) )
            return;

        $plugin = isset( $_REQUEST['plugin'] ) ? $_REQUEST['plugin'] : '';

        if ( ! $plugin )
            return;

        check_admin_referer( "deactivate-plugin_{$plugin}" );
    }

    /**
     * When plugin is uninstalled
     */
    protected function on_uninstall(){
        if ( ! current_user_can( 'activate_plugins' ) )
            return;

        check_admin_referer( 'bulk-plugins' );

        if ( __FILE__ != WP_UNINSTALL_PLUGIN )
            return;
    }

    protected function load_helpers(){
        $helper_directory = $this->get_plugin_folder() . 'includes/';

        // Load auxiliary and third classes are in the class directory
        foreach ( glob( $helper_directory . "*.php" ) as $filename ){
            require_once $filename;
        }
    }

    protected function load_components( $components = array() ){
        if ( $components ){
            $registered_components = array();

            // Register enqueue hooks for registered components
            foreach ( $components as $component ){

                // Verify if component is valid
                if ( $component instanceof XP_WP_Core_Component ){
                    $registered_components[] = $component;
                }

            }

            $this->set_registered_components( $components );
        }
    }

    protected function load_admin_pages( $admin_pages = array() ){
        if ( $this->get_plugin_prefix() === 'xp_wp_core' ){
            $admin_pages[] = new XP_WP_Core_Admin_Page(
                'config-xp-core',
                'options-general.php',
                __('Configurações XP Core', $this->get_text_domain(), 'xp-wp-quotes'),
                __('Configurações XP Core', $this->get_text_domain(), 'xp-wp-quotes'),
                'administrator'
            );
        }

        if ( $admin_pages ){
            foreach ( $admin_pages as $page ){
                XP_WP_Core_Admin_Page_Loader::load_options_page( $page, $this );
            }
        }
    }

    public function get_text_domain(){
        return $this->text_domain;
    }

    protected function set_text_domain( $text_domain ){
        $this->text_domain = $text_domain;
    }

    public function get_plugin_prefix(){
        return $this->plugin_prefix;
    }

    protected function set_plugin_prefix( $plugin_prefix ){
        $this->plugin_prefix = $plugin_prefix;
    }

    protected function get_registered_components(){
        return $this->registered_components;
    }

    protected function set_registered_components( $registered_components ){
        $this->registered_components = $registered_components;
    }

    protected function get_component( $component_name ){

        // Loop through the registered components
        foreach ( $this->get_registered_components() as $component ){

            // Find the registered component by name
            if ( $component->get_name() === $component_name ){
                return $component;
            }
        }

        return null;
    }

    public function enqueue( ...$component_names ){
        if ( $component_names ){
            $component_arr = array();

            foreach ( $component_names as $name ){
                $component = $this->get_component( $name );

                if ( $component ){
                    $component_arr[] = $component;
                }
            }

            if ( $component_arr ){
                XP_WP_Core_Component_Loader::enqueue( $this , $component_arr );
            }
        }
    }

    public function render( $component_name ){
        $component = $this->get_component( $component_name );

        if ( $component ){
            XP_WP_Core_Component_Loader::render( $component, $this );
        }
    }
}