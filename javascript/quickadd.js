$("#k_mobile").keypress(function (e) {
                 //if the letter is not digit then display error and don't type anything
                 if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {        
                           return false;
                   }
               });

$('#k_name').keydown(function (e) {
      if (e.shiftKey || e.ctrlKey || e.altKey) {
					  e.preventDefault();
					 } else {
    					var key = e.keyCode;
    					if (!((key == 8) || (key == 32) || (key == 46) || (key >= 35 && key <= 40) || (key >= 65 && key <= 90))) {
    					e.preventDefault();
					  } }
					});

$("#add_karyakarta input").focus(function(){
	$(this).removeClass("form_error");
});

$("#add_karyakarta input").focus(function(){
	$('#email-error').text('');
});

$('#k_email').blur(function(){ 
  if($.trim($('#k_email').val()) != ''){   
     $.ajax({
                type: "POST",
                url: "/sampark/shakha/validate_email",
                data:{email:$('#k_email').val()},
                dataType : "json",                                     
                success:function(result) {
                  $('#email-error').addClass('span_error_font'); 	
                  $('#email-error').text(result.error);
                  
                }               
         });
         } 
     }); 


$('#btn_addcontact').click(function(){
	var regexName = /^[a-zA-Z ]*$/; 
	var regexEmail = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
	var regexPhone = /^[0-9]*$/
	var errmsg = false;

	if($.trim($('#email-error').text()) != ''){
         errmsg = true;
    }

	if($.trim($('#k_name').val()) === ''){
		errmsg = true;
		$('#k_name').addClass('form_error');
	}else{
    	  if (!regexName.test($("#k_name").val())) {
               errmsg = true;
               $('#k_name').addClass('form_error');
          }
	}

   if($.trim($('#k_email').val()) != ''){
		if(!regexEmail.test($("#k_email").val())){
			  errmsg = true;
	          $('#k_email').addClass('form_error');
		}
	}

	if($.trim($('#k_mobile').val()) != ''){
		if(!regexPhone.test($("#k_mobile").val())){
			  errmsg = true;
	          $('#k_mobile').addClass('form_error');
		}else if($('#k_mobile').val().length != 10){
			  errmsg = true;
	          $('#k_mobile').addClass('form_error');
		}
	}

	if(!errmsg){
		$('#add_karyakarta').submit();
	}
	

})
