<?php

namespace App\Helpers;

class StripeConfig
{
    /**
     * Initialize Stripe with the secret key
     */
    public static function init(): void
    {
        \Stripe\Stripe::setApiKey($_ENV['STRIPE_SECRET_KEY']);
    }

    /**
     * Get publishable key for frontend
     */
    public static function getPublishableKey(): string
    {
        return $_ENV['STRIPE_PUBLISHABLE_KEY'];
    }
}
