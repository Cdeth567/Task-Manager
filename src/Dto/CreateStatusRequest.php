<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

final class CreateStatusRequest
{
    public function __construct(
        #[Assert\NotBlank(message: 'name is required.')]
        #[Assert\Length(max: 100)]
        #[Assert\Regex(pattern: '/^[a-z0-9]+(?:_[a-z0-9]+)*$/', message: 'name must use snake_case.')]
        public readonly mixed $name = null,

        #[Assert\NotBlank(message: 'title is required.')]
        #[Assert\Length(max: 255)]
        public readonly mixed $title = null,
    ) {
    }
}
