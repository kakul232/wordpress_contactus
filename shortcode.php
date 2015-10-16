
<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
add_shortcode('contact_us_video_reply','contact_us');


function contact_us($atts){

      $a = shortcode_atts(
            array(
                'id' => '1',
                'type'=>'lg',

            ), $atts);

    $frankly_userid=get_user_meta ($a['id'], 'frankly', true );
    do_action('bootsrtap_hook');



?>

<style type="text/css">.error{color: red}</style>



<form role="form" id="myform">
        <div id="alert"></div>
        <div class="form-group">
            <label>Name:</label>
            <input class="form-cotrol" type="text" id="name" name="name" placeholder="Full Name" style="width:100%">
             <input class="form-cotrol" type="hidden" id="franklyId" name="franklyId" value="<?php _e($frankly_userid);?>">
        </div>

        <div class="form-group">
            <label>Email:</label>
            <input class="form-cotrol" type="text" id="email" name="email" placeholder="eg. abc@exemple.com" style="width:100%">
        </div>

         <div class="form-group">
            <label>Phone:</label>
            <input class="form-cotrol" type="text" id="phone" name="phone" placeholder="Your Phone No" style="width:100%">
        </div>

        <div class="form-group">
            <label></label>
            <input type="submit" value="ask">
        </div>

</form>

<h1>Answer Card</h1>
<ul class="pagination">



       <?php
             $url='https://api.frankly.me/user/profile/'.$frankly_userid.'';
              $response = wp_remote_post( $url, array(
                  'method' => 'GET',
                  'timeout' => 45,
                  'redirection' => 5,
                  'httpversion' => '1.0',
                  'blocking' => true,
                  'headers' => array(),
                  'body' => array(),
                  'cookies' => array()
                  )
              );

             // collect data from response

              $rs=json_decode($response['body'],true);
              $qus_url='https://api.frankly.me/timeline/user/'.$rs['user']['id'].'/multitype';
              $qus_response = wp_remote_post( $qus_url, array(
                  'method' => 'GET',
                  'timeout' => 45,
                  'redirection' => 5,
                  'httpversion' => '1.0',
                  'blocking' => true,
                  'headers' => array(),
                  'body' => array(),
                  'cookies' => array()
                  )
              );

             // collect data from response
             // echo $qus_response['body'];
              $qus_rs=json_decode($qus_response['body'],true);
              for($i=0;$i<count($qus_rs['stream']) ; $i++){


            //  print_r($qus_rs["stream"][$i]["post"]["slug"]);

                if(isset($qus_rs["stream"][$i]["post"]["slug"]) && $qus_rs["stream"][$i]["post"]["question_author"]['username'] != $qus_rs["stream"][$i]["post"]["answer_author"]['username'])
                {
                  $slug=$qus_rs["stream"][$i]["post"]["slug"];


              echo   '<li><iframe width="300" height="520" frameborder="0" src="https://frankly.me/widgets/'.$frankly_userid.'/'.$slug.'?flagRedirect=false&url=http:/'.$_SERVER["PHP_SELF"].'"></iframe><li>';

        } } ?>


</ul>
<a href="https://frankly.me/<?php echo $frankly_userid; ?> ">View All</a>








<?php do_action('validate_hook'); ?>

<script type="text/javascript">
(function($){
        $("#myform").validate({
        rules: {
            name: "required",
            email: {
              required: true,
              email: true
            },
            phone: {
                required: true,
                number:true
            }

        },
        messages: {
            name: "Please specify your name",
            email: {
                required: "We need your email address to contact you",
                email: " must be in the format of name@domain.com"
                },
            phone: {
                required: "We need your Phone number to contact you"
            }
        },

            submitHandler: function(form)
            {
               var name= $('#name').val();
               var email= $('#email').val();
               var phone= $('#phone').val();
               var franklyId= $('#franklyId').val();
              // var fromdata=$('#myform').serialize();
               var created=Date();
             $('#submit').attr('disabled','disabled');
                 /*****************************************************************Submit Handeler*/
                    $.ajax({
                        url: "<?php echo admin_url( 'admin-ajax.php' ); ?>",
                        type: 'post',
                        data:{
                            action: 'contact_us_submit',
                            franklyid:franklyId,
                            name:name,
                            email:email,
                            phone:phone,
                            created:created,
                        },
                        success: function(response)
                            {

                                var url="https://frankly.me/widgets/ask/"+franklyId+"/question";
                                popupwindow(url,"Ask Me Anything", 400, 500);
                            }
                        });
                }
 });

})(jQuery);


function popupwindow(url, title, w, h) {
  var left = (screen.width/2)-(w/2);
  var top = (screen.height/2)-(h/2);
  return window.open(url, title, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
}


</script>

<?php }

/**********************************************************************************
* @                 Ajax Contact us form ******************************************
***********************************************************************************/

add_action( 'wp_ajax_nopriv_contact_us_submit', 'contact_us_submit_callbck' );
add_action( 'wp_ajax_contact_us_submit', 'contact_us_submit_callbck' );

function contact_us_submit_callbck(){
    global $wpdb;
   $Insert=$wpdb->insert(
                'wp_frankly_contact',
                array('name' => $_POST['name'],
                    'franklyid'=>$_POST['franklyid'],
                    'email' => $_POST['email'],
                    'phone'=>$_POST['phone'],
                    'created'=>$_POST['created'],

                     )
                );
    $last_id=$wpdb->insert_id;




    if($last_id)
    {
        $json[] = array('flag' =>'1');
    }else{
        $json[] = array('flag' =>'0');
    }

    echo json_encode($json);
    wp_die();
}


?>
