<?php
/*
Plugin Name:  PasswordleSSI
Plugin URI:   https://github.com/sideos/wp-ssi-login
Description:  SSI Passwordless Login powered by Sideos
Version:      1.0.0
Author:       Sideos GmbH
Author URI:   https://www.sideos.io
License:      GPLv2 or later
License URI:  https://www.gnu.org/licenses/gpl-2.0.html
Text Domain:  sideoslogin
Domain Path: /languages
*/

require_once('classes/adminui.php');
require_once('classes/options.php');
require_once('classes/loginaction.php');
require_once('classes/sendaction.php');
require_once('classes/loginform.php');
require_once('classes/restapi.php');

require_once('enqueue.php');

/* ** BEGIN --- DISABLE POST SUBMIT TO AVOID BRUTE FORCE ATTACK **************/
/*
	If you selected the option to disable the username/password form, you can
	re-enable it by calling the rest API endpoint using the SSI token 
	in the X-Token header parameter.
*/
$val = get_option('disablesubmit', false);
if ($val === "1") {
	add_action( 'login_init', function () {
		if ( isset( $_POST['log'] ) || isset( $_POST['user_login'] ) ) {
			die;
		}
	 } );
}
/* *** END ---DISABLE POST SUBMIT TO AVOID BRUCE FORCE ATTACK **************/


(new SideosAdminUI())->initUI();
(new SideosOptions())->initOptions();
(new SideosLoginAction())->initAction();
(new SideosSendAction())->initAction();
(new SideosLoginForm())->initForm();
(new SideosRestAPI())->initAPI();
