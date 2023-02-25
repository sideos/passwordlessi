<?php

class SideosSendAction {

    function send_credential() {
        try {
            $SIDEOS_URL = get_option('sideosurl', '');
            $TOKEN = get_option( 'token', '' );
            $TEMPLATEID = get_option('templateID', '');
        
            if ($TOKEN === '' || $SIDEOS_URL === '') {
                echo json_encode(array('error_code'=>1));
                exit();
            }
            $postParameter = array(
                'name' => sanitize_text_field($_POST['name']),
                'email' => sanitize_text_field($_POST['email']),
                'website' => get_site_url(),
                'templateID' => $TEMPLATEID
            );

            $args = array(
                'headers' => array(
                    'X-Token' => $TOKEN,
                    'Content-Type' => 'application/json'
                ),
                'body' => wp_json_encode( $postParameter )
            );
            $response = wp_remote_post( "$SIDEOS_URL/send", $args );
            $http_code = wp_remote_retrieve_response_code( $response );
            if ($http_code !== 200) {
                echo json_encode(array('error_code'=>1));
                exit();
            }
            echo json_encode(array('error_code'=>0));
        } catch(Exception $e) {
            echo json_encode(array('error_code'=>1));
        }
        exit();
    }
    
    function initAction() {
        add_action('wp_ajax_send_credential',[$this, 'send_credential']);
    }
}