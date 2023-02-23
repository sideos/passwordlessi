<?php
class SideosLoginForm {

    function ssi_login() {
    ?>
        <section class="sideosssi">
        <?php 
            if (get_option('token') === '' || get_option('templateID') === '' || get_option('sideosurl') === '') {
                ?> 
                <div id="ssierror" style="padding:20px;"><?php _e('Configuration Needed', 'sideoslogin') ?></div> 
                <div id="ssiqrcode" style="padding:20px;"></div> 
                <?php
            } else {
                ?> 
                <div id="ssiqrcode" style="padding:20px;"></div> 
                <?php
            }
        ?>	
        <?php if (get_option('showlogo') === '1') { ?> 
            <img src="<?php echo plugin_dir_url( __FILE__ ).'../styles/powered.png' ?>" />
        <?php } ?>
        </section>
    <?php
    }

    function initForm() {
        add_action('login_form',[$this, 'ssi_login']);
    }
}