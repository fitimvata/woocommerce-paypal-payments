<?php
declare(strict_types=1);

namespace Inpsyde\PayPalCommerce\WcGateway\Notice;

class AuthorizeOrderActionNotice
{
    const NO_INFO = 81;
    const ALREADY_CAPTURED = 82;
    const FAILED = 83;
    const SUCCESS = 84;
    const NOT_FOUND = 85;

    public function registerMessages(array $messages): array
    {
        $messages['shop_order'][self::NO_INFO] = __(
            'Could not retrieve information. Try again later.',
            'woocommerce-paypal-gateway'
        );
        $messages['shop_order'][self::ALREADY_CAPTURED] = __(
            'Payment already captured.',
            'woocommerce-paypal-gateway'
        );
        $messages['shop_order'][self::FAILED] = __(
            'Failed to capture. Try again later.',
            'woocommerce-paypal-gateway'
        );
        $messages['shop_order'][self::NOT_FOUND] = __(
            'Could not find payment to process.',
            'woocommerce-paypal-gateway'
        );
        $messages['shop_order'][self::SUCCESS] = __(
            'Payment successfully captured.',
            'woocommerce-paypal-gateway'
        );

        return $messages;
    }

    public static function displayMessage(int $messageCode): void
    {
        add_filter(
            'redirect_post_location',
            function ($location) use ($messageCode) {
                return add_query_arg(
                    'message',
                    $messageCode,
                    $location
                );
            }
        );
    }
}