<?php

namespace App\Security;

use App\Entity\AccessToken;
use App\Repository\AccessTokenRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\AccessToken\AccessTokenHandlerInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;

class AccessTokenHandler implements AccessTokenHandlerInterface
{
    public function __construct(
        private UserPasswordHasherInterface $passwordHasher,
        private AccessTokenRepository $tokenRepository
    ) {
    }

    /**
     * @inheritDoc
     */
    public function getUserBadgeFrom(#[\SensitiveParameter] string $accessToken): UserBadge
    {
        $tokenParams = explode(':', base64_decode($accessToken));
        $identifier = '';
        if (is_array($tokenParams)) {
            $identifier = $tokenParams[1];
        }

        $token = $this->tokenRepository->findOneBy(['identifier' => $identifier]);
        $tokenValue = $accessToken;

        if (!$this->isTokenValid($token, $tokenValue)) {
            $identifier = '';
        }

        $userBadge = new UserBadge($identifier);

        return $userBadge;
    }

    public function isTokenValid(?AccessToken $token, string $tokenValue)
    {
        if (!empty($token) && !$token->isExpired() && $this->passwordHasher->isPasswordValid($token, $tokenValue)) {
            return true;
        }
        return false;
    }
}