<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

final class CreateTaskRequest
{
    public function __construct(
        #[Assert\NotBlank(message: 'title is required.')]
        #[Assert\Length(max: 255)]
        public readonly mixed $title = null,

        #[Assert\Length(max: 10000)]
        public readonly mixed $description = null,
    ) {
    }
}
