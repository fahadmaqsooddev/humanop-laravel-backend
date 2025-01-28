<?php


switch (env('APP_ENV')) {
    case 'local':
    case 'development':
        $mail_address = 'info@dev.humanop.com';
        break;

    case 'staging':
        $mail_address = 'info@staging.humanop.com';
        break;

    case 'production':
        $mail_address = 'info@beta.humanop.com';
        break;

    default:
        $mail_address = 'info@default.humanop.com'; // Optional
        break;
}

return [
    'mail_address' => $mail_address,
    'mail_name' => 'HumanOp',
];

