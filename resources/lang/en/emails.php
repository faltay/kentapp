<?php

return [

    'welcome' => [
        'subject'      => 'Welcome to :app_name!',
        'heading'      => 'Welcome aboard!',
        'greeting'     => 'Hi :name,',
        'body'         => 'Your account has been created successfully. Here is what you can do with your restaurant panel:',
        'feature_menu' => 'Create and manage your digital menu',
        'feature_qr'   => 'Generate QR codes for your tables',
        'feature_ai'   => 'Use AI to parse and translate your menu',
        'cta'          => 'Go to Dashboard',
        'footer_note'  => 'If you did not create this account, you can safely ignore this email.',
    ],

    'subscription_started' => [
        'subject'  => 'Subscription Activated',
        'heading'  => 'Your subscription is now active!',
        'greeting' => 'Hi :name,',
        'body'     => 'Your subscription has been successfully activated. Here are the details:',
        'plan'     => 'Plan',
        'renewal'  => 'Renewal Date',
        'amount'   => 'Amount Paid',
        'status'   => 'Status',
        'active'   => 'Active',
        'free'     => 'Free',
        'cta'      => 'View Billing',
    ],

    'subscription_cancelled' => [
        'subject'          => 'Subscription Cancelled',
        'heading'          => 'Your subscription has been cancelled.',
        'greeting'         => 'Hi :name,',
        'body'             => 'Your subscription has been cancelled. You will continue to have access until the end of your billing period.',
        'plan'             => 'Plan',
        'access_until'     => 'Access Until',
        'status'           => 'Status',
        'cancelled'        => 'Cancelled',
        'resubscribe_note' => 'You can resubscribe at any time from the billing page.',
        'cta'              => 'View Plans',
    ],

    'account_created' => [
        'subject'          => 'Your Account Has Been Created — :app_name',
        'heading'          => 'Your account is ready!',
        'greeting'         => 'Hi :name,',
        'body'             => 'An account has been created for you by the administrator. You can log in using the credentials below:',
        'email'            => 'Email',
        'password'         => 'Password',
        'password_warning' => 'For your security, please change your password after your first login.',
        'cta'              => 'Log In',
        'footer_note'      => 'This email was sent by :app_name. If you believe this was a mistake, please contact the administrator.',
    ],

    'payment_failed' => [
        'subject'      => 'Payment Failed',
        'heading'      => 'We could not process your payment.',
        'greeting'     => 'Hi :name,',
        'body'         => 'Unfortunately, your recent payment could not be processed. Please update your payment method to keep your subscription active.',
        'restaurant'   => 'Restaurant',
        'amount'       => 'Amount',
        'status'       => 'Status',
        'failed'       => 'Failed',
        'action_note'  => 'Please visit the billing page to retry your payment or choose a new plan.',
        'cta'          => 'Go to Billing',
    ],

];
