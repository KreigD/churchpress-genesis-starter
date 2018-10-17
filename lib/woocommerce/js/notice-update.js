/**
 * Trigger AJAX request to save state when the WooCommerce notice is dismissed.
 *
 * @version 2.3.0
 *
 * @author ChurchPress
 * @license GPL-3.0+
 * @package GenesisSample
 */

jQuery( document ).on(
	'click', '.cp-genesis-starter-woocommerce-notice .notice-dismiss', function() {

		jQuery.ajax(
			{
				url: ajaxurl,
				data: {
					action: 'genesis_sample_dismiss_woocommerce_notice'
				}
			}
		);

	}
);
