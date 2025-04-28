<?php

namespace App\Model;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

class UserLoginDto {

    #[Assert\NotBlank]
    #[Groups(['user:login'])]
    public readonly string $email;

    #[Assert\NotBlank]
    #[Groups(['user:login'])]
    public readonly string $password;

    public function __construct(string $email, string $password)
    {
        $this->email = $email;
        $this->password = $password;
    }
}