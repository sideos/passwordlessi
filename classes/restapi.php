<?php

class SideosRestAPI {
    /* ********************* REST API TO RE-ENABLE SUBMIT USERAME PASSWORD ***************/
    /* You need the SSI token in order to make this happen */

    function ssi_enable_submit( WP_REST_Request $request ) {
        $token = $request->get_header('X-Token');
        $response = new WP_REST_Response();
        if ($token !== '' && $token === get_option('token', '')) {
            update_option('disablesubmit', '');
            $response->set_status( 200 );
        } else {
            $response->set_status( 403 );
        }
        return $response;
    }

    function initAPI() {
        add_action( 'rest_api_init', function () {
            register_rest_route( 'sideos-ssi/v1', '/enable', array(
                'methods' => 'POST',
                'callback' => [$this, 'ssi_enable_submit'],
            ) );
        } );
    }
}
