<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

final class ChangeTaskStatusRequest
{
    public function __construct(
        #[Assert\NotBlank(message: 'status is required.')]
        #[Assert\Length(max: 100)]
        #[Assert\Regex(pattern: '/^[a-z0-9]+(?:_[a-z0-9]+)*$/', message: 'status must use snake_case.')]
        public readonly mixed $status = null,
    ) {
    }
}
