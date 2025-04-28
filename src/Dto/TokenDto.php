<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

class TokenDto
{

    public function __construct(
        #[Groups(['user:token'])]
        #[Assert\NotBlank()]
        public string $token,
        
    ) {}
}
