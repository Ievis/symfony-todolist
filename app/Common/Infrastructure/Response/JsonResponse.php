<?php

declare(strict_types=1);

namespace App\Common\Infrastructure\Response;

use Symfony\Component\HttpFoundation\JsonResponse as BaseJsonResponse;

class JsonResponse extends BaseJsonResponse
{
    /**
     * @param mixed|null $data
     * @param int $status
     * @param array $headers
     * @param bool $json
     * @return self
     */
    public function ok(
        mixed $data = null,
        int $status = 200,
        array $headers = [],
        bool $json = false,
    ): self
    {
        return new self([
            'ok' => true,
            'data' => $data,
        ], $status, $headers, $json);
    }

    /**
     * @param mixed|null $data
     * @param mixed|null $errors
     * @param int $status
     * @param array $headers
     * @param bool $json
     * @return self
     */
    public function error(
        mixed $errors = null,
        int $status = 422,
        array $headers = [],
        bool $json = false,
    ): self
    {
        return new self([
            'ok'     => false,
            'errors' => $errors,
        ], $status, $headers, $json);
    }
}