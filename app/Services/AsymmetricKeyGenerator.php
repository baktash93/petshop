<?php

namespace App\Services;

class AsymmetricKeyGenerator {
    public function generate() {
        $privateKeyResource = openssl_pkey_new([
            'private_key_bits' => 2048,
            'private_key_type' => OPENSSL_KEYTYPE_RSA
        ]);
        openssl_pkey_export_to_file($privateKeyResource, storage_path('app') . '/app-private-key.pem');
        // $privateKeyDetailsArray = openssl_pkey_get_details($privateKeyResource);
        openssl_free_key($privateKeyResource);
    }

    public function getPath() {
        if (!file_exists(storage_path('app') . '/app-private-key.pem')) {
            $this->generate();
        }
        return storage_path('app') . '/app-private-key.pem';
    }
}