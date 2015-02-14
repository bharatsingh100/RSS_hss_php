$( "#addContactForm" ).submit(function( event ) {
  if($('#email-error').text() != ''){
      return false;
    }else{
      return true;
    }
});

  $('#email').blur(function(){ 
  if($('#email').val() != ''){   
     $.ajax({
                type: "POST",
                url: "/sampark/shakha/validate_email",
                data:{email:$('#email').val()},
                dataType : "json",                                     
                success:function(result) {
                  $('#email-error').text(result.error);
                }               
         });
         } 
     }); 