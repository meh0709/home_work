<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Cache\RateLimiter;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class VerifyRequestLimit
{
    const TOO_MANY_ATTEMPTS = 'Занадто багато спроб. Спробуйте пізніше';
    const TOO_MANY_ATTEMPTS_VERIFY = 'Занадто багато спроб верифікації. Спробуйте пізніше';

    protected $limiter;

    public function __construct(RateLimiter $limiter)
    {
        $this->limiter = $limiter;
    }


    /**
     * @param Request $request
     * @param Closure $next
     * @param $type // Тип сповіщення
     * @param $maxAttempts // Максимальна кількість спроб до бану
     * @param $decayMinutes // Кількість хвилин бану
     * @return RedirectResponse|mixed
     */
    public function handle(Request $request, Closure $next, $type, $maxAttempts = 10, $decayMinutes = 5)
    {
        $key = $request->ip();

        if ($this->limiter->tooManyAttempts($key, $maxAttempts)) {
            return  redirect()->back()->with('error', $this->getType($type));
        }

        $this->limiter->hit($key, $decayMinutes * 60);

        return $next($request);
    }

    /**
     * @var array|string[]
     */
    private array $typeList = [
          0 => self::TOO_MANY_ATTEMPTS,
          1 =>  self::TOO_MANY_ATTEMPTS_VERIFY
        ];


    /**
     * @param $type
     * @return mixed|string
     */
    private function getType($type) : ?string
    {
        return $this->typeList[$type] ?? 'Спробуйте пізніше';
    }
}
