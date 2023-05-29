<?php

class XP_WP_Core_Admin_Page {

    private $page_slug;
    private $parent_slug;
    private $page_title;
    private $menu_title;
    private $capability;
    private $has_js;
    private $has_css;

    public function __construct( $page_slug, $parent_slug, $page_title, $menu_title, $capability, $has_js = true, $has_css = true){
        $this->page_slug   = $page_slug;
        $this->parent_slug = $parent_slug;
        $this->page_title  = $page_title;
        $this->menu_title  = $menu_title;
        $this->capability  = $capability;
        $this->has_css     = $has_css;
        $this->has_js      = $has_js;
    }

    public function get_page_slug(){
        return $this->page_slug;
    }

    public function get_parent_slug(){
        return $this->parent_slug;
    }

    public function get_page_title(){
        return $this->page_title;
    }

    public function get_menu_title(){
        return $this->menu_title;
    }

    public function get_capability(){
        return $this->capability;
    }

    public function has_js(){
        return $this->has_js;
    }

    public function has_css(){
        return $this->has_css;
    }
}