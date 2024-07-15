jQuery(document).ready(function () {
  
	 jQuery(".wpf_form_ajax").hide();

  jQuery(".btndemo").click(function(){
    jQuery(".wpf_form_ajax ").toggle();
  });


  jQuery('.slider-section').owlCarousel({
    loop:true,
    margin:0,
    nav:false,
    autoplay:false,
    dots:false,
    items:1,

});

});

jQuery(window).on('load resize', function(){
  
  var desNewHeight = 0; // for the description section
  var headerNewHeight = 0; // for the header section
  
  // Calculating the header new height
  jQuery('.image-banner').each(function(){       
    if(headerNewHeight < jQuery(this).outerHeight()){
      headerNewHeight = jQuery(this).outerHeight();
    }  
  });

  // Calculating the description new height
  jQuery('.banner-ception').each(function(){       
    if(desNewHeight < jQuery(this).outerHeight()){
      desNewHeight = jQuery(this).outerHeight();
    }  
  });

  // Set the header new height
  jQuery('.image-banner').css('height', headerNewHeight);

  // Set the description new height
  jQuery('.banner-ception').css('height', desNewHeight);
  
});
// jQuery(window).scroll(function() {
//   if (jQuery(this).scrollTop() > 120) {
//     jQuery('header#masthead').addClass('fixed');
//   } else {
//     jQuery('header#masthead').removeClass('fixed');
//   }
// });
