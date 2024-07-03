<?php
define('ENCRYPTION_KEY', 'a3f1c2e4b5d678e91a2c3d4e5f6071829b0a1c2d3e4f506172839a4b5c6d7e8f'); // Use a key of 32 bytes for AES-256

function encrypt($data) {
    $key = hash('sha256', ENCRYPTION_KEY, true);
    $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
    $encrypted = openssl_encrypt($data, 'aes-256-cbc', $key, 0, $iv);
    return base64_encode($encrypted . '::' . $iv);
}

function decrypt($data) {
    $key = hash('sha256', ENCRYPTION_KEY, true);
    list($encrypted_data, $iv) = explode('::', base64_decode($data), 2);
    return openssl_decrypt($encrypted_data, 'aes-256-cbc', $key, 0, $iv);
}
?>
