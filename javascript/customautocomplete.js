function log( id,type ) {
          if(type == "shakha"){            
            window.location='/sampark/shakha/view/'+id;
          }else{            
            window.location='/sampark/profile/view/'+id;
          }        
        }
      jQuery.noConflict();
      (function ($) {
        function readyFn() {
        jQuery( "#term" ).autocomplete({          
           source: function(request, response) {
                $.ajax({
                url: "/sampark/search/auto_suggest/"+request.term,                
                dataType: "json",
                type: "GET",
                success: function(data){                   
                    response( $.map( data, function( item ) 
                        {
                            return{                                    
                                    label: item.title,
                                    value: item.title,
                                    id:item.id,
                                    type:item.type
                                                                                                      
                                   }
                        }));                   
                }
            });
        },
          minLength: 3,
          select: function( event, ui ) {
            log(ui.item.id, ui.item.type);                      
          }

        });
    $("#term").focus(function(){
      $(this).removeClass("form_error");
      $('#autosuggest-error').text('');
    });

    $('#form1').submit(function(){
      if($.trim($('#term').val()).length < 4){
        $('#term').addClass('form_error');
        $('#autosuggest-error').text('Atleast 4 characters required');
         return false;
      }  
    });
  }
$(document).ready(readyFn); 
})(jQuery);