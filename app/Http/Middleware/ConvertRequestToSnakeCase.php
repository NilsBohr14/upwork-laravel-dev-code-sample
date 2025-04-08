<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

final class ConvertRequestToSnakeCase
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->isJson()) {
            $request->replace($this->convertToSnakeCase($request->all()));
        }

        return $next($request);
    }

    /**
     * Recursively convert array keys to snake_case.
     */
    private function convertToSnakeCase(array $array): array
    {
        return array_combine(
            array_map([Str::class, 'snake'], array_keys($array)),
            array_map(function ($value) {
                return is_array($value) ? $this->convertToSnakeCase($value) : $value;
            }, array_values($array))
        );
    }
}
