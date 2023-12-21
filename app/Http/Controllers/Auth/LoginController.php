<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use App\Services\AuthService\AuthService;
use App\Services\TurboSms\TurboSmsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class LoginController extends Controller
{
    public function loginUser(LoginRequest $request, AuthService $authService, TurboSmsService $turboSmsService)
    {
        $data = $request->validated();

        if(!User::firstWhere('phone', $data['phone'])){
            return redirect()->route('auth.register')
                ->with('error', "Користувач з номером {$data['phone']} не зареєстрований");
        }
        $code = $authService->loginScenario($data['phone']);

        if (!$code){
            return  redirect()->route('auth.verify')->with('error', "Код уже був відправлений вам на номер {$data['phone']}.");
        }

//        $turboSmsService->sendSms($data['phone'], $code);

        return  redirect()->route('auth.verify');


    }

}
