<?php

class SideosLoginAction {

    function username_login() {
        try {
            $SIDEOS_URL = get_option('sideosurl', '');
            $TOKEN = get_option( 'token', '' );
            if ($TOKEN == '') {
                echo json_encode(array('error_code'=>1));
                exit();
            }
            // Automatic login //
            $challenge = sanitize_text_field($_POST['challenge']);

            $args = array(
                'headers' => array(
                    'X-Token' => $TOKEN,
                    'Content-Type' => 'application/json'
                )
            );
            $response = wp_remote_get( "$SIDEOS_URL/user/$challenge", $args );
            $http_code = wp_remote_retrieve_response_code( $response );

            if ($http_code !== 200) {
                echo json_encode(array('error_code'=>1));
                exit();
            }

            $body = wp_remote_retrieve_body( $response );

            $obj = json_decode($body, true);
            $obj = json_decode($obj['response'], true);
            $vc = $obj['verifiableCredential'][0];
            $cs = $vc['credentialSubject'];

            if ($vc['issuer']['id'] === get_option('did') && $cs['website'] === get_site_url()) {
                $user = get_user_by('email', $cs['email'] );
                if ( !is_wp_error( $user ) )
                {
                        /* CHECK THE DID...if None updates it... */
                    $did = esc_attr( get_the_author_meta( 'did', $user->ID ) );
                    if ($did === '') {
                            update_user_meta($user->ID, 'did', $cs['id']);
                    } else {
                        if ($did !== $cs['id']) {	// Different DID? no log in...
                            echo json_encode(array('error_code'=>1));
                            exit();
                        }
                    }
                    wp_clear_auth_cookie();
                    wp_set_current_user ( $user->ID );
                    wp_set_auth_cookie  ( $user->ID );
            
                    echo json_encode(array('error_code'=>0, 'url'=>user_admin_url()));
                    
                } else {
                    echo json_encode(array('error_code'=>1));
                }
            } else {
                echo json_encode(array('error_code'=>1));
            }
        } catch(Exception $e) {
            echo json_encode(array('error_code'=>1));
        }
        exit();
    }

    function initAction() {
        add_action('wp_ajax_username_login',[$this, 'username_login']);
        add_action('wp_ajax_nopriv_username_login',[$this, 'username_login']);
    }
}