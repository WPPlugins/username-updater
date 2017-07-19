<?php
/**
 * User update Page
 *
 * @package     Username_updater page
 * @since       1.0.3
 */
// Exit if accessed directly
if( ! defined( 'ABSPATH' ) ) {
	exit;
}
use \EasyUserNameUpdater\EasyUsernameUpdater;
function eup_user_update() { 
	    if(isset( $_REQUEST['update'] )) {
	         $eup = new EasyUsernameUpdater();
	         global $wpdb;
	         $id = trim( $_REQUEST['update'] ); 
	         $user_info = get_userdata($id);
	         $result=$wpdb->get_results($wpdb->prepare("SELECT * from $wpdb->users WHERE ID = %d", $id));
	         foreach ($result as $user) {
                   $username = $user->user_login;
              }
             if( !empty($_REQUEST['submit']) ) {  
                   $name = sanitize_user( $_POST["user_login"] );
	               if ( empty($name)) {
	                	$errorMsg=  "Error : You can not enter an empty username.";
                   }
                   elseif ( username_exists($name) ) {
                   		$errorMsg= "Error: This username(<i>$name</i>) is already exist.";
                   }
                   else {
	                    $eup->eup_update( $id,$name );
	                    echo '<div class="updated"><p><strong>Username Updated!</strong></p></div>'; 
	                    if(isset($_POST['user_notification'])) {
                             require_once (plugin_dir_path(__FILE__) . 'mail.php');
	                    }
	                }
             }
	       ?>
			<div class="wrap">
		        <h1><?php _e('Update Username','easy_username_updater') ?></h1>
		          <?php if (isset($errorMsg)) { echo "<div class='error'><p><strong>" .$errorMsg. "</strong></p></div>" ;} ?>
		     </div>
		    <form method="post" id="user_udate" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
		        <table class="form-table">
					<tr class="user-user-login-wrap">
						<th><label for="olduser_login"><?php _e('Old Username','easy_username_updater') ?></label></th>
						<td><strong><?php echo $username; ?></strong></td>
					</tr>
					<tr class="user-user-login-wrap">
						<th><label for="user_login"><?php _e('New Username','easy_username_updater') ?></label></th>
						<td><input type="text" name="user_login" class="regular-text" id="user_login" value="<?php if(!empty($_POST["user_login"])) echo $name; ?>"/></td>
					</tr>
					<tr>
						<th><?php _e('Send User Notification','easy_username_updater') ?></th>
						<td><label for="user_notification"><input type="checkbox" name="user_notification" id="user_notification" value="yes" <?php if(isset($_POST['user_notification'])) echo "checked='checked'"; ?>> <?php _e('Send the user an email about their updated username.','easy_username_updater') ?></label></td>
				    </tr>
				</table>
				<input type="submit" name="submit" id="submit" class="button button-primary" value="Update Username">
		    </form>
		     <p><a href="<?php echo admin_url('admin.php?page=easy_username_updater'); ?>"><-<?php _e('Go Back','easy_username_updater') ?></a></p>
<?php
}
else { ?>
<script>
  window.location='<?php echo admin_url('admin.php?page=easy_username_updater'); ?>'
</script>
<?php
}
}