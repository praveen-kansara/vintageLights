jQuery(document).ready(function() {

  $('.datepicker').datepicker();

  $.validator.addMethod("customemail", 
    function(value, element) {
        return /^\w+([-+.']\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/.test(value);
    }, 
    "Sorry, I've enabled very strict email validation"
  );

  $("#reserve_space_form").validate({
    rules: {
      first_name : 'required',
      last_name : 'required',
      move_in_date : 'required',
      no_of_people : {
        required: true,
        digits: true
      },
      email : {
        required : true,
        customemail : true
      },
    },
    messages: {
      email : {
        required : "Please enter email",
        customemail : "Please enter a valid email address"
      }
    },
  });

  $("#reserver_space_submit").on("click", function() {

    var form = $("#reserve_space_form");
    if(form.valid() === true) {
      $("#reserve_space_sending").removeAttr('hidden');
        
      $.ajax({
        url: "./?q=Ajax/SaveReserveSpaceRequest",
        type: "POST",
        data: $("#reserve_space_form").serialize(),
        dataType: "text",

        success: function (data) {
          data_obj = $.parseJSON(data);
          $("#reserve_space_sending" ).attr("hidden",true);
          
          if(data_obj.status == "success") {
                  
              $("#reserve_space" ).removeAttr('hidden');
              $('#reserve_space_form')[0].reset();
              
              setTimeout(function() { 
                $("#reserve_space" ).attr("hidden",true);
              }, 2100);
    
              setTimeout(function() { 
                $("#reserve-space-form").modal('hide');
              }, 2100);
          } else {
          
            $("#reserve_space_error_message" ).removeAttr('hidden');
            $("#reserve_inquiry_error_message" ).html(data_obj.message);
            setTimeout(function(){ $("#reserve_space_error_message" ).attr("hidden",true); }, 2100);
         }
        },
        error: function(data) {
          $("#reserve-space-form").modal('hide');
        }
      });
    } else {

    }
  });
  
  const elem = document.getElementById('panzoom-element');
  const zoomInButton = document.getElementById('zoom-in');
  const zoomOutButton = document.getElementById('zoom-out');
  const panzoom = Panzoom(elem,{
    panOnlyWhenZoomed: true,
    minScale: 1,
    maxScale : 2.5,
    contain: 'outside'
  });
  const parent  = elem.parentElement;
  //parent.addEventListener('wheel', panzoom.zoomWithWheel);
  zoomInButton.addEventListener('click', panzoom.zoomIn);
  zoomOutButton.addEventListener('click', panzoom.zoomOut);
  
  
  
  const elem2 = document.getElementById('panzoom-element2');
  const zoom2InButton = document.getElementById('zoom2-in');
  const zoom2OutButton = document.getElementById('zoom2-out');
  const panzoom2 = Panzoom(elem2,{
    panOnlyWhenZoomed: true,
    minScale: 1,
    maxScale : 2.5,
    contain: 'outside'
  });
  //const parent  = elem.parentElement;
  //parent.addEventListener('wheel', panzoom.zoomWithWheel);
  zoom2InButton.addEventListener('click', panzoom2.zoomIn);
  zoom2OutButton.addEventListener('click', panzoom2.zoomOut);
  
  
  const elem3 = document.getElementById('panzoom-element3');
  const zoom3InButton = document.getElementById('zoom3-in');
  const zoom3OutButton = document.getElementById('zoom3-out');
  const panzoom3 = Panzoom(elem3,{
    panOnlyWhenZoomed: true,
    minScale: 1,
    maxScale : 2.5,
    contain: 'outside'
  });
  //const parent  = elem.parentElement;
  //parent.addEventListener('wheel', panzoom.zoomWithWheel);
  zoom3InButton.addEventListener('click', panzoom3.zoomIn);
  zoom3OutButton.addEventListener('click', panzoom3.zoomOut);
  
});


