<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\Rules\Password as PasswordRule;
use Illuminate\View\View;

class PasswordResetController extends Controller
{
    public function showForgotForm(): View
    {
        return view('auth.forgot-password');
    }

    public function sendResetLink(Request $request): RedirectResponse
    {
        $request->validate(['email' => 'required|email|max:254']);

        // Sempre envia a mesma mensagem — anti-enumeração de emails
        Password::sendResetLink($request->only('email'));

        return back()->with(
            'status',
            'Se esse e-mail estiver cadastrado, você receberá um link em breve.'
        );
    }

    public function showResetForm(Request $request, string $token): View
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->query('email', ''),
        ]);
    }

    public function reset(Request $request): RedirectResponse
    {
        $request->validate([
            'token'    => 'required|string',
            'email'    => 'required|email|max:254',
            'password' => ['required', 'confirmed', PasswordRule::min(8)
                ->mixedCase()
                ->numbers()
                ->symbols()
                ->uncompromised(),
            ],
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill(['password' => $password]);
                $user->save();
            }
        );

        if ($status !== Password::PasswordReset) {
            return back()->withErrors(['email' => 'Token inválido ou expirado.']);
        }

        return redirect('/login')->with('status', 'Senha redefinida com sucesso.');
    }
}
