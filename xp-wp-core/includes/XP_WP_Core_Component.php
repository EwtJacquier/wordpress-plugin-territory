<?php

class XP_WP_Core_Component {

    private $name;
    private $has_js;
    private $has_css;
    private $callback;

    public function __construct( $name, array $callback, $has_js = true, $has_css = true){
        $this->name = $name;
        $this->callback = $callback;
        $this->has_js = $has_js;
        $this->has_css = $has_css;
    }

    public function get_name(){
        return $this->name;
    }

    public function get_render_data(){
        if ( count($this->callback) === 2 && $this->callback[0] instanceof XP_WP_Core && is_string($this->callback[1]) ){
            $class = $this->callback[0];
            $method = $this->callback[1];

            if ( method_exists( $class, $method ) ){
                return $class->$method();
            }
        }

        return false;
    }

    public function has_js(){
        return $this->has_js;
    }

    public function has_css(){
        return $this->has_css;
    }
}