<?php

return [
    // the secret key used to encrypt cookie data. Must be
    // 36 bits long
    'secret_key' => '12345678901234567890123456789012',

    'logged_in_cookie_ttl_in_minutes' => 120,

    // the host of the cas server
    'host'       => 'https://localhost',
    'port'       => 443,

    // Where the cas services live on the CAS SSO server
    'uri'   => 'cas'
];