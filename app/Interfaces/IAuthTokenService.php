<?php

namespace App\Interfaces;

interface IAuthTokenService {
    function sign($key, $claim, $ttl);
    function verify($token): bool;
    function getToken(): String;
}