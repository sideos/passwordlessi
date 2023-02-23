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
            $challenge = $_POST['challenge'];
            $ch = curl_init("$SIDEOS_URL/user/$challenge");
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'X-Token:' . $TOKEN,
                'Content-Type:application/json'
            ]);	
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $result = curl_exec($ch);
            $obj = json_decode($result, true);
            $obj = json_decode($obj['response'], true);
            $vc = $obj['verifiableCredential'][0];
            $cs = $vc['credentialSubject'];
            curl_close($ch);

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