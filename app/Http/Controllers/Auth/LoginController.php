<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class LoginController extends Controller
{
    private const MAX_ATTEMPTS  = 5;
    private const LOCKOUT_MINS  = 15;

    public function showForm(): View
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email'    => 'required|email|max:254',
            'password' => 'required|string|max:72',
        ]);

        $email = $credentials['email'];

        // ── 1. Verifica bloqueio por email ───────────────────────────────────
        $record = DB::table('login_attempts')->where('email', $email)->first();

        if ($record && $record->locked_until && now()->lt($record->locked_until)) {
            $minutesLeft = (int) ceil(now()->diffInSeconds($record->locked_until) / 60);

            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => "Conta bloqueada temporariamente. Tente novamente em {$minutesLeft} min."]);
        }

        // ── 2. Tenta autenticar ──────────────────────────────────────────────
        if (!Auth::attempt($credentials)) {
            $this->recordFailure($email, $record);

            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'Credenciais inválidas.']);
        }

        // ── 3. Sucesso: limpa tentativas, regenera sessão ────────────────────
        DB::table('login_attempts')->where('email', $email)->delete();

        $request->session()->regenerate();
        Auth::logoutOtherDevices($credentials['password']);

        return redirect()->intended('/');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    // ── Helpers ──────────────────────────────────────────────────────────────

    private function recordFailure(string $email, ?object $record): void
    {
        $attempts = ($record->attempts ?? 0) + 1;
        $lockedUntil = $attempts >= self::MAX_ATTEMPTS
            ? now()->addMinutes(self::LOCKOUT_MINS)
            : null;

        DB::table('login_attempts')->updateOrInsert(
            ['email' => $email],
            [
                'attempts'        => $attempts,
                'locked_until'    => $lockedUntil,
                'last_attempt_at' => now(),
            ]
        );
    }
}
