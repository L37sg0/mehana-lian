<?php

namespace App\Service;

use Symfony\Component\Uid\Uuid;

class CredentialsGenerator
{
    public function generateCredentials()
    {
        return [
            'clientId' => Uuid::v4(),
            'clientSecret' => Uuid::v4()
        ];
    }
}