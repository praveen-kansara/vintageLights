
$( document ).ready(function() {
  // $('#error_container').hide();
   $('#donot_change').hide();
});

// function frm_submit(){
//     //console.log('data: '+ $('#frm_application_form').serialize());
//     var form = $('#frm_application_form')[0];
//     console.log('data: '+ $('#frm_application_form'));
//     var data = new FormData(form);
    
//     var loader_html = '<span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span> Please wait ...';
//      jQuery('#formsubmitbtn').html(loader_html);
//      jQuery('#formsubmitbtn').attr("disabled", 'disabled');
     
// //data: $('#frm_application_form').serialize(),
//     $.ajax({
//             type: "POST",
//             enctype: 'multipart/form-data',
//             url: "index.php?q=Ajax/SaveApplication",
//             data: data,
//             processData: false,
//             contentType: false,
//             cache: false,
//             success: function(response) {
                
//                 var res = jQuery.parseJSON(response);
//                 console.log("result");
//                 console.log(res);
//                 console.log(res.error);
                
//                 if(res.error == true){
                    
//                     console.log("start handler");

//                     jQuery('#error_message').html('');
//                     var error_message_html = '<ul>';
//                     for (i = 0; i < res.data.length; i++) {

//                     //   console.log(i + ": "+ res.data[i][0]['field_name']);
//                     //   console.log(i + ": "+ res.data[i][0]['message']);
                       
//                       var error_field = res.data[i][0]['field_name'];
//                       var error_field_label = jQuery('label[for="' + error_field + '"]').text();
//                       if(error_field_label) {
//                           error_field_label =  error_field_label.slice(0, -1);
//                       } else {
//                           error_field_label = error_field;
//                       }
//                       var error_field_message = res.data[i][0]['message'];
                      
//                       error_message_html += '<li><b>' + error_field_label + '</b> '+ error_field_message + '</li>'

//                     }
//                     error_message_html += '</ul>';
//                     jQuery('#error_container').show();
//                     jQuery('#error_message').html(error_message_html);
                    
//                     // for (res.data of Object){
//                     //     console.log(Object);
//                     // }
//                     jQuery('#formsubmitbtn').removeAttr("disabled");
//                   jQuery('#formsubmitbtn').html('Submit');
                
//                     return true;
//                 } else {
//                   console.log('success message');
//                     $("#application_modal_body").html('<h2>Thank you for the submission!</h2>'); 
//                   jQuery('#formsubmitbtn').removeAttr("disabled");
//                   jQuery('#formsubmitbtn').html('Submit');
//                   jQuery('#formsubmitbtn').hide();
//                 }
                 
//               // console.log(res.error); return true;
                
//                 // if(res.error == false){
//                 //     console.log('success message');
//                 //     $("#application_modal_body").html('<h1>Thank you for the submission!</h1>');
//                 //  } else {
//                 //     console.log('Failure message');
//                 //     $("#application_modal_body").html('<p>Somthing went wrong! Try later</>');
//                 //  }
//             },
//             error: function() {
//                 alert('Error');
//             }
//         });
//   }