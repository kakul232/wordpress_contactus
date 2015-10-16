<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
/*********************************************************************

@ Display this content on contact us form if user log in 

**********************************************************************/
$frankly_userid=get_user_meta (get_current_user_id(), 'frankly', true );
$user_id = get_current_user_id();
global $wpdb;

$contact_page_content='[contact_us_video_reply id='.$user_id.']';

$check_title=get_page_by_title('Contact Us', 'OBJECT', 'page') ;


if (!$check_title or empty($check_title)){

    $post = array(
      'ID'             => '' ,  
      'post_content'   => $contact_page_content, 
      'post_name'      => 'contact-us', // The name (slug) for your post
      'post_title'     => 'Contact Us' ,// The title of your post.
      'post_status'    => 'publish', // Default 'draft'.
      'post_type'      => 'page' , // Default 'post'.
      'post_author'    => $user_id, // The user ID number of the author. Default is the current user ID.
      'post_parent'    => 0 , // Sets the parent of the new post, if any. Default 0.
      'menu_order'     => 7, // If new post is a page, sets the order in which it should appear in supported menus. Default 0.
      'comment_status' => 'closed' ,// Default is the option 'default_comment_status', or 'closed'.
    );  

    // Insert the post into the database

    $post_id=wp_insert_post( $post );
    echo '<h1>Contact Us Dashbord ! </h1> ';


}
else
{
    $post = array(
      'ID'             => $check_title->ID ,  
      'post_content'   => $contact_page_content, 
      'post_name'      => 'contact-us', // The name (slug) for your post
      'post_title'     => 'Contact Us' ,// The title of your post.
      'post_status'    => 'publish', // Default 'draft'.
      'post_type'      => 'page' , // Default 'post'.
      'post_author'    => $user_id, // The user ID number of the author. Default is the current user ID.
      'post_parent'    => 0 , // Sets the parent of the new post, if any. Default 0.
      'menu_order'     => 5, // If new post is a page, sets the order in which it should appear in supported menus. Default 0.
      'comment_status' => 'closed' ,// Default is the option 'default_comment_status', or 'closed'.
    );  


     // update the post into the database

    $post_id=wp_update_post( $post );
  


}

/*********************************************************************

@ Create Table on Load

**********************************************************************/
$sql="CREATE TABLE IF NOT EXISTS `wp_frankly_contact` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `franklyid` longtext NOT NULL,
  `name` longtext NOT NULL,
  `email` mediumtext NOT NULL,
  `phone` varchar(15) NOT NULL,
  `msg` text NOT NULL,
  `status` int(11) NOT NULL,
  `created` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=34 ;";

dbDelta( $sql );
/*********************************************************************

@ Display Dashboard

**********************************************************************/
do_action('bootsrtap_hook');

/*if ($check_title or !empty($check_title)){
    echo '<iframe src="https://frankly.me/'.$frankly_userid.'" height="850" width="600" ></iframe>';
    }*/
  echo '<h1>Contact Us Dashbord ! </h1> <b>Welcome to FranklyMe  @ '.get_user_meta (get_current_user_id(), 'frankly', true ).'</br>
  <a class="label label-primary" href="https://frankly.me/'.get_user_meta (get_current_user_id(), 'frankly', true ).'">View Profile</a> <button id="logout" class="label label-danger">logout</button></b>';
?>


<h4>Try Our another plugin </h4>

<b>
<ul class="pagignation">
   <li> <a href="https://wordpress.org/plugins/video-polls/">Video Poll</a> </li>
   <li> <a href="https://wordpress.org/plugins/franklyme/">franky.me</a></li>
   <li> <a>frankly Team</a></li>

   <li> Download the frankly.me app from
                            <a target="_blank" href="https://play.google.com/store/apps/details?id=me.frankly">
                            <img style="height:30px; vertical-align: middle;" src="<?=plugins_url( 'images/playstore.png' , __FILE__ );?>" class="MainGetAppLinks"></a> or 
                            <a target="_blank" href="https://itunes.apple.com/in/app/frankly.me-talk-to-celebrities/id929968427&amp;mt=8">
                            <img style="height:30px; vertical-align: middle;" src="<?= plugins_url( 'images/appstore.png' , __FILE__ );?>" class="MainGetAppLinks"></a>.
                        </li> 
</ul>
</b>


<div class="mr-tp-20 allpollwrap">
  
  <h2> Yur Contact Us  </h2>

 <table class="wp-list-table widefat fixed striped posts" border="1">
   <thead>
                  <tr>
                    <th><strong>Name</strong></th>
                    <th><strong>Email</strong></td>
                    <th><strong>Phone</strong></th>
                    <th><strong>Massage</strong></th>
                  </tr>
                </thead>
    
    <tbody>
     <?php 
     /*****************************************************
     @ Display Poll On Table 
     *****************************************************/
    $uid = get_user_meta (get_current_user_id(), 'frankly', true );

    $sql="SELECT * FROM wp_frankly_contact WHERE franklyid='$uid'ORDER BY id DESC";
    $result=$wpdb->get_results($sql, OBJECT);
    foreach ($result as $contact) {

                $username=$contact->franklyid;




                ?>
               <tr>
                  <td><?php echo $contact->name;?></td>
                  <td><?php echo $contact->email;?></td>
                  <td><?php echo $contact->phone;?></td>
                  <td><a href="http://frankly.me/<?php echo $username; ?>">View</a> </td>
                </tr>
               
      <?php } ?>
    </tbody>
    
  </table>
  
  </div>

 

<script type="text/javascript">

/**********************************************************************************
@ Logout Button Call
***********************************************************************************/

                $('#logout').click(function(){


                    $.ajax({
                            method: 'POST',
                            url: ajaxurl,
                            data: {
                                    
                                    'action': 'my_logout',
                                    

                                 },
                            success: function(response)
                            {
                                 var obj = JSON.parse(response);
                                 console.log(obj);
                                if(obj.flag=='1')
                                {
                                     window.location.assign('admin.php?page=video_contact_us%2Ffrankly-me.php');
                                }else{
                                     $('#user-result').html('<img src="' + '<?php echo plugins_url( 'images/not-available.png' , __FILE__ );?>' + '" /> Unable to proceed request !');
                                }
                              console.log(response);
                            }
                        });

                });



</script>

