jQuery(document).ready(function() {

   jQuery('#btn_nav').on('click', function(){
    jQuery(this).toggleClass('open-menu');
   });


  jQuery('.multiple-items').slick({
    infinite: true,
    dots: true,
    slidesToShow: 2,
        slidesToScroll: 1,
        focusOnSelect: true,
        autoplay: true,
        autoplaySpeed: 3000,
        speed: 1500,
        responsive: [{
          breakpoint: 1025,
          settings: {
            slidesToShow: 2
          }
        },
          {
            breakpoint: 576,
            settings: {
              slidesToShow: 1
            }
          }
        ]
  });

  jQuery('.amenities-slide').slick({
    infinite: true,
    dots: true,
    slidesToShow: 3,
        slidesToScroll: 1,
        focusOnSelect: true,
        autoplay: true,
        autoplaySpeed: 3000,
        speed: 1500,
        responsive: [{
          breakpoint: 1025,
          settings: {
            slidesToShow: 2
          }
        },
          {
            breakpoint: 576,
            settings: {
              slidesToShow: 1
            }
          }
        ]
  });

  jQuery('.properties-slide').slick({
    infinite: true,
    dots: false,
    slidesToShow: 1,
        slidesToScroll: 1,
        focusOnSelect: true,
        autoplay: false,
        speed: 1500,
        responsive: [{
          breakpoint: 1025,
          settings: {
            slidesToShow: 1
          }
        },
          {
            breakpoint: 576,
            settings: {
              slidesToShow: 1
            }
          }
        ]
  });

  jQuery("#section-icon").click(function() {
    jQuery('html,body').animate({
        scrollTop: jQuery("#info-section").offset().top
      },1500);
  });

  jQuery("#home-section-icon").click(function() {
    jQuery('html,body').animate({
        scrollTop: jQuery(".welcome-section").offset().top-80
      },1500);
  });

  jQuery(window).scroll(function() {
    if (jQuery(window).scrollTop() > 300) {
      jQuery('.back-top').addClass('show');
    } else {
      jQuery('.back-top').removeClass('show');
    }
  });

  jQuery('.back-top').on('click', function(e) {
    e.preventDefault();
    jQuery('html, body').animate({scrollTop:0}, 2500);
  });
  
  var hash = $(location).prop('hash');
  if(hash !== '') {
    jQuery('html,body').animate({
     scrollTop: jQuery(hash).offset().top},
     2500);
  }
});

