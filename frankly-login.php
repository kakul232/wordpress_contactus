 <h2>Enter your Frankly.me username to get started,</h2>
        <h4>Enter your settings below:</h4>
        
        <table class="form-table">
        <tr>
            <th scope="row">Frankly.me Login</th>

            <td>
                <input type="text" id="frankly_uname" placeholder="frankly username" name="frankly_uname" />
                <input type="text" id="frankly_pass" placeholder="frankly password" name="frankly_pass" />
                <span id="user-result"></span>
                <br><br><br>
                <b style="decorator:underlined">How to get frankly user name?</b><br>
                    <div style="margin-left:20px">
                    <ul style="list-style-type:circle">
                        
                        <li> Download the frankly.me app from
                            <a target="_blank" href="https://play.google.com/store/apps/details?id=me.frankly">
                            <img style="height:30px; vertical-align: middle;" src="<?=plugins_url( 'images/playstore.png' , __FILE__ );?>" class="MainGetAppLinks"></a> or 
                            <a target="_blank" href="https://itunes.apple.com/in/app/frankly.me-talk-to-celebrities/id929968427&amp;mt=8">
                            <img style="height:30px; vertical-align: middle;" src="<?= plugins_url( 'images/appstore.png' , __FILE__ );?>" class="MainGetAppLinks"></a>.
                        </li>

                        <li> Create an account</li>
                        <li> Open your profile to get your user name.</li>
                    </ul>
                    <div>
            </td>
        </tr>
        </table>
        <button style="float:right;margin-right:40px;" class="button button-primary" id ="proceed"  >Proceed</button>
<script type="text/javascript">


    
            (function($){
              
                var delay = (function(){
                  var timer = 0;
                  return function(callback, ms){
                    clearTimeout (timer);
                    timer = setTimeout(callback, ms);
                  };
                })();

                var keyup_callback = function(){

                    $('#frankly_uname').val($('#frankly_uname').val().replace(/\s/g, ''));
                    var username = $('#frankly_uname').val();
                    var url = 'http://api.frankly.me/user/profile/' + username;
                 // var url='https://frankly.me/auth/local';
                    
                    if(username.length < 4){
                        $('#user-result').html('');
                        return;
                    }
                    
                    if(username.length >= 4){
                    
                        $('#user-result').html('<img src="'+ '<?php echo plugins_url( 'images/ajax-loader.gif' , __FILE__ );?>' +'" />');
                      
                        $.ajax({
                            method: 'GET',
                            crossDomain: true,
                          //  data:'username='+username,
                            url: url,
                            
                            error: function()
                            {
                                $('#user-result').html('<img src="' + '<?php echo plugins_url( 'images/not-available.png' , __FILE__ );?>' + '" /> Username Not Available !');
                               // $('#proceed').attr('disabled','disabled');
                            },
                            success: function()
                            {
                                $('#user-result').html('<img src="' + '<?php echo plugins_url( 'images/available.png' , __FILE__ );?>' + '"  /> Username Available !');
                              //  $('#proceed').removeAttr('disabled');

                            }
                          

                        });
                        
                    }
                        
                };



                
                $('#frankly_uname').keyup( function(){
                    delay(  keyup_callback, 1000 );
                    });
                

/**********************************************************************************
@ Proceed Button Call
***********************************************************************************/

                $('#proceed').click(function(){

                    var uname = $('#frankly_uname').val();
                    var pass = $('#frankly_pass').val();

                    $.ajax({
                            method: 'POST',
                            url: ajaxurl,
                            data: {
                                    
                                    'action': 'my_login',
                                    'frankly_uname': uname,
                                    'frankly_pass': pass,

                                 },
                            success: function(response)
                            {
                                 var obj = JSON.parse(response);
                                 console.log(obj);
                                if(obj.flag=='1')
                                {
                                     window.location.assign('admin.php?page=video_contact_us%2Ffrankly-me.php');
                                }else{
                                     $('#user-result').html('<img src="' + '<?php echo plugins_url( 'images/not-available.png' , __FILE__ );?>' + '" /> Password Doesnt match !');
                                }
                              console.log(response);
                            }
                        });

                });

 })(jQuery);






</script>
