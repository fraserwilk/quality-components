// Keep the navigation cart badge in sync after WooCommerce updates the classic
// cart form via AJAX. The refreshed cart markup is the source of truth here,
// which also prevents a stale cart fragment from restoring the previous count.
(function ($) {
	'use strict';

	function updateCartBadgeFromForm() {
		var cartForm = document.querySelector(
			'.woocommerce-cart-form, .wp-block-woocommerce-cart'
		);

		if (!cartForm) {
			return;
		}

		var count = Array.prototype.reduce.call(
			cartForm.querySelectorAll(
				'.cart_item input.qty, ' +
					'.wc-block-components-quantity-selector__input'
			),
			function (total, input) {
				var quantity = parseFloat(input.value);

				return total + (Number.isNaN(quantity) ? 0 : quantity);
			},
			0
		);

		document.querySelectorAll('.cart-count').forEach(function (badge) {
			badge.textContent = count;
			badge.style.display = count > 0 ? '' : 'none';
		});
	}

	$(document.body).on(
		'updated_wc_div wc_fragments_refreshed',
		updateCartBadgeFromForm
	);

	$(document).on(
		'input change',
		'.woocommerce-cart-form .cart_item input.qty, ' +
			'.wp-block-woocommerce-cart ' +
			'.wc-block-components-quantity-selector__input',
		updateCartBadgeFromForm
	);

	$(document).on(
		'click',
		'.wp-block-woocommerce-cart ' +
			'.wc-block-components-quantity-selector__button',
		function () {
			// React updates the controlled quantity input after the click handler.
			window.setTimeout(updateCartBadgeFromForm, 0);
		}
	);
})(jQuery);
