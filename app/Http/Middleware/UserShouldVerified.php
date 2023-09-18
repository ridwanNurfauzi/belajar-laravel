<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class UserShouldVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // return $next($request);
        $response = $next($request);
        if (Auth::check() && !Auth::user()->is_verified) {
            $link = url('auth/send-verification').'?email='.urlencode(Auth::user()->email);
            Auth::logout();
            Session::flash("flash_notification", [
                "level" => "warning",
                "message" => "Silahkan klik pada link aktivasi yang telah kami kirim.<a class='alert-link' href='$link'>Kirim lagi</a>.",
                // "message" => "Akun Anda belum aktif. Silahkan klik pada link aktivasi yang telah kami kirim."
            ]);
            return redirect('/login');
        }
        return $response;
    }
}
