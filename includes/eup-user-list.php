<?php
/**
 * User List Page
 *
 * @package     Users List page
 * @since       1.0.3
 */
// Exit if accessed directly
if( ! defined( 'ABSPATH' ) ) {
	exit;
}
 use \EasyUsernameUpdaterPagination\EupPagination;
 use \EasyUserNameUpdater\EasyUsernameUpdater;
	function eup_edit_options() { ?>
		<div class="wrap userupdater">
		<p><h1><?php _e('Users List','easy_username_updater') ?></h1></p>
		  <form method="get" action="">
		    <p class="search-box">
			<input type="search" id="user-search-input" name="user" value="<?php if(!empty($_GET["user"])) echo $_GET["user"]; ?>">
			<input type="hidden" name="page" value="easy_username_updater" />
			<input type="submit" id="search-submit" class="button" value="Search Users">
			</p>
		  </form>
		<?php
		global $wpdb;
		$eup = new EasyUsernameUpdater();
		if( !empty( $_REQUEST['user'] )) {
		   $search_text = $_REQUEST['user'] . "%";
		   $records = $eup->eup_search( $search_text ); 
		   $pagination_count = count($records);
		   echo '<div class="subtitles">Search results for <strong>"'.$_REQUEST['user'].'"</strong></div>';
		   echo '<i>'.$pagination_count.' '."results found</i>";
	    }
		else {
		   $pagination_count = $wpdb->get_var("SELECT COUNT('ID') FROM  $wpdb->users"); 
	    }
		if($pagination_count > 0) {
		    //get current page
		    $current ='';
		    if(isset($_GET['p'])){ $current = $_GET['p']; }
		    $this_page = ( $current && $current > 0 ) ? (int) $current : 1;
		    //Records per page
		    $per_page = 20;
		    //Total Page
		    $total_page = ceil($pagination_count/$per_page);
		    //initiate the pagination variable
		    $pag = new EupPagination();
		    //Set the pagination variable values
		    $pag->Items($pagination_count);
		    $pag->limit($per_page);
		    $pag->target("admin.php?page=easy_username_updater");
		    $pag->currentPage($this_page);
		    //Done with the pagination
		    $list_start = ($this_page - 1)*$per_page;
		    if($list_start >= $pagination_count)  //Start of the list should be less than pagination count
		        $list_start = ($pagination_count - $per_page);
		    if($list_start < 0) //list start cannot be negative
		        $list_start = 0;
		    $list_end = ($this_page * $per_page) - 1;
		    //Get the data from the database
		    if( !empty( $_REQUEST['user'] )) {
		     $records = $eup->eup_search( $search_text ); 
		    }
		    else {
		     $records = $eup->eup_select( $list_start, $per_page );
		    }
		    if($records) {
		        ?>
		        <table class="wp-list-table widefat fixed striped users" cellpadding="3" cellspacing="3" width="100%">
			          <tr>
			            <th><strong><?php _e('User ID','easy_username_updater') ?></strong></th>
			            <th><strong><?php _e('User Name','easy_username_updater') ?></strong></th>
			            <th><strong><?php _e('Email','easy_username_updater') ?></strong></th>
			            <th><strong><?php _e('Role','easy_username_updater') ?></strong></th>
			            <th><strong><?php _e('Update','easy_username_updater') ?></strong></th>
			          </tr>
					    <?php
					    //loop through
					    foreach($records as $user) { 
					        $user_info = get_userdata( $user->ID );
					    ?>
			          <tr>
			            <td><?php echo $user->ID; ?></td>
			            <td><?php echo $user->user_login; ?></td>
			            <td><a href="mailto:<?php echo $user->user_email; ?>"><?php echo $user->user_email; ?></a></td>
			            <td><?php echo implode(', ', $user_info->roles); ?></td>
			            <td><a href="<?php echo admin_url( 'admin.php?page=eup_username_update&update='.$user->ID ); ?>">update</a></td>
			          </tr>
			      <?php } ?>
		        </table>
		        <?php
		        if( empty( $_REQUEST['user'] )) {
		        //Now display the pagiantion links
		        ?>
		            <div class="tablenav">
		                <div class="tablenav-pages">
		                    <span class="displaying-num">total: <?php echo $pagination_count; ?> <?php _e('Users','easy_username_updater') ?></span>
		                    <?php $pag->show(); ?>
		                </div>
		            </div>
		        <?php
		        }
		    }
		    else {
		        echo '<div class="error"><p>Something Went wrong! Check</p></div>';
		    }
		}
		else {
		    echo '<span class="update-nag bsf-update-nag"><p>No Results Found</p></span>';
		} 
		?>
		</div>
<?php } 