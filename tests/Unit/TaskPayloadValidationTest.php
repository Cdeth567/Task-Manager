<?php

namespace App\Tests\Unit;

use App\Dto\ChangeTaskStatusRequest;
use App\Dto\CreateStatusRequest;
use App\Dto\CreateTaskRequest;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Validation;

final class TaskPayloadValidationTest extends TestCase
{
    public function testCreateTaskRequiresTitle(): void
    {
        $validator = Validation::createValidatorBuilder()->enableAttributeMapping()->getValidator();
        $violations = $validator->validate(new CreateTaskRequest(null, null));
        self::assertNotEmpty($violations);
    }

    public function testStatusNameMustBeSnakeCase(): void
    {
        $validator = Validation::createValidatorBuilder()->enableAttributeMapping()->getValidator();
        $violations = $validator->validate(new CreateStatusRequest('Bad Name', 'Bad status'));
        self::assertNotEmpty($violations);
    }

    public function testTaskStatusMustBeSnakeCase(): void
    {
        $validator = Validation::createValidatorBuilder()->enableAttributeMapping()->getValidator();
        $violations = $validator->validate(new ChangeTaskStatusRequest('in progress'));
        self::assertNotEmpty($violations);
    }
}
