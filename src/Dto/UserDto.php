<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

class UserDto {

    #[Assert\NotBlank]
    #[Groups(['user:me'])]
    public readonly string $email;

    #[Assert\NotBlank]
    #[Groups(['user:me'])]
    public readonly string $password;

    public function __construct(string $email, string $password)
    {
        $this->email = $email;
        $this->password = $password;
    }
}