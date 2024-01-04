/* Systemuser form  valiation rules  */
$("#SystemUserEdit").validate({
  rules: {
    name: "required",
    email: "required",
    phone:{
      number: true
    },
    password: {
      required: true,
      minlength: 5
    },
    cpassword: {
      required: true,
      minlength: 5,
      equalTo: "#password"
    },
    newpassword: {
      minlength: 5
    },
    newcpassword: {
      minlength: 5,
      equalTo: "#newpassword"
    },

  },
  messages:{
    name: "Please enter your name",
    email: "Please enter a valid email address",
    phone: "Please enter a valid phone no.",
    password: {
      required: "Please provide a password",
      minlength: "Your password must be at least 5 characters long"
    },
  }


});


$("#settings_page").validate({
  rules: {
    name   		: "required",
    email  		: "required",
    slug   		: "required",
    status 		: "required",
    sequence  : { min: 0 }
  },
  messages:{
    name    		:  "Please enter site name",
    email   		:  "Please enter a valid email address",
  }
});


$(".form-page").validate({
  rules: {
    title   		: "required",
    sequence  : { min: 0 }
  },
  messages:{
    title    		:  "Please enter title"
  }
});

$(".form-property").validate({
  rules: {
    title   		: "required",
    sequence  : { min: 0 }
  },
  messages:{
    title    		:  "Please enter title"
  }
});

$(".form-faq").validate({
  rules: {
    title       : "required",
    content     : "required",
    sequence  : { min: 0 }
  },
  messages:{
    title       :  "Please enter Question",
    content     :  "Please enter Answer"
  }
});

$("#redirection_manager").validate({
  rules: {
    uri        : { required:true, url: true},
    redir      : "required",
    redir_type : "required",
    
  },
  messages:{
    uri        :  "Please enter URL",
    redir      :  "Please enter redirect URL",
    redir_type :  "Please select redirect type"
  }
});



