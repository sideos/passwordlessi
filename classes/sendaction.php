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
                'name' => $_POST['name'],
                'email' => $_POST['email'],
                'website' => get_site_url(),
                'templateID' => $TEMPLATEID
            );
            
            $ch = curl_init("$SIDEOS_URL/send");
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'X-Token: ' . $TOKEN,
                'Content-Type:application/json'
            ]);	
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postParameter));	
            $result = curl_exec($ch);
            $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            if ($statusCode == 200) {
                echo json_encode(array('error_code'=>0));
            } else {
                echo json_encode(array('error_code'=>1));
            }
            curl_close($ch);
            
        } catch(Exception $e) {
            echo json_encode(array('error_code'=>1));
        }
        exit();
    }
    
    function initAction() {
        add_action('wp_ajax_send_credential',[$this, 'send_credential']);
    }
}