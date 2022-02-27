<?php

namespace App\Interfaces;

interface IAuthTokenService {
    function sign($key, $claim, $ttl);
    function verify();
    function getToken(): String;
}