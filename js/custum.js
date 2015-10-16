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
              // var fromdata=$('#myform').serialize();
               var created=Date();
             $('#submit').attr('disabled','disabled');
                 /*****************************************************************Submit Handeler*/
                    $.ajax({
                        url: validate.ajaxurl,
                        type: 'post',
                        data:{
                            action: validate.action,
                            name:name,
                            email:email,
                            phone:phone,
                            created:created,
                        },
                        success: function(response)
                            {
                                alert(response);
             
                            }            
                        });
                } 
 });

})(jQuery);

