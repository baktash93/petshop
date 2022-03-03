<?php

namespace App\Services;
use App\Interfaces\IAuthTokenService;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\Clock\SystemClock;

class JWTAuthTokenService implements IAuthTokenService {
    private $config;
    private $constraints;

    function __construct() {
        $this->init(new AsymmetricKeyGenerator());
        $this->setConstraints();
    }

    private function init($gen) {
        $this->config = Configuration::forSymmetricSigner(
            new Sha256(),
            InMemory::file($gen->getPath())
        );
    }

    private function setConstraints() {
        $this->constraints = [
            new \Lcobucci\JWT\Validation\Constraint\IssuedBy(config('values.APP_URL')),
            new \Lcobucci\JWT\Validation\Constraint\SignedWith($this->config->signer(), $this->config->verificationKey()),
            new \Lcobucci\JWT\Validation\Constraint\LooseValidAt($this->getSystemClock(config('values.TIMEZONE')))
        ];
        $this->config->setValidationConstraints(...$this->constraints);
    }

    private function getSystemClock($timezone): SystemClock {
        return new SystemClock(new \DateTimeZone($timezone));
    }

    function sign($key, $claim, $ttl) {
            $this->token = $this->config
                ->builder()
                ->issuedBy(config('values.APP_URL'))
                ->expiresAt((new \DateTimeImmutable())->modify($ttl))
                ->withClaim($key, $claim)
                ->getToken($this->config->signer(), $this->config->signingKey())
                ->toString();
    }

    function verify($token): bool {
        return $this->config->validator()->validate($this->parse($token), ...$this->config->validationConstraints());
    }

    function parse($strToken) {
        return $this
            ->config
            ->parser()
            ->parse($strToken);
    }

    function getToken(): String {
        return $this->token;
    }
}