<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

class OrderStatusDto
{

    #[Assert\NotBlank]
    #[Groups(['order::read'])]
    public readonly string $status;
    #[Groups(['order::read'])]
    public readonly string $description;
    #[Groups(['order::read'])]
    public readonly string $date;
    public function __construct(string $status, string $description, $date)
    {
        $this->status      = $status;
        $this->description = $description;
        $this->date        = $date;
    }
}
