<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Traits\Auth\RedirectsUsers;
use App\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RegisterController extends Controller
{
    use RedirectsUsers;

    /**
     * Handle a registration request for the application.
     *
     * @param RegisterRequest $request
     * @return Response
     */
    public function register(RegisterRequest $request): Response
    {
        /** @var User $user */
        $user = User::query()->create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
            'password' => bcrypt($request->input('password')),
        ]);

        event(new Registered($user));

        Auth::login($user);

        return $request->wantsJson()
            ? (new UserResource($user))
                ->response()
                ->setStatusCode(201)
            : redirect($this->redirectPath());
    }
}
