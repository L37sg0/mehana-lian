<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\AccessToken\AccessTokenExtractorInterface;

class AccessTokenExtractor implements AccessTokenExtractorInterface
{

    public function extractAccessToken(Request $request): ?string
    {
        $accessToken = $request->headers->get('Authorization');
        $accessToken = str_replace('Bearer ', '', $accessToken);
        return $accessToken;
    }
}