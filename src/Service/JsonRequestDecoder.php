<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

final class JsonRequestDecoder
{
    /** @return array<string, mixed> */
    public function decode(Request $request): array
    {
        $content = trim($request->getContent());
        if ($content === '') {
            throw new BadRequestHttpException('Request body must not be empty.');
        }

        try {
            $data = json_decode($content, false, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException $exception) {
            throw new BadRequestHttpException('Invalid JSON payload.');
        }

        if (!is_object($data)) {
            throw new BadRequestHttpException('JSON payload must be an object.');
        }

        return get_object_vars($data);
    }
}
