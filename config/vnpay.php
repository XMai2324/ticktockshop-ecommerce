<?php

return [
    'vnp_TmnCode'   => env('VNP_TMN_CODE', 'ABC12345'),
    'vnp_HashSecret'=> env('VNP_HASH_SECRET', 'SECRETKEY123456789'),
    'vnp_Url'       => env('VNP_URL', 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html'),
    'vnp_Returnurl' => env('VNP_RETURN_URL', 'https://yourdomain.com/vnpay/return'),
];
