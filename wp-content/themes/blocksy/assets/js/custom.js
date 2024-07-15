jQuery(document).ready(function () {
 function submitForm(event) {
        event.preventDefault(); // Prevent the default form submission behavior
        
        // Add any additional logic here, such as AJAX request to submit the form data
        
        // For demonstration purposes, let's just log a message
        console.log('Form submitted without redirecting and jumping to top.');
      }

      jQuery(".discount-code-wrapper #discount_code,.discount-code-wrapper button.button[type='submit']").wrapAll("<div class='wrap_globel_box'></div>");
      let parentUl = jQuery("li.previous").closest("ul.wpmc-tabs-list");

  // Find the first <li> element within the parent <ul>
  let firstLi = parentUl.find("li:first-child");

  // Add a class to the first <li> element
  firstLi.addClass("new-class");

  jQuery("label.form-check-label").wrapInner(
    "<div class='label_text_in'></div>"
    );
  jQuery(".wpf_form_ajax").hide();

  jQuery(".btndemo").click(function () {
    jQuery(".wpf_form_ajax ").toggle();
  });
  jQuery(".slider-section").owlCarousel({
    loop: true,
    margin: 0,
    nav: false,
    autoplay: true,
    dots: false,
    items: 1,
  });
  jQuery("section.related.products>ul.products").addClass("owl-carousel");
  jQuery("section.related.products>ul.products").owlCarousel({
    loop: true,
    margin: 15,
    nav: true,
    dots: false,
    responsive: {
      0: {
        items: 1,
        center: true,
      },
      480: {
        items: 2,
      },

      680: {
        items: 3,
      },
      991: {
        items: 4,
      },
    },
  });
});

jQuery(window).on("load resize", function () {
  var desNewHeight = 0; // for the description section
  var headerNewHeight = 0; // for the header section

  // Calculating the header new height
  jQuery(".image-banner").each(function () {
    if (headerNewHeight < jQuery(this).outerHeight()) {
      headerNewHeight = jQuery(this).outerHeight();
    }
  });

  // Calculating the description new height
  jQuery(".banner-ception").each(function () {
    if (desNewHeight < jQuery(this).outerHeight()) {
      desNewHeight = jQuery(this).outerHeight();
    }
  });

  // Set the header new height
  jQuery(".image-banner").css("height", headerNewHeight);

  // Set the description new height
  jQuery(".banner-ception").css("height", desNewHeight);
});

jQuery(document).ready(function () {
  setInterval(function () {
    var docHeight = jQuery(window).height();
    var footerHeight = jQuery("footer#colophon").height();
    var footerTop = jQuery("footer#colophon").position().top + footerHeight;
    var marginTop = docHeight - footerTop + 10;

    if (footerTop < docHeight)
      jQuery("footer#colophon").css(
        "margin-top",
        marginTop + "px"
      ); // padding of 30 on footer
    else jQuery("footer#colophon").css("margin-top", "0px");
    // console.log("docheight: " + docHeight + "\n" + "footerheight: " + footerHeight + "\n" + "footertop: " + footerTop + "\n" + "new docheight: " + $(window).height() + "\n" + "margintop: " + marginTop);
  }, 0);
});

// jQuery(document).ready(function ($) {
//   jQuery('input#ship-to-different-address-checkbox').addClass('ship-oncheck');

//   let tabhtml = $('.woocommerce-shipping-drapdrop').html();
//   let tabNew = $('.wpmc-shipping').html();
//   $('.wpmc-step-delivery').append('<div id="check_dummy_ops' + '">' + tabNew + tabhtml + '</div>');

// });

jQuery(document).ready(function ($) {
  jQuery("input#ship-to-different-address-checkbox").addClass("ship-oncheck");

  let tabhtml = $(".woocommerce-shipping-drapdrop").html();
  let tabNew = $(".wpmc-shipping").html();

  // Check if tabhtml and tabNew are defined before appending
  let appendedHtml = "";
  if (tabNew !== undefined) {
    appendedHtml += tabNew;
  }
  if (tabhtml !== undefined) {
    appendedHtml += tabhtml;
  }

  $(".wpmc-step-delivery").append(
    '<div id="check_dummy_ops">' + appendedHtml + "</div>"
    );
});

//How to Update WooCommerce Cart on Quantity Change
jQuery(function ($) {
  let timeout;
  $(".woocommerce").on("change", "input.qty", function () {
    if (timeout !== undefined) {
      clearTimeout(timeout);
    }
    timeout = setTimeout(function () {
      $("[name='update_cart']").trigger("click"); // trigger cart update
    }, 1000); // 1 second delay, half a second (500) seems comfortable too
  });
});

