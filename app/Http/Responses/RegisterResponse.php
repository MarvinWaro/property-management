<?php

namespace App\Http\Responses;

use Illuminate\Http\Request;
use Laravel\Fortify\Contracts\RegisterResponse as RegisterResponseContract;

class RegisterResponse implements RegisterResponseContract
{
    /**
     * Create an HTTP response that represents the object.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function toResponse($request)
    {
        // Log out the user so they have to log in after registration.
        auth()->logout();

        // Redirect to the login page with a status message.
        return redirect()->route('login')->with('status', 'Registration successful, but your account is currently inactive. Please contact the admin.');

    }
}
