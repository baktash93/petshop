<?php

namespace App\Interfaces;

interface ITokenStoreService {
    public function getItem($userId);
    public function store($userId, $model);
    public function invalidate($userId, $expiryDateTime);
}