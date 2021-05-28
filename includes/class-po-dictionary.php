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

    // main method for creating a cURL request
    function request( $args , $endpoint = "count", $prospector = false ){
        
        $json = json_encode( $args );

        $ch = curl_init();

        $headers = [
            'Content-Type: application/json',                    
            'Content-Length: ' . strlen( $json ),
        ];
        
        $options = [
            CURLOPT_URL             => ( $prospector ? $this->prospector_url : $this->dictionary_url ) . 'api/v1/' . $endpoint,
            CURLOPT_POST            => 1,
            CURLOPT_POSTFIELDS      => $json,
            CURLOPT_RETURNTRANSFER  => true,
            CURLOPT_HTTPHEADER      => $headers,
        ];

        curl_setopt_array($ch, $options);

        $server_output = curl_exec($ch);

        curl_close($ch);
        
        $response = json_decode( $server_output );
        
        write_log( $response, "SOSPO_Dictionary-request-response-" . $endpoint );
        
        if( $response->status == "success" && ! empty( $response->data ) ){
            
            return $response->data;
        }
        
        return new \WP_Error( 'server', $response->message );
    }
    
    // main method for getting the count of the filters in the collection
    function count( $args, $prospector = false ){
        
        $data = $this->request( $args, "count", $prospector );
        
        if( ! is_wp_error( $data ) ){
            
            $data = $data->count;
        }
        
        return $data;
    }
    
    // main method for getting the filters from the collection
    function get( $args, $prospector = false  ){
        
        $data = $this->request( $args, "get", $prospector );
        
        if( ! is_wp_error( $data ) ){
            
            $data = $data->filters;
        }
        
        return $data;
    }
    
    
    
    function get_prospector_count(){
        
        $menu_endpoints = get_option( "po_admin_menu_list" );
        
        $args = [
            "query" => [
                'endpoint' =>  [
                    '$in' => array_values( $menu_endpoints["endpoints"] )
                ],
            ],
        ];
        
        
        $args = $this->get_benefit_filters_query();
        
        write_log( json_encode( $args["query"] ), "SOSPO_Dictionary-get_prospector_count-query" );
        
        $count = $this->count( $args, true );
        
        return is_wp_error( $count ) ? "unknown" : $count;
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

    private function get_benefit_filters_query(){
        
        if ( ! function_exists( 'get_plugins' ) ) {
            require_once ABSPATH . 'wp-admin/includes/plugin.php';
        }
        
        $plugins = get_plugins();
        
        $args = [
            "query" => [
                '$and' => [
                    [ 'belongsTo' => [ '$in' => array_merge( array_keys( $plugins ), [ '_core' ] ) ] ],
                    // [ 'status' => 'pending' ],
                    [ 'status' => 'approved' ],
                ],
            ],
        ];
        
        // $args = [
            // "query" => [
                // '$and' => [
                    // [ '$or' => [
                        // [ 'belongsTo' => [ '$in' => array_keys( $plugins ) ] ],
                        // [ 'belongsTo' => '_core' ],
                    // ] ],
                    // [ 'status' => 'approved' ],
                // ],
            // ],
        // ];
        
        // $args = [
            // "query" => [
                // 'belongsTo' => [ '$in' => array_merge( array_keys( $plugins ), [ '_core' ] ) ],
            // ],
        // ];
        
        
        
        // TODO We need to add the 'status' => 'approved' as a condition
        
        return $args;
    }
}

function sospo_dictionary(){
    return SOSPO_Dictionary::get_instance();
}
sospo_dictionary();