jQuery(document).ready(function ($) {
  // jQuery('#standardprice').hide();
  jQuery("#premiumprice").hide();
  jQuery(".premium-price").hide();
  jQuery('input[name="payment_method_demo"]').change(function () {
    var selectedValue = $(this).val();
    if (selectedValue === "Standard") {
      $("#standardprice").show();
      $("#premiumprice").hide();
    } else if (selectedValue === "Premium") {
      $("#standardprice").hide();
      $("#premiumprice").show();
    }

    if (selectedValue === "Standard") {
      $(".standard-price").show();
      $(".premium-price").hide();
    } else if (selectedValue === "Premium") {
      $(".standard-price").hide();
      $(".premium-price").show();
    }
  });
});

//Get the Checkout Fields in Preview page
jQuery(document).ready(function ($) {

  var couponCode = jQuery(".woocommerce-remove-coupon").data("coupon");
  if(couponCode)
  { 
    // alert("found");
    $('#wpmc-next').on("click", function () {
      var name = $('input[name="billing_name"]').val();
      $("#name").text(name);
    // console.log(name);
  });
    $('#wpmc-next').on("click", function () {
      var city = $('input[name="billing_city"]').val();
      $("#city").text(city);
    // console.log(name);
  });

    $('#wpmc-next').on("click", function () {
      var addressone = $('input[name="billing_address_1"]').val();
      $("#addressone").text(addressone);
    // console.log(name);
  });

    $('#wpmc-next').on("click", function () {
      var addresstwo = $('input[name="billing_address_2"]').val();
      $("#addresstwo").text(addresstwo);
    // console.log(name);
  });

    $('#wpmc-next').on("click", function () {
      var shipping_name = $('input[name="shipping_name"]').val();
      $("#shippingname").text(shipping_name);
    // console.log(name);
  });
    $('#wpmc-next').on("click", function () {
      var shipping_ville = $('input[name="shipping_ville"]').val();
      $("#shippingville").text(shipping_ville);
    // console.log(name);
  });
    $('#wpmc-next').on("click", function () {
      var shipping_rue = $('input[name="shipping_rue"]').val();
      $("#shippingrue").text(shipping_rue);
    // console.log(name);
  });
    $('#wpmc-next').on("click", function () {
      var shipping_supplement = $('input[name="shipping_supplement"]').val();
      $("#shippingsupplement").text(shipping_supplement);
    // console.log(name);
  });
  }
  else
  {
    // alert("not found");
    $('input[name="billing_name"]').on("input", function () {
      var name = $(this).val();
      $("#name").text(name);
    // console.log(name);
  });
    $('input[name="billing_city"]').on("input", function () {
      var city = $(this).val();
      $("#city").text(city);
    // console.log(name);
  });
    $('input[name="billing_address_1"]').on("input", function () {
      var addressone = $(this).val();
    // console.log(addressone);
    $("#addressone").text(addressone);
    // console.log(name);
  });
    $('input[name="billing_address_2"]').on("input", function () {
      var addresstwo = $(this).val();
      $("#addresstwo").text(addresstwo);
    // console.log(name);
  });
    $('input[name="shipping_name"]').on("input", function () {
      var shipping_name = $(this).val();
      $("#shippingname").text(shipping_name);
    // console.log(name);
  });
    $('input[name="shipping_ville"]').on("input", function () {
      var shipping_ville = $(this).val();
      $("#shippingville").text(shipping_ville);
    // console.log(name);
  });
    $('input[name="shipping_rue"]').on("input", function () {
      var shipping_rue = $(this).val();
      $("#shippingrue").text(shipping_rue);
    // console.log(name);
  });
    $('input[name="shipping_supplement"]').on("input", function () {
      var shipping_supplement = $(this).val();
      $("#shippingsupplement").text(shipping_supplement);
    // console.log(name);
  });

  }
});

jQuery(document).ready(function(){
  jQuery('.test-one').click(function(){
    jQuery('.standard-demo').removeClass('hidden');
    jQuery('.premium-demo').addClass('hidden');
  });
  jQuery('.test-two').click(function(){
    jQuery('.premium-demo').removeClass('hidden');
    jQuery('.standard-demo').addClass('hidden');
  });
});

jQuery(document).ready(function() {
  jQuery('input[type="radio"][name="payment_method_demo"][value="Standard"]').prop('checked', true);
});

