<?php

/**
 * XP WP Core Child
 *
 * @author Ewerton
 */
class XP_WP_Quotes extends XP_WP_Core {

    // Override the load_components class, which is called in XP_WP_Core::__construct() method, after the plugin is loaded
    protected function load_components( $components = array() )
    {
        $components[] = new XP_WP_Core_Component('quotes-bar',    array( $this, 'regra_negocio' ));
        $components[] = new XP_WP_Core_Component('quotes-widget', array( $this, 'regra_negocio' ));

        parent::load_components( $components );
    }

    public function regra_negocio(){
        $args = array(
            'valor' => 1
        );

        return $args;
    }
}