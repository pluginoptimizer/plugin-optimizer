<?php

class SOSPO_Dictionary{

    protected static $instance = null;
    
    protected $dictionary_url = 'https://po-dictionary.herokuapp.com/';
    protected $prospector_url = 'https://po-prospector.herokuapp.com/';

    private function __construct() {
        
        
        
    }

    static function get_instance() {
        
        if( self::$instance == null ){
            self::$instance = new self();
        }

        return self::$instance;
        
    }

    function retrieve( $dictionary_ids = [] ){
        
        $url  = empty( $dictionary_ids ) ? $this->dictionary_url . 'api/v1/retrieve' : $this->prospector_url . 'api/v1/retrieveById';
        
        $json = empty( $dictionary_ids ) ? json_encode( $dictionary_ids ) : json_encode( [ "ids" => $dictionary_ids, "columns" => [ "_id", "status" ], ] );
        
        $ch   = curl_init();

        $headers = [
            'Content-Type: application/json',                    
            'Content-Length: ' . strlen( $json ),
        ];
        
        $options = [
            CURLOPT_URL             => $url,
            CURLOPT_POST            => 1,
            CURLOPT_POSTFIELDS      => $json,
            CURLOPT_RETURNTRANSFER  => true,
            CURLOPT_HTTPHEADER      => $headers,
        ];

        curl_setopt_array($ch, $options);

        $server_output = curl_exec($ch);

        curl_close($ch);
        
        return $server_output;
    }

    function get_relevant_filters(){
        
        // sospo_mu_plugin()->write_log( array_keys( get_plugins() ), "test-123-get_plugins" );
        
        $send_out = [ 'belongsTo' => array_keys( get_plugins() ) ];
        
        return $this->retrieve( $send_out );
    }
    
    function get_pending_filters(){
        
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL,  PODICT_URL.'api/v1/pending');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $options = [
            CURLOPT_URL             => $this->dictionary_url . 'api/v1/pending',
            CURLOPT_RETURNTRANSFER  => true,
        ];

        curl_setopt_array($ch, $options);

        $server_output = curl_exec($ch);

        curl_close($ch);
        
        return $server_output;
    }
    
    function get_approved_filters( $index = 'all', $query = '' ){

        $send_out = [ 'index' => $index, 'query' => $query ];

        $json = json_encode( $send_out );

        $ch = curl_init();

        $headers = [
            'Content-Type: application/json',                    
            'Content-Length: ' . strlen( $json ),
        ];
        
        $options = [
            CURLOPT_URL             => $this->dictionary_url . 'api/v1/approved',
            CURLOPT_POST            => 1,
            CURLOPT_POSTFIELDS      => $json,
            CURLOPT_RETURNTRANSFER  => true,
            CURLOPT_HTTPHEADER      => $headers,
        ];

        curl_setopt_array($ch, $options);

        $server_output = curl_exec($ch);

        curl_close($ch);
        
        return $server_output;
    }

    function get_prospector_count(){
        
        $menu_endpoints = json_encode( get_option( "po_admin_menu_list" ) );
        
        // write_log( json_decode( $menu_endpoints ), "get_prospector_count-menu_endpoints" );

        $ch = curl_init();

        $headers = [
            'Content-Type: application/json',                    
            'Content-Length: ' . strlen( $menu_endpoints ),
        ];
        
        $options = [
            CURLOPT_URL             => $this->prospector_url . 'api/v1/count',
            CURLOPT_POST            => 1,
            CURLOPT_POSTFIELDS      => $menu_endpoints,
            CURLOPT_RETURNTRANSFER  => true,
            CURLOPT_HTTPHEADER      => $headers,
        ];

        curl_setopt_array($ch, $options);

        $server_output = curl_exec($ch);

        curl_close($ch);
        
        $response = json_decode( $server_output );
        
        $count = 0;
        
        if( $response->status == "success" && ! empty( $response->data->count ) ){
            
            $count = $response->data->count;
        }
        
        return $count;
    }
    
}

function sospo_dictionary(){
    return SOSPO_Dictionary::get_instance();
}
sospo_dictionary();
