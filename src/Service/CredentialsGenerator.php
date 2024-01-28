<?php

namespace App\Service;

use Symfony\Component\Uid\Uuid;

class CredentialsGenerator
{
    /**
     * @return array<string,string>
     */
    public function generateCredentials(): array
    {
        return [
            'clientId' => Uuid::v4(),
            'clientSecret' => Uuid::v4()
        ];
    }
}