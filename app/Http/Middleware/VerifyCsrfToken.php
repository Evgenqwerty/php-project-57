<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;

class VerifyCsrfToken extends BaseVerifier
{
    /**
     * Отключить CSRF для тестового окружения
     */
    public function handle($request, Closure $next)
    {
        // Полностью отключить CSRF для тестового окружения
        if (app()->environment('testing', 'dusk')) {
            return $next($request);
        }

        // Для всех остальных окружений - стандартная проверка CSRF
        return parent::handle($request, $next);
    }
}
