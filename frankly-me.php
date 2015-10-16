<?php

/**
* Plugin Name: Contact Us video Reply
* Description: Embed Frankly.me social widgets and grow your audience on frankly.me. Official Frankly.me wordpress plugin.
* Author: Frankly.me
* Version: 2.0
* Author URI: http://frankly.me
* Plugin URI: https://wordpress.org/plugins/franklyme/
* Text Domain: frankly-me
* License: GPLv2
**/


/*  Copyright 2015  ABHISHEK GUPTA  (email : abhishekgupta@frankly.me)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
    
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
add_action('admin_menu', 'my_contact_us_create_menu');

function my_contact_us_create_menu() {

    //create new top-level menu
    add_menu_page('Frankly Contact Settings', 'Contact Us', 'administrator', __FILE__, 'franly_contact_form' , plugins_url('/images/icon.png', __FILE__) ,15);

}






    add_action('wp_enqueue_scripts', 'load_frankly_script');
    add_action('validate_hook', 'load_frankly_script');
    add_action('bootsrtap_hook', 'add_bootstrap_contact');

    function load_frankly_script(){
        wp_enqueue_script('jquery');
       
        wp_enqueue_script('jquery-validate-min', 
                      plugins_url('/js/jquery.validate.min.js', __FILE__ ) ,
                      array( 'jquery' ) 
                     );
    }

    function add_bootstrap_contact()
    {
         wp_enqueue_style( 'bootstrap', plugins_url( '/css/bootstrap.min.css' , __FILE__ ) );
    }


function franly_contact_form(){

/***********************************************************************************
@
@ user Not login With frankly Me  
@
*************************************************************************************/

if(!get_user_meta (get_current_user_id(), 'frankly', true ))
    {
        require_once('frankly-login.php');
    }

/***********************************************************************************
@
@ user login With frankly Me  
@
*************************************************************************************/



else
    {
        require_once('frankly-contact-dash.php');
        require_once('shortcode.php');
    }


}
require_once('shortcode.php');
/***************************************************************
@ login Function  Ajax callback 
**************************************************************/

 add_action( 'wp_ajax_my_login', 'my_login_callback_contact' );


function my_login_callback_contact() {


    // HTTP REQUEST TO LOGIN API 

    $url='https://frankly.me/auth/local';
    $response = wp_remote_post( $url, array(
        'method' => 'POST',
        'timeout' => 45,
        'redirection' => 5,
        'httpversion' => '1.0',
        'blocking' => true,
        'headers' => array(),
        'body' => array( 'username' => $_POST['frankly_uname'], 'password' => $_POST['frankly_pass'] ),
        'cookies' => array()
        )
    );

    // DECODE RESPONDSE 
   //print_r($response);
   $data= json_decode($response['body'] ,true);
   
    if(isset($data['user']))
    {
         $user=$data['user']['username'];
         $token=$data['user']['token'];
         $id =$data['user']['id'];

        update_user_meta( get_current_user_id(), 'frankly', $user );
        update_user_meta( get_current_user_id(), 'frankly_id', $id );
        update_user_meta( get_current_user_id(), 'frankly_token',  $token );
        $json = array('flag' => 1);                      //  Login Successfull Collect token
     }else{
         $json = array('flag' => 0);                       //  Login failed
     }
     echo json_encode($json);
    wp_die();
}

/***************************************************************
@ logout Function  Ajax callback 
**************************************************************/

 add_action( 'wp_ajax_my_logout', 'my_logout_callback_contact' );


function my_logout_callback_contact() {
    delete_user_meta( get_current_user_id(), 'frankly');
    delete_user_meta( get_current_user_id(), 'frankly_id');
    delete_user_meta( get_current_user_id(), 'frankly_token');
    if(isset($_COOKIE['connect.sid']))
    {
        unset($_COOKIE['connect.sid']);
    }
    $json=array('flag' => 1 );

     echo json_encode($json);
    wp_die();
}


if(isset($_GET['action'],$_GET['plugin']))
{
    if($_GET['action']=='' && $_GET['plugin']=='franklyme_contact')
    {

        $check_title=get_page_by_title('Contact Us', 'OBJECT', 'page') ;
        wp_delete_post($check_title->ID);
    }
}

   
?>
