<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

final class ConvertResponseToCamelCase
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if ($response->headers->get('Content-Type') === 'application/json') {
            $response->setContent(json_encode($this->convertToCamelCase(json_decode($response->getContent(), true))));
        }

        return $response;
    }

    /**
     * Recursively convert array keys to camelCase.
     */
    private function convertToCamelCase(array $array): array
    {
        return array_combine(
            array_map([Str::class, 'camel'], array_keys($array)),
            array_map(function ($value) {
                return is_array($value) ? $this->convertToCamelCase($value) : $value;
            }, array_values($array))
        );
    }
}
