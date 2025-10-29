<?php

use Illuminate\Support\Facades\Crypt;

if (! function_exists('encrypt_response')) {
    function encrypt_response($data, $status = 200) {
        $json = json_encode($data);
        $encrypted = Crypt::encryptString($json);
        return response($encrypted, $status)
            ->header('Content-Type', 'application/json');
    }
}

if (! function_exists('decrypt_request')) {
    function decrypt_request($encryptedContent) {
        try {
            $decrypted = Crypt::decryptString($encryptedContent);
            return json_decode($decrypted, true);
        } catch (\Exception $e) {
            return null;
        }
    }
}