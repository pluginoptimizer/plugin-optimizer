<?php

class SOSPO_Dictionary{

    protected static $instance = null;

    private function __construct() {
        
        
        
    }

    static function get_instance() {
        
        if( self::$instance == null ){
            self::$instance = new self();
        }

        return self::$instance;
        
    }

}

function sospo_dictionary(){
    return SOSPO_Dictionary::get_instance();
}
sospo_dictionary();
