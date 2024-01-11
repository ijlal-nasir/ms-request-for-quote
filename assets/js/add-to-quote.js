jQuery(document).ready(function () {
  function render_quote_list(quote_list) {
    let qtyItemProducts = 0;
    if (quote_list.length > 0) {
      jQuery(".quote-item-count").removeClass("elementdisplaynone");
      let addtoquote_list =
        '<div class="row" id="msatq_table_header_wrapper"><div class="col-1 ps-0"></div><div class="col-2 col-lg-3 px-lg-0 text-center"> <h4 class="msatq_quotes_header_title">Image</h4> </div> <div class="col-7 col-md-8 col-lg-6"> <h4 class="msatq_quotes_header_title"> Product </h4> </div> <div class="col-2 col-md-1 col-lg-2 p-1 p-lg-0"> <h4 class="msatq_quotes_header_title text-md-center">Qty</h4> </div> </div>';
      for (var list = 0; list < quote_list.length; list++) {
        qtyItemProducts = qtyItemProducts + parseInt(quote_list[list][3]);
        if (list > 0) {
          addtoquote_list += '<hr id="hr_' + quote_list[list][0] + '" />';
        }
        addtoquote_list +=
          '<div class="row px-md-3 msatq_table_item_wrapper"> <div class="col-1 ps-0"><svg class="msatq_delete_quote" id="' +
          quote_list[list][0] +
          '" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x" viewBox="0 0 16 16"><path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/></svg> </div> <div class="col-2 col-lg-3 px-lg-0"> <img class=" rounded" src="' +
          quote_list[list][2] +
          '" /> </div> <div class="col-7 col-md-8 col-lg-6"> <h4 class="msatq_quotes_item_title"> <a href="' +
          quote_list[list][4] +
          '" class="mstq_product_title" > ' +
          quote_list[list][1] +
          ' </a> </h4> </div> <div class="col-2 col-md-1 col-lg-2 p-1 p-lg-0"> <input type="number" class="mstq_qunatity" min="1" id="' +
          quote_list[list][0] +
          '" value="' +
          quote_list[list][3] +
          '" /> </div> </div>';
      }
      jQuery("#quotes_added_product_list").html(addtoquote_list);
      jQuery(".quote-item-count").html(qtyItemProducts);
    }
  }
  var current_product_page_id = jQuery("#msatq_addtoquote_button")
    .find("#msatq_addtoquote")
    .attr("data-product-id");
  var added_products = localStorage.getItem("added_addtoquote_products");
  if (typeof current_product_page_id != "undefined") {
    if (added_products != null) {
      added_products = JSON.parse(added_products);
      for (var i = 0; i < added_products.length; i++) {
        if (added_products[i].includes(current_product_page_id)) {
          jQuery("#msatq_addtoquote_button").html(
            '<p>This product is already in your quotes list. <a href="" data-bs-toggle="modal" data-bs-target="#msatq_addtoquote_modal" class="clickhere_after_quote">Click Here</a> to see your quotes</p>'
          );
          break;
        }
      }
    }
  }
  var added_products = localStorage.getItem("added_addtoquote_products");
  if (added_products != null) {
    jQuery("#modal-quote-list-row").removeClass("elementdisplaynone");
    jQuery(".no-quote-in-list").addClass("elementdisplaynone");
    jQuery("#msatq_addtoquote_modalLabel").removeClass("elementdisplaynone");
    added_products = JSON.parse(added_products);
    render_quote_list(added_products);
  } else {
    jQuery("#msatq_addtoquote_modalLabel").addClass("elementdisplaynone");
  }

  jQuery("body").on("click", "#msatq_addtoquote", function () {
    var added_products = localStorage.getItem("added_addtoquote_products");
    jQuery("#modal-quote-list-row").removeClass("elementdisplaynone");
    jQuery(".no-quote-in-list").addClass("elementdisplaynone");
    jQuery("#msatq_addtoquote_modalLabel").removeClass("elementdisplaynone");
    jQuery("#msatq_message_after_submission").addClass("elementdisplaynone");
    jQuery("#msatq_submit_your_request").css("cursor", "default");
    jQuery("#msatq_submit_your_request").css("width", "100%");
    jQuery("#msatq_submit_your_request").prop("disabled", false);
    let current_product_id = jQuery(this).attr("data-product-id");
    let product_title = jQuery(".postid-" + current_product_id + "")
      .find(".product_title")
      .text();
    let product_quantity = jQuery(".postid-" + current_product_id + "")
      .find(".qty")
      .val();
    var imgsrc = "";
    let product_link = location.href;
    jQuery(".wcgs-slider-image").each(function () {
      if (jQuery(this).attr("data-slick-index") == 0) {
        imgsrc = jQuery(this).attr("href");
      }
    });
    let added_addtoquote_products_array = [
      current_product_id,
      product_title,
      imgsrc,
      product_quantity,
      product_link,
    ];
    if (added_products == null) {
      added_products = [];
      added_products[0] = added_addtoquote_products_array;
      localStorage.setItem(
        "added_addtoquote_products",
        JSON.stringify(added_products)
      );
      jQuery("#msatq_addtoquote_button").html(
        '<p>Your Product is successfully added into the quote. <a href="" data-bs-toggle="modal" data-bs-target="#msatq_addtoquote_modal" class="clickhere_after_quote">Click Here</a> to see your quotes</p>'
      );
    } else {
      added_products = JSON.parse(added_products);
      added_products.push(added_addtoquote_products_array);
      localStorage.setItem(
        "added_addtoquote_products",
        JSON.stringify(added_products)
      );
      jQuery("#msatq_addtoquote_button").html(
        '<p>Your Product is successfully added into the quote. <a href="" data-bs-toggle="modal" data-bs-target="#msatq_addtoquote_modal" class="clickhere_after_quote">Click Here</a> to see your quotes</p>'
      );
    }
    if (added_products.length > 0) {
      render_quote_list(added_products);
    }
  });

  jQuery("body").on("click", "#msatq_submit_your_request", function (e) {
    let ajax_url = window["origin"] + window["woocommerce_params"]["ajax_url"];
    let quotes_added_array = localStorage.getItem("added_addtoquote_products");
    quotes_added_array = JSON.parse(quotes_added_array);
    let first_name = jQuery("#msatq_firstname").val();
    let last_name = jQuery("#msatq_lastname").val();
    let email = jQuery("#msatq_email").val();
    let phone = jQuery("#msatq_phonenumber").val();
    let message = jQuery("#msatq_Message").val();
    if (first_name == "") {
      alert("Please enter first name");
    } else {
      if (last_name == "") {
        alert("Please enter last name");
      } else {
        if (email == "") {
          alert("Please enter email");
        } else {
          if (phone == "") {
            alert("Please enter phone number");
          }
        }
      }
    }
    if (first_name != "" && last_name != "" && email != "" && phone != "") {
      let screen_width = screen.width;
      if (screen_width > 800) {
        jQuery(this).css("width", "84%");
      } else {
        jQuery(this).css("width", "80%");
      }
      jQuery(this).css("cursor", "not-allowed");
      jQuery(this).prop("disabled", true);
      jQuery(".spinnerloader").removeClass("elementdisplaynone");
      jQuery.ajax({
        type: "POST",
        url: ajax_url,
        data: {
          action: "msatq_request_quote_email",
          quotes_list: quotes_added_array,
          first_name: first_name,
          last_name: last_name,
          email: email,
          phone: phone,
          message: message,
        },
        success: function (data) {
          console.log(data);
          jQuery("#msatq_submit_your_request").css("cursor", "default");
          jQuery("#msatq_submit_your_request").prop("disabled", false);
          jQuery(".spinnerloader").addClass("elementdisplaynone");
          jQuery("#msatq_message_after_submission").removeClass(
            "elementdisplaynone"
          );
          jQuery("#modal-quote-list-row").addClass("elementdisplaynone");
          jQuery(".no-quote-in-list").addClass("elementdisplaynone");
          jQuery("#msatq_addtoquote_modalLabel").addClass("elementdisplaynone");
          localStorage.removeItem("added_addtoquote_products");
          var current_product_id = jQuery(".single_add_to_cart_button").val();
          console.log(current_product_id);
          jQuery("#msatq_addtoquote_button").html(
            '<button id="msatq_addtoquote" data-product="add_to_quote_' +
              current_product_id +
              '" data-product-id = "' +
              current_product_id +
              '"type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#msatq_addtoquote_modal">Request A Quote</button>'
          );
          jQuery(".quote-item-count").addClass("elementdisplaynone");
        },
      });
    }
  });

  jQuery("body").on("click", ".msatq_delete_quote", function (e) {
    let deleted_quote_id = jQuery(this).attr("id");
    var get_quote_list = localStorage.getItem("added_addtoquote_products");
    jQuery(this).parent().parent().remove();
    if (jQuery("#hr_" + deleted_quote_id).length > 0) {
      jQuery("#hr_" + deleted_quote_id).remove();
    }
    if (get_quote_list != null) {
      get_quote_list = JSON.parse(get_quote_list);
      for (var i = 0; i < get_quote_list.length; i++) {
        if (get_quote_list[i].includes(deleted_quote_id)) {
          if (jQuery("body").hasClass("postid-" + get_quote_list[i][0])) {
            jQuery("#msatq_addtoquote_button").html(
              '<button id="msatq_addtoquote" data-product="add_to_quote_' +
                get_quote_list[i][0] +
                '" data-product-id = "' +
                get_quote_list[i][0] +
                '"type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#msatq_addtoquote_modal">Request A Quote</button>'
            );
          }
          get_quote_list.splice(i, 1);

          console.log(get_quote_list);
          var quoteQuantoty = 0;
          if (get_quote_list.length > 0) {
            for (
              var afterdelete = 0;
              afterdelete < get_quote_list.length;
              afterdelete++
            ) {
              quoteQuantoty =
                quoteQuantoty + parseInt(get_quote_list[afterdelete][3]);
            }
            jQuery(".quote-item-count").html(quoteQuantoty);
            localStorage.setItem(
              "added_addtoquote_products",
              JSON.stringify(get_quote_list)
            );
          }
          if (get_quote_list.length == 1) {
            jQuery("hr").remove();
          }
          if (get_quote_list.length == 0) {
            localStorage.removeItem("added_addtoquote_products");
            jQuery("#modal-quote-list-row").addClass("elementdisplaynone");
            jQuery("#msatq_addtoquote_modalLabel").addClass(
              "elementdisplaynone"
            );
            jQuery(".no-quote-in-list").removeClass("elementdisplaynone");
            jQuery(".quote-item-count").addClass("elementdisplaynone");
          }
        }
      }
    }
  });

  jQuery("body").on("change", ".mstq_qunatity", function (e) {
    let update_quantity_quote_id = jQuery(this).attr("id");
    var get_quote_list = localStorage.getItem("added_addtoquote_products");
    let updated_value = parseInt(jQuery(this).val());
    var quoteQuantity = 0;
    jQuery(".mstq_qunatity").each(function () {
      quoteQuantity += parseInt(jQuery(this).val());
    });
    jQuery(".quote-item-count").html(quoteQuantity);
    if (get_quote_list != null) {
      get_quote_list = JSON.parse(get_quote_list);
      for (var i = 0; i < get_quote_list.length; i++) {
        if (get_quote_list[i].includes(update_quantity_quote_id)) {
          get_quote_list[i][3] = updated_value;
          localStorage.setItem(
            "added_addtoquote_products",
            JSON.stringify(get_quote_list)
          );
        }
      }
    }
  });
  jQuery("body").on("click", ".btn-close", function (e) {
    var get_quote_list = localStorage.getItem("added_addtoquote_products");
    // jQuery('#msatq_addtoquote_modal').addClass('animate__slideOutRight');
    if (get_quote_list == null) {
      jQuery(".no-quote-in-list").removeClass("elementdisplaynone");
      jQuery("#msatq_message_after_submission").addClass("elementdisplaynone");
    }
  });
  jQuery("body").on(
    "click",
    ".quote-items-icons, .clickhere_after_quote",
    function (e) {
      var get_quote_list = localStorage.getItem("added_addtoquote_products");
      if (get_quote_list == null) {
        jQuery(".no-quote-in-list").removeClass("elementdisplaynone");
        jQuery("#msatq_message_after_submission").addClass(
          "elementdisplaynone"
        );
      }
    }
  );
});
