<?php
if (!defined('WPINC')) {
	wp_die();
}
if (!class_exists('msatq_MegaSafe__FRONT')) {

	class msatq_MegaSafe__FRONT extends msatq_MegaSafe_Main
	{

		public function __construct()
		{
			add_action('woocommerce_after_add_to_cart_quantity', array($this, 'msatq_MegaSafe_quote_button'));
			add_action('wp_footer', array($this, 'insert_modal_in_footer'));
		}

		public function msatq_MegaSafe_quote_button()
		{
			$current_product_id = get_the_ID();
			$button_and_modal = '<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css"><script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js"></script><div id="msatq_addtoquote_button"><button id="msatq_addtoquote" data-product="add_to_quote_' . $current_product_id . '" data-product-id = "' . $current_product_id . '"type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#msatq_addtoquote_modal">Request A Quote
			</button><input type="hidden" id="msatq_plugindirurl" value=' . MSATQ_PLUGIN_DIR . '></div>';
			echo $button_and_modal;
		}
		public function insert_modal_in_footer()
		{
			$modal_in_footer = '<div class="modal fade right" id="msatq_addtoquote_modal" tabindex="-1" aria-labelledby="msatq_addtoquote_modalLabel" aria-hidden="true"><div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="msatq_addtoquote_modalLabel">Your Quote</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body" id="msatq_modal_body">
					<div class="row elementdisplaynone" id="modal-quote-list-row">
						<div class="col-md-6 quotes_product_list" id="quotes_added_product_list">
						</div>
						<div class="col-md-6 request-a-quote-form">
							<form class="msatq_submit quote">
								<h5 class="msatq_form_heading">Submit you quote by filling out the form below</h5>
								<div class="form-row">
									<div class="col-md-12"> <input type="text" class="form-control" id="msatq_firstname" placeholder="First Name*"> </div>
									<div class="col-md-12"> <input type="text" class="form-control" id="msatq_lastname" placeholder="Last Name*"> </div>
									<div class="col-md-12">
										<div class="input-group"> <input type="email" class="form-control" id="msatq_email" placeholder="Email*"> </div>
									</div>
									<div class="col-md-12"><input type="text" class="form-control" id="msatq_phonenumber" placeholder="Phone Number*" > </div>
									<div class="col-md-12"> <textarea type="text" class="form-control" style="height:198px" id="msatq_Message" placeholder="Message"></textarea> </div>
								</div>
								<input class="btn btn-warning" id="msatq_submit_your_request" type="button" value="Submit your quote">
								<div class="spinnerloader elementdisplaynone spinner-border text-dark" role="status"></div>
							</form>
						</div>
					</div>
					<div id="empty-quote-list-wrapper" class="no-quote-in-list">
						<h4 class="msatq_message_tagline">Its empty!</h4>
						<p class="msatq_byline text-center">Add an item to your quotes list!</p>
					</div>
					<div id="msatq_message_after_submission" class="elementdisplaynone">
						<h4 class="msatq_message_tagline">Your quote has been successfully submitted</h4>
						<p class="msatq_byline text-center">A Megasafe representative will reach out to you as soon as possible and help you fulfill your order!</p>
					</div>
				</div>
				</div>
			</div>
			</div>';
			echo $modal_in_footer;
		}
	}
	new msatq_MegaSafe__FRONT();
}