//Get the Payment ICON in Checkout Preview page (Moyen de paiement)
jQuery(document).ready(function ($) {
    // Use .on() method for dynamically added elements
    $(document).on('click', '.payment_method_cod', function(){
      $("#codimg").attr("src", "https://devwp1.websiteserverhost.biz/ameliyahair/wp-content/themes/blocksy/assets/images/cod.png");
    });
    $(document).on('click', '.payment_method_ppcp', function(){
      $("#codimg").attr("src", "https://devwp1.websiteserverhost.biz/ameliyahair/wp-content/plugins/pymntpl-paypal-woocommerce/assets/img/paypal_logo.svg");
    });
    $(document).on('click', '.payment_method_woo_mpgs', function(){
      $("#codimg").attr("src", "https://devwp1.websiteserverhost.biz/ameliyahair/wp-content/plugins/woo-mpgs/assets/images/mastercard.png");
    });
    $(document).on('click', '.payment_method_klarna_payments_pay_later', function(){
      $("#codimg").attr("src", "https://x.klarnacdn.net/payment-method/assets/badges/generic/klarna.svg");
    });
    $('.payment_method_cod').trigger('click');
  });


jQuery(document).ready(function ($) {
      var isCouponAppended = false; // Initialize flag variable
      jQuery("#wpmc-next").click(function(){
              if (!isCouponAppended) { // Check if coupon code content has not been appended yet
                var couponCodeContent = jQuery(".my-coupon-code").html();
            // alert(couponCodeContent);
            jQuery(".xyz-coupan").append(couponCodeContent);
            isCouponAppended = true; // Set the flag to true after appending
            jQuery(".my-coupon-code").hide();
          }
        });
    });

jQuery(document.body).on('applied_coupon', function() { 
    // Assuming the 3rd tab can be selected with '.wc-tabs li:nth-child(3) a'
    // Adjust the selector based on your actual HTML structure
    var thirdTabSelector = '.wpmc-tab-number li:nth-child(3) a';
    jQuery(thirdTabSelector).trigger('click');
  }); 


//Register User Supprimer mes informations après livraison
jQuery(document).ready(function() {
  jQuery('#new_register_acc').click(function() {
        jQuery('#createaccount').click(); // Trigger click event on the checkbox
      });
});

//create place order buton in 4rth tab
jQuery(document).ready(function() {
  jQuery('#new_oder_btn').click(function() {
        jQuery('#place_order').click(); // Trigger click event on the checkbox
      });
});
//Email Changed functionality
jQuery(document).ready(function(){
  jQuery("#updateuser").hide();
  jQuery("#showForm").click(function(){
      // alert("hello");
      jQuery("#updateuser").toggle();
    });

});

jQuery(document).ready(function(){
  var couponCode = jQuery(".woocommerce-remove-coupon").data("coupon");
  console.log(jQuery(".wpmc-step-billing").hasClass("current"));
  if(couponCode){ 
    setTimeout(function(){///workaround
          jQuery('form[name="checkout"]').children("div.wpmc-step-billing").toggleClass('current'); // Matches exactly 'tcol1'
          if(!jQuery(".wpmc-step-billing").hasClass("current")){
            jQuery("#wpmc-prev").attr('style', 'display: block !important');
          }else{
            jQuery("#wpmc-prev").attr('style', 'display: none !important');
          }
        }, 10);
  //jQuery('.wpmc-step-billing').toggleClass('current');
  jQuery(".wpmc-billing").removeClass("current");
  jQuery('.wpmc-review').addClass('current');
  jQuery('.wpmc-step-review').addClass('current');
}
jQuery('body').on('click', '#new_coupan_code_data', function(event) {
var found = validateCouponCode(coupon_code); // This function should return true if the coupon code is valid
});
});

// Example function to validate the coupon code
function validateCouponCode(code) {
    // Implement your coupon code validation logic here
    // For demonstration, let's assume the code is valid if it's not empty
    return code !== '';
  }

//for checkout fillup tab 
jQuery(document).ready(function(){
  jQuery("#wpmc-next").click(function(){
    setTimeout(function(){
      jQuery("li.current").prevAll("li").addClass("previous");
    }, 1000);
  });
  jQuery("#wpmc-prev").click(function(){
    setTimeout(function(){
      jQuery("li.current").prevAll("li").addClass("previous");
      jQuery("li.current").nextAll("li").removeClass("previous");
    }, 1000);
  });
});


jQuery(document).ready(function(){
  setTimeout(function(){
    var txt = jQuery(".email_done").text();
    // console.log(txt);
    if (txt === "Email updated successfully!") {
      // alert("Email Changed successfully");
        window.location.href = 'https://devwp1.websiteserverhost.biz/ameliyahair/'; // Replace 'https://example.com' with your desired URL
    }
  }, 2000);
});