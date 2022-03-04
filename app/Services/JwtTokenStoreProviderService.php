<?php

namespace App\Services;
use App\Models\JwtToken;
use App\Interfaces\ITokenStoreService;
use Illuminate\Support\Arr;

class JwtTokenStoreProviderService implements ITokenStoreService
{
    public function getItem($userId)
    {
        return JwtToken::where('user_id', $userId)->first();
    }

    public function store($userId, $model)
    {
        JwtToken::updateOrCreate([
            'user_id' => $userId,
        ], Arr::only($model, [
            'unique_id',
            'token_title',
            'refreshed_at',
            'expires_at'
        ]));
    }

    public function invalidate($userId, $expiryDateTime)
    {
        return JwtToken::where('user_id', $userId)
            ->update([
                'expires_at' => $expiryDateTime
            ]);
    }
}