<?php
class SideosAdminUI {

    function extra_user_profile_fields( $user ) { ?>
        <h3><?php _e("SSI Information", "blank"); ?></h3>

        <table class="form-table">
        <tr>
            <th><label for="DID"><?php _e("DID"); ?></label></th>
            <td>
                <input type="text" name="did" id="did" value="<?php echo esc_attr( get_the_author_meta( 'did', $user->ID ) ); ?>" class="regular-text" /><br />
                <span class="description"><?php _e("Please enter your DID."); ?></span>
            </td>
        </tr>
        </table>
    <?php 
    }

    function userMetaDIDSave($userId) {
        if (!current_user_can('edit_user', $userId)) {
            return;
        }
        update_user_meta($userId, 'did', $_REQUEST['did']);
    }
    
    function initUI() {
        add_action('show_user_profile', [$this, 'extra_user_profile_fields'] );
        add_action('edit_user_profile', [$this, 'extra_user_profile_fields'] );
        add_action('personal_options_update', [$this, 'userMetaDIDSave']);
        add_action('edit_user_profile_update', [$this, 'userMetaDIDSave']);
    }
}