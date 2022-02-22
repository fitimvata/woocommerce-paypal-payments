<?php
/**
 * Renders the onboarding options.
 *
 * @package WooCommerce\PayPalCommerce\Onboarding\Render
 */

declare(strict_types=1);

namespace WooCommerce\PayPalCommerce\Onboarding\Render;

/**
 * Class OnboardingRenderer
 */
class OnboardingOptionsRenderer {
	/**
	 * The module url.
	 *
	 * @var string
	 */
	private $module_url;

	/**
	 * OnboardingOptionsRenderer constructor.
	 *
	 * @param string $module_url The module url (for assets).
	 */
	public function __construct( string $module_url ) {
		$this->module_url = $module_url;
	}

	/**
	 * Renders the onboarding options.
	 *
	 * @param bool $is_shop_supports_dcc Whether the shop can use DCC (country, currency).
	 */
	public function render( bool $is_shop_supports_dcc ): string {
		return '
<ul class="ppcp-onboarding-options">
	<li>
		<label><input type="checkbox" disabled checked> ' .
			__( 'Enable PayPal Payments — includes PayPal, Venmo, Pay Later — with fraud protection', 'woocommerce-paypal-payments' ) . '
		</label>
	</li>
	<li>
		<label><input type="checkbox" id="ppcp-onboarding-accept-cards" checked> ' .
			__( 'Securely accept all major credit & debit cards on the strength of the PayPal network', 'woocommerce-paypal-payments' ) . '
		</label>
	</li>
	<li>' . $this->render_dcc( $is_shop_supports_dcc ) . '</li>
</ul>';
	}

	/**
	 * Renders the onboarding DCC options.
	 *
	 * @param bool $is_shop_supports_dcc Whether the shop can use DCC (country, currency).
	 */
	private function render_dcc( bool $is_shop_supports_dcc ): string {
		$items = array();

		if ( $is_shop_supports_dcc ) {
			$dcc_table_rows = array(
				$this->render_table_row(
					__( 'Credit & Debit Card form fields', 'woocommerce-paypal-payments' ),
					__( 'Customizable user experience', 'woocommerce-paypal-payments' )
				),
				$this->render_table_row(
					__( 'Credit & Debit Card pricing', 'woocommerce-paypal-payments' ),
					__( '2.59% + $0.49', 'woocommerce-paypal-payments' )
				),
				$this->render_table_row(
					__( 'Seller Protection', 'woocommerce-paypal-payments' ),
					__( 'Yes', 'woocommerce-paypal-payments' ),
					__( 'On eligible PayPal transactions', 'woocommerce-paypal-payments' )
				),
				$this->render_table_row(
					__( 'Advanced Fraud Protection', 'woocommerce-paypal-payments' ),
					__( 'Yes', 'woocommerce-paypal-payments' )
				),
				$this->render_table_row(
					__( 'Chargeback Protection', 'woocommerce-paypal-payments' ),
					__( 'Yes', 'woocommerce-paypal-payments' ),
					__( 'Extra 0.4% per transaction', 'woocommerce-paypal-payments' )
				),
				$this->render_table_row(
					__( 'Additional Vetting and Underwriting Required', 'woocommerce-paypal-payments' ),
					__( 'Yes', 'woocommerce-paypal-payments' )
				),
				$this->render_table_row(
					__( 'Seller Account Type', 'woocommerce-paypal-payments' ),
					__( 'Business', 'woocommerce-paypal-payments' )
				),
			);
			$items[]        = '
<li>
	<label><input type="radio" id="ppcp-onboarding-dcc-acdc" name="ppcp_onboarding_dcc" value="acdc" checked ' .
				'data-screen-url="' . $this->get_screen_url( 'acdc' ) . '"> ' .
			__( 'Advanced Card Processing', 'woocommerce-paypal-payments' ) . '
	</label>
	<table>
		' . implode( '', $dcc_table_rows ) . '
	</table>
</li>';
		}

		$basic_table_rows = array(
			$this->render_table_row(
				__( 'Credit & Debit Card form fields', 'woocommerce-paypal-payments' ),
				__( 'Prebuilt user experience', 'woocommerce-paypal-payments' )
			),
			$this->render_table_row(
				__( 'Credit & Debit Card pricing', 'woocommerce-paypal-payments' ),
				__( '3.49% + $0.49', 'woocommerce-paypal-payments' )
			),
			$this->render_table_row(
				__( 'Seller Protection', 'woocommerce-paypal-payments' ),
				__( 'Yes', 'woocommerce-paypal-payments' ),
				__( 'On eligible PayPal transactions', 'woocommerce-paypal-payments' )
			),
			$this->render_table_row(
				__( 'Seller Account Type', 'woocommerce-paypal-payments' ),
				__( 'Business or Personal', 'woocommerce-paypal-payments' )
			),
		);
		$items[]          = '
<li ' . ( ! $is_shop_supports_dcc ? 'style="display: none;"' : '' ) . '>
	<label><input type="radio" id="ppcp-onboarding-dcc-basic" name="ppcp_onboarding_dcc" value="basic" ' .
			( ! $is_shop_supports_dcc ? 'checked' : '' ) .
			' data-screen-url="' . $this->get_screen_url( 'basic' ) . '"' .
			'> ' .
		__( 'Standard Card Processing', 'woocommerce-paypal-payments' ) . '
	</label>
	<table>
		' . implode( $basic_table_rows ) . '
	</table>
</li>';

		return '
<div class="ppcp-onboarding-cards-options">
	<ul id="ppcp-onboarding-dcc-options" class="ppcp-onboarding-options-sublist">' .
			implode( '', $items ) .
			'
	</ul>
	<div class="ppcp-onboarding-cards-screen">' .
			( $is_shop_supports_dcc ? '<img id="ppcp-onboarding-cards-screen-img" />' : '' ) . '
	</div>
</div>';
	}

	/**
	 * Returns HTML of a row for the cards options tables.
	 *
	 * @param string $header The text in the first cell.
	 * @param string $value The text in the second cell.
	 * @param string $note The additional description text, such as about conditions.
	 * @return string
	 */
	private function render_table_row( string $header, string $value, string $note = '' ): string {
		$value_html = $value;
		if ( $note ) {
			$value_html .= '<br/><span class="ppcp-muted-text">' . $note . '</span>';
		}

		return "
<tr>
	<td>$header</td>
	<td>$value_html</td>
</tr>";
	}

	/**
	 * Returns the screen image URL.
	 *
	 * @param string $key The image suffix, 'acdc' or 'basic'.
	 * @return string
	 */
	private function get_screen_url( string $key ): string {
		return untrailingslashit( $this->module_url ) . "/assets/images/cards-screen-$key.png";
	}
}
