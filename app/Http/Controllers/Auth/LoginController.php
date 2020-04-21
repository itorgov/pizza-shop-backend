<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\UserResource;
use App\Traits\Auth\RedirectsUsers;
use App\Traits\Auth\ThrottlesLogins;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class LoginController extends Controller
{
    use ThrottlesLogins, RedirectsUsers;

    /**
     * Handle a login request to the application.
     *
     * @param LoginRequest $request
     * @return Response
     *
     * @throws ValidationException
     */
    public function login(LoginRequest $request): Response
    {
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            $this->sendLockoutResponse($request);
        }

        $attempt = Auth::attempt($request->only(['email', 'password']), $request->filled('remember'));

        if ($attempt) {
            $request->session()->regenerate();
            $this->clearLoginAttempts($request);
            /** @var User $user */
            $user = Auth::user();

            return $request->wantsJson()
                ? (new UserResource($user))
                    ->response()
                    ->setStatusCode(200)
                : redirect($this->redirectPath());
        }

        $this->incrementLoginAttempts($request);

        throw ValidationException::withMessages([
            'email' => [trans('auth.failed')],
        ]);
    }
}
