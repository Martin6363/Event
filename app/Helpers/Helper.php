<?php

namespace App\Helpers;


use Throwable;

if (!function_exists('parseDtoError')) {
    function parseDtoError(Throwable $e): array
    {
        $message = $e->getMessage();

        if (preg_match('/property\s+\S+::\$(\w+)\s+of\s+type\s+(\w+)/', $message, $matches)) {
            $field = $matches[1] ?? 'unknown';
            $type = $matches[2] ?? 'unknown';

            return [
                $field => "The '{$field}' field is required and must be of type {$type}.",
            ];
        }

        return [
            'error' => 'Invalid DTO data structure.',
        ];
    }
}
