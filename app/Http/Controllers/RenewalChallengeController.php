<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Services\RenewalAccessService;
use App\Services\RenewalOtpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class RenewalChallengeController extends Controller
{
    public const SESSION_KEY = 'renewal_otp_challenge';

    /**
     * Entry from email link: validate opaque token, then challenge with OTP.
     */
    public function start(Request $request, string $cart_ref, RenewalAccessService $access, RenewalOtpService $otps)
    {
        $token = (string) $request->query('token', '');
        $cart = Cart::with(['user', 'product'])->where('cart_ref', $cart_ref)->first();
        if (!$cart) {
            abort(404);
        }

        $user = $access->validate($cart, $token);
        if (!$user) {
            Log::notice('renewal.access.invalid', [
                'cart_ref' => $cart_ref,
                'ip' => $request->ip(),
            ]);

            return redirect()
                ->route('login')
                ->with('status', 'Il link di rinnovo non è valido o è scaduto. Accedi con le tue credenziali oppure usa “Password dimenticata”.');
        }

        if (Auth::check() && (int) Auth::id() === (int) $user->id) {
            return redirect()->route('front.product.renew', [
                'product' => $cart->product_id,
                'cart' => $cart->cart_ref,
            ]);
        }

        $request->session()->put(self::SESSION_KEY, [
            'cart_ref' => $cart->cart_ref,
            'user_id' => $user->id,
            'link_verified_at' => now()->toIso8601String(),
        ]);

        $issued = $otps->issue($cart, $user);
        if (!$issued['ok'] && $issued['reason'] === 'throttle') {
            return redirect()
                ->route('front.renewal.challenge')
                ->with('status', 'Ti abbiamo già inviato un codice. Attendi qualche secondo prima di richiederne un altro.');
        }

        return redirect()
            ->route('front.renewal.challenge')
            ->with('status', 'Ti abbiamo inviato un codice via email. Inseriscilo entro ' . $otps->ttlMinutes() . ' minuti.');
    }

    public function show(Request $request)
    {
        $challenge = $request->session()->get(self::SESSION_KEY);
        if (!$challenge) {
            return redirect()
                ->route('login')
                ->with('status', 'Sessione di rinnovo assente o scaduta. Usa di nuovo il link ricevuto via email.');
        }

        $cart = Cart::where('cart_ref', $challenge['cart_ref'])->first();
        if (!$cart) {
            $request->session()->forget(self::SESSION_KEY);
            abort(404);
        }

        return Inertia::render('Auth/RenewalChallenge', [
            'cart_ref' => $cart->cart_ref,
            'email_hint' => $this->maskEmail(optional($cart->user)->email),
            'ttl_minutes' => (int) config('renewal.otp_ttl_minutes', 60),
            'status' => session('status'),
        ]);
    }

    public function verify(Request $request, RenewalOtpService $otps)
    {
        $challenge = $request->session()->get(self::SESSION_KEY);
        if (!$challenge) {
            return redirect()
                ->route('login')
                ->with('status', 'Sessione di rinnovo assente o scaduta. Usa di nuovo il link ricevuto via email.');
        }

        $data = $request->validate([
            'code' => 'required|string|min:6|max:8',
        ]);

        $cart = Cart::with('user')->where('cart_ref', $challenge['cart_ref'])->first();
        if (!$cart || !$cart->user || (int) $cart->user_id !== (int) $challenge['user_id']) {
            $request->session()->forget(self::SESSION_KEY);
            abort(404);
        }

        $result = $otps->verify($cart, $cart->user, $data['code']);
        if (!$result['ok']) {
            $messages = [
                'expired' => 'Il codice è scaduto. Richiedine uno nuovo.',
                'locked' => 'Troppi tentativi. Richiedi un nuovo codice.',
                'mismatch' => 'Codice non corretto.',
                'missing' => 'Nessun codice attivo. Richiedine uno nuovo.',
                'invalid_format' => 'Inserisci solo le cifre del codice.',
            ];

            return back()->withErrors([
                'code' => $messages[$result['reason']] ?? 'Codice non valido.',
            ]);
        }

        Auth::login($cart->user, false);
        $request->session()->regenerate();
        $request->session()->forget(self::SESSION_KEY);

        return redirect()->route('front.product.renew', [
            'product' => $cart->product_id,
            'cart' => $cart->cart_ref,
        ]);
    }

    public function resend(Request $request, RenewalOtpService $otps)
    {
        $challenge = $request->session()->get(self::SESSION_KEY);
        if (!$challenge) {
            return redirect()->route('login');
        }

        $cart = Cart::with('user')->where('cart_ref', $challenge['cart_ref'])->first();
        if (!$cart || !$cart->user) {
            abort(404);
        }

        $issued = $otps->issue($cart, $cart->user);
        if (!$issued['ok']) {
            return back()->with('status', 'Attendi prima di richiedere un nuovo codice.');
        }

        return back()->with('status', 'Nuovo codice inviato via email.');
    }

    private function maskEmail(?string $email): string
    {
        if (!$email || strpos($email, '@') === false) {
            return '';
        }
        [$name, $domain] = explode('@', $email, 2);
        $visible = substr($name, 0, 1);

        return $visible . str_repeat('*', max(1, strlen($name) - 1)) . '@' . $domain;
    }
}
