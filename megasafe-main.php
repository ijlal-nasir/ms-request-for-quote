<?php

/**
 * Plugin Name: MS Request for Quote
 * Description: MS Request for Quote
 * Version: 1.0.0
 * Author: Ijlal Nasir
 * License: GPL2
 * Text Domain: megasafe-atq
 * Project Prefix : msatq_
 */


if (!defined('WPINC')) {
	wp_die();
}

if (!class_exists('msatq_MegaSafe_Main')) {

	class msatq_MegaSafe_Main
	{

		public function __construct()
		{
			$this->msatq_WC_module_constants();
			add_filter('wp_mail_content_type', array($this, 'MegaSafe_Main_mail_content_type'), 10, 1);
			add_action('wp_ajax_nopriv_msatq_request_quote_email', array($this, 'msatq_request_quote_email'));
			add_action('wp_ajax_msatq_request_quote_email', array($this, 'msatq_request_quote_email'));
			if (!is_admin()) {
				wp_enqueue_style('addtoquote-bootstrap-styles', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css');
				wp_enqueue_style('addtoquote-styles', plugins_url('assets/css/add-to-quote.css', __FILE__), array(), '1.0');
				wp_enqueue_style('addtoquote-animate-styles', 'https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css');
				wp_enqueue_script('addtoquote-bootstrap-scripts', "https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js", array(), '5.3.0-alpha3', true);
				wp_enqueue_script('addtoquote-script', plugins_url('assets/js/add-to-quote.js', __FILE__), array(), '1.0', true);
				include_once(plugin_dir_path(__FILE__) . 'front/megasafe-front.php');
			}
		}
		public function msatq_WC_module_constants()
		{

			if (!defined('MSATQ_URL')) {
				define('MSATQ_URL', plugin_dir_url(__FILE__));
			}

			if (!defined('MSATQ_BASENAME')) {
				define('MSATQ_BASENAME', plugin_basename(__FILE__));
			}

			if (!defined('MSATQ_PLUGIN_DIR')) {
				define('MSATQ_PLUGIN_DIR', plugin_dir_path(__FILE__));
			}
		}
		public function MegaSafe_Main_mail_content_type($content_type)
		{
			return 'text/html';
		}
		public function msatq_request_quote_email()
		{
			global $wpdb;
			$quotes_list = $_REQUEST['quotes_list'];
			$first_name = $_REQUEST['first_name'];
			$last_name = $_REQUEST['last_name'];
			$email = $_REQUEST['email'];
			$phone = $_REQUEST['phone'];
			$message = $_REQUEST['message'];
			$order_table = $wpdb->prefix . 'wc_order_stats';
			$date_for_title = date("d-m-y @ h:i:s A");
			$insert_post_id = wp_insert_post(array(
				'post_title' => 'order ' . $date_for_title,
				'post_status' => 'wc-pending',
				'post_type' => 'shop_order',
			));
			update_post_meta($insert_post_id, '_customer_user', get_current_user_id());
			update_post_meta($insert_post_id, '_customer_first_name', $first_name);
			update_post_meta($insert_post_id, '_customer_last_name', $last_name);
			update_post_meta($insert_post_id, '_customer_email', $email);
			update_post_meta($insert_post_id, '_customer_phone', $phone);
			update_post_meta($insert_post_id, '_customer_message', $message);
			update_post_meta($insert_post_id, '_billing_email', get_bloginfo('admin_email'));
			update_post_meta($insert_post_id, '_order_total', 0);
			update_post_meta($insert_post_id, 'order_status', 'pending');

			$wpdb->insert($order_table, array(
				'order_id' => $insert_post_id,
				'status' => 'wc-pending'
			));
			for ($qList = 0; $qList < sizeof($quotes_list); $qList++) {
				$order_item_id = wc_add_order_item(
					$insert_post_id,
					array(
						'order_item_name' => $quotes_list[$qList][1],
						'order_item_type' => 'line_item',
					)
				);
				if ($order_item_id) {
					wc_add_order_item_meta($order_item_id, '_qty', $quotes_list[$qList][3], true); // quantity
					wc_add_order_item_meta($order_item_id, '_product_id', $quotes_list[$qList][0], true); // ID of the product
					wc_add_order_item_meta($order_item_id, '_line_subtotal', 0, true); // price per item
					wc_add_order_item_meta($order_item_id, '_line_total', 0, true); // total price
				}
			}

			$email_message_body = '<div style="padding: 25px; background-color: #f0cc5a">
			<div style="max-width: 768px; margin: auto">
				<h2 style="font-size: 40px">Quote Requested</h2>
			</div>
			</div>
			<div style="padding: 40px 25px; max-width: 768px; margin: auto; color: #57626c">
			<div>
				<p style="font-size: 18px"> You have sent a quote request for the following products: </p>
				<h3 style="color: #f0cc5a; font-size: 32px">Request #' . $insert_post_id . '</h3>
			</div>
			<table style="width: 100%; text-align: left; padding: 0 35px 35px">
			<tr>
			<th style="font-weight: 600; font-size: 18px">Product</th>
			<th>Qty</th>
			</tr>';
			for ($i = 0; $i < sizeof($quotes_list); $i++) {
				echo $i;
				$email_message_body .= '<tr>
				<td style="display: flex;justify-content: space-between;margin-top: 10px;">
				<img style="width:70px;object-fit: cover;flex-basis: 18%;" src="' . $quotes_list[$i][2] . '" alt="" />
				<div style="flex-basis: 70%;margin-left: 5%;">
					<h4 style="text-decoration: underline"> ' . $quotes_list[$i][1] . ' </h4>
				</div>
				</td>
				<td>' . $quotes_list[$i][3] . '</td>
			</tr>';
			}

			$email_message_body .= '</table>
				<p style="padding: 10px 0px; font-size: 18px; margin-bottom: 35px"> You can see details here: <span style="text-decoration: underline">#' . $insert_post_id . '</span>
				</p>
				<h3 style="color: #f0cc5a; font-size: 32px; line-height: 10px"> Your message </h3>
				<p style="font-size: 18px; margin-bottom: 45px"> ' . $message . ' </p>
				<h3 style="color: #f0cc5a; font-size: 32px; line-height: 10px"> Your details </h3>
				<p style="font-size: 14px; font-weight: 600; line-height: 10px"> First Name : <span style="font-weight: 300">' . $first_name . '</span>
				</p>
				<p style="font-size: 14px; font-weight: 600; line-height: 10px"> Last Name : <span style="font-weight: 300">' . $last_name . '</span>
				</p>
				<p style="font-size: 14px; font-weight: 600; line-height: 10px"> Email : <span style="font-weight: 300; text-decoration: underline">' . $email . '</span>
				</p>
				<p style="font-size: 14px; font-weight: 600; line-height: 10px"> Phone Number : <span style="font-weight: 300">' . $phone . '</span>
				</p>
			</div>';
			$customer_email_message_body = '<div style="padding: 25px; background-color: #f0cc5a;border-radius:10px">
			<div style="max-width: 768px; margin: auto">
				<h2 style="font-size: 30px;font-family: revert;line-height:37px;font-weight: 900;">We’ve received your quote</h2>
			</div>
			</div>
			<div style="max-width: 768px; margin: auto">
				<p style="font-size: 18px;font-family: revert;line-height:22px;font-weight: 400;">A Megasafe representative will reach out to you as soon as possible and help you fulfill your order!</p>
			</div>
			<div style="padding: 40px 25px; max-width: 768px; margin: auto; color: #57626c">
			<div>
				<h3 style="color: #f0cc5a; font-size: 32px">Request #' . $insert_post_id . '</h3>
			</div>
			<table style="width: 100%; text-align: left; padding: 0 35px 35px">
			<tr>
			<th style="font-weight: 600; font-size: 18px">Product</th>
			<th>Qty</th>
			</tr>';
			for ($j = 0; $j < sizeof($quotes_list); $j++) {
				echo $j;
				$customer_email_message_body .= '<tr>
				<td style="display: flex;justify-content: space-between;margin-top: 10px;">
				<img style="width:70px;object-fit: cover;flex-basis: 18%;" src="' . $quotes_list[$j][2] . '" alt="" />
				<div style="flex-basis: 70%;margin-left: 5%;">
					<h4 style="text-decoration: underline"> ' . $quotes_list[$j][1] . ' </h4>
				</div>
				</td>
				<td>' . $quotes_list[$j][3] . '</td>
			</tr>';
			}


			$customer_email_message_body .= '</table>
				<h3 style="color: #f0cc5a; font-size: 32px; line-height: 10px"> Your details </h3>
				<p style="font-size: 14px; font-weight: 600; line-height: 10px"> First Name : <span style="font-weight: 300">' . $first_name . '</span>
				</p>
				<p style="font-size: 14px; font-weight: 600; line-height: 10px"> Last Name : <span style="font-weight: 300">' . $last_name . '</span>
				</p>
				<p style="font-size: 14px; font-weight: 600; line-height: 10px"> Email : <span style="font-weight: 300; text-decoration: underline">' . $email . '</span>
				</p>
				<p style="font-size: 14px; font-weight: 600; line-height: 10px"> Phone Number : <span style="font-weight: 300">' . $phone . '</span>
				</p>
				<h3 style="color: #f0cc5a; font-size: 32px; line-height: 10px"> Your message </h3>
				<p style="font-size: 18px; margin-top: 45px"> ' . $message . ' </p>
			</div>';
			$headers[] = 'From: ' . get_bloginfo();
			wp_mail($email, 'Request a Quote', $customer_email_message_body, $headers);
			wp_mail(get_bloginfo('admin_email'), 'Request a Quote', $email_message_body, $headers);
			wp_mail('hookway@optonline.net', 'Request a Quote', $email_message_body, $headers);
			wp_die();
		}
	}
	new msatq_MegaSafe_Main();
}
