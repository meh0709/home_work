<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\VerifyRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class VerifyController extends Controller
{


    public function verifyCode(VerifyRequest $request)
    {

        $data = $request->validated();
        $name = session()->get('name');
        $phone = session()->get('phone');

        $result = $this->validateVerificationCode($data['code'], $phone);

        $user = User::query()->firstWhere('phone', $phone);

        if ($result){
            if (!$user){
                $this->createUser($name, $phone);
                return redirect()->route('cabinet.index');
            }
          $this->loginUser($user);
            return redirect()->route('cabinet.index');
        }

        return redirect()->back()->with('error', 'Не вірний код');
    }

    /**
     * @param string $code
     * @param int $phone
     * @return bool
     */
    private function validateVerificationCode(string $code, int $phone) : bool
    {
        $storeCode = Redis::get("sms:{$phone}");

        return $storeCode && $code == $storeCode;
    }

    private function createUser($name, $phone)
    {
        $user = User::create(
            [
                'name' => $name,
                'phone' => $phone
            ]
        );

        Auth::login($user);
    }

    private function loginUser($user)
    {
        Auth::login($user);

    }
}
