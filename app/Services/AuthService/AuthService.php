<?php

namespace App\Services\AuthService;

use Illuminate\Support\Facades\Redis;

class AuthService
{


    /**
     * @param string $phone
     * @return int|bool
     */
    public function loginScenario(string $phone) : ?int
    {
        if (Redis::get("sms:{$phone}")){
            return false;
        }
        $code = $this->getRandomCode();

        $this->putSession($phone);

        $this->redisCode($phone, $code);

        return $code;
    }

    /**
     * @param $name
     * @param $phone
     * @return int
     */
    public function registerScenario(string $phone, string $name) : int
    {
        if (Redis::get("sms:{$phone}")){
            return false;
        }

        $code = $this->getRandomCode();

        $this->putSession($phone, $name);

        $this->redisCode($phone, $code);

        return $code;

    }

    /**
     * @param string $phone
     * @param int $code
     * @return void
     */
    private function redisCode(string $phone, int $code) : void
    {
        Redis::set("sms:{$phone}", $code);
        Redis::expire("sms:{$phone}", 300);
    }

    /**
     * @param string $name
     * @param string $phone
     * @return void
     */
    private function putSession(string $phone, string $name = null ) :void
    {
        if ($name) session()->put('name', $name);
        session()->put('phone', $phone);
    }

    /**
     * @return int
     */
    private function getRandomCode() : int
    {
        return rand(1111, 9999);
    }

}
