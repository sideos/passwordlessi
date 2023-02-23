<?php

class SideosOptions {

    private $help;
    
    function __construct() {
        $this->help = [
        "disablesubmit" => __("Make sure you understand what this option does before disabling username/password login.", "sideoslogin"),
        "showlogo" => __("Make sure to check this box, to give credits!", "sideoslogin"),
        "token" => __("This is the Juno platform Token. Keep it safe.", "sideoslogin"),
        "did" => __("This is your Digital Identifier and you can find it in the Juno Platform", "sideoslogin"),
        "templateID" => __("This is the ID for the WordpressLogin template.", "sideoslogin"),
        "sideosurl" => __("This is the proxy to help you set up the passwordless login. You can create your own proxy, see <a href=\"https://docs.sideos.io\">documentation</a> for that.", "sideoslogin"),
    ];
}

    function ssi_options_page_html() {
        // check user capabilities
        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }
        ?>
        <div class="wrap">
            <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
            <section class="instruction">
                <div>
                    <?php _e('These are the settings to connect this instance with the Juno account', 'sideoslogin') ?>
                </div>
            </section>
            
            
            <form action="options.php" method="post">
                <?php
                // output security fields for the registered setting "wporg_options"
                settings_fields( 'ssi_options' );
                // output setting sections and their fields
                // (sections are registered for "wporg", each field is registered to a specific section)
                do_settings_sections( 'ssi' );
                // output save settings button
                submit_button( __( 'Save Settings', 'textdomain' ) );
                ?>
            </form>

            <div class="separator">&nbsp;</div>
            
            <section class="send">
                <h1><?php _e('Send Credential', 'sideoslogin') ?></h1>
                <div class="instruction"><?php _e('The selected user will receive at his/her email a credential offer to access this WordPress website. It lasts only 1 day so let them know.', 'sideoslogin')?></div>
                <label for="users"><?php _e('Select a user to send a credential', 'sideoslogin')?></label>
                <select id="users" name="users">
                <?php 
                    $users = get_users();
                    foreach($users as $value) {
                    ?>
                        <option value="<?php echo $value->user_email . '|' . $value->user_nicename; ?>"><?php echo $value->user_email ?></option>
                    <?php 
                    }
                ?>
                </select>
                <button id="send_credential" class="button">Send Credential</button>
            </section>
        </div>
        <?php
    }

    function ssi_options_page()
    {
        $icon = plugin_dir_url( __FILE__ ) . '../styles/icon.png';
        add_menu_page(
            __('SSI Options', 'sideoslogin'),
            __('SSI Options', 'sideoslogin'),
            'administrator',
            'ssi',
            [$this, 'ssi_options_page_html'],
            $icon
        );
    }


    function ssi_settings_fields(){

        $page_slug = 'ssi';
        $option_group = 'ssi_options';

        // 1. create section
        add_settings_section(
            'ssi_id', // section ID
            '', // title (optional)
            '', // callback function to display the section (optional)
            $page_slug
        );

        // 2. register fields
        register_setting( $option_group, 'token');
        register_setting( $option_group, 'did');
        register_setting( $option_group, 'templateID');
        register_setting( $option_group, 'sideosurl');
        register_setting( $option_group, 'disablesubmit');
        register_setting( $option_group, 'showlogo');

        // 3. add fields
        add_settings_field(
            'token',
            __('SSI Sideos Token', 'sideoslogin'),
            [$this, 'ssi_token'], // function to print the field
            $page_slug,
            'ssi_id', // section ID
            array(
                'label_for' => 'token',
                'class' => 'sideos_token', // for <tr> element
                'name' => 'token' // pass any custom parameters
            )
        );

        add_settings_field(
            'did',
            'SSI DID',
            [$this, 'ssi_field'], // function to print the field
            $page_slug,
            'ssi_id', // section ID
            array(
                'label_for' => 'did',
                'class' => 'sideos_did', // for <tr> element
                'name' => 'did' // pass any custom parameters
            )
        );

        add_settings_field(
            'templateID',
            'Template ID',
            [$this, 'ssi_field'], // function to print the field
            $page_slug,
            'ssi_id', // section ID
            array(
                'label_for' => 'templateID',
                'class' => 'sideos_template', // for <tr> element
                'name' => 'templateID' // pass any custom parameters
            )
        );

        add_settings_field(
            'sideosurl',
            'Sideos Proxy URL',
            [$this, 'ssi_sideosurl'], // function to print the field
            $page_slug,
            'ssi_id', // section ID
            array(
                'label_for' => 'sideosurl',
                'class' => 'sideos_template', // for <tr> element
                'name' => 'sideosurl' // pass any custom parameters
            )
        );

        add_settings_field(
            'disablesubmit',
            __('Disable Username/Password submit form', 'sideoslogin'),
            [$this, 'ssi_disablesubmit'], // function to print the field
            $page_slug,
            'ssi_id', // section ID
            array(
                'label_for' => 'disablesubmit',
                'class' => 'sideos_disablesubmit', // for <tr> element
                'name' => 'disablesubmit' // pass any custom parameters
            )
        );

        add_settings_field(
            'showlogo',
            __('Enable Powered by Sideos', 'sideoslogin'),
            [$this, 'ssi_disablesubmit'], // function to print the field
            $page_slug,
            'ssi_id', // section ID
            array(
                'label_for' => 'showlogo',
                'class' => 'sideos_showlogo', // for <tr> element
                'name' => 'showlogo' // pass any custom parameters
            )
        );

    }

    // custom callback function to print field HTML
    function ssi_token( $args ){
        printf(
            '<div class="sideos-token">
                <div class="help">%s</div>
                <input type="text" id="%s" name="%s" value="%s" class="blurred"/>
                <span id="showtoken">%s</span>
            </div>',
            $this->help[$args[ 'name' ]],
            $args[ 'name' ],
            $args[ 'name' ],
            get_option( $args[ 'name' ], '') ,
            __('hide/show', 'sideoslogin') 
        );
    }

    function ssi_field( $args ){
        printf(
            '<div class="help">%s</div><input type="text" id="%s" name="%s" value="%s" />',
            $this->help[$args[ 'name' ]],
            $args[ 'name' ],
            $args[ 'name' ],
            get_option( $args[ 'name' ], '' ) 
        );
    }

    function ssi_sideosurl( $args ){
        printf(
            '<div class="help">%s</div><input type="text" id="%s" name="%s" value="%s" />',
            $this->help[$args[ 'name' ]],
            $args[ 'name' ],
            $args[ 'name' ],
            get_option( $args[ 'name' ], 'https://wplogin.sideos.io' ) 
        );
    }

    function ssi_disablesubmit( $args ){
        $opt = get_option( $args[ 'name' ], false );
        printf(
            '<div class="help">%s</div><input type="checkbox" id="%s" name="%s" value="1" ' . checked( 1, $opt, false ) .' />',
            $this->help[$args[ 'name' ]],
            $args[ 'name' ],
            $args[ 'name' ]
        );
    }

    function initOptions () {
        add_action('admin_menu', [$this, 'ssi_options_page']);
        add_action('admin_init', [$this, 'ssi_settings_fields'] );
    }
}