<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use App\Services\AuthService\AuthService;
use App\Services\TurboSms\TurboSmsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Illuminate\View\View;

class RegisterController extends Controller
{

    public function create(RegisterRequest $request, AuthService $authService, TurboSmsService $turboSmsService)
    {
        $data = $request->validated();

        if(User::firstWhere('phone', $data['phone'])){
            return redirect()->route('auth.login')
                ->with('error', "Користувач з номером {$data['phone']} вже зареєстрований");
        }

        $code = $authService->registerScenario($data['phone'], $data['name']);
        if (!$code){
            return  redirect()->route('auth.verify')->with('error', "Код уже був відправлений вам на номер {$data['phone']}.");
        }
//        $turboSmsService->sendSms($data['phone'], $code);

        return  redirect()->route('auth.verify');
    }


}
