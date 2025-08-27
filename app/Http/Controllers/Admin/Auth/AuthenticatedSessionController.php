<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('admin.auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
 // 1. 入力値のバリデーション
 $request->validate([
    'email' => ['required', 'string', 'email'],
    'password' => ['required', 'string'],
]);

// 2. 'admin' ガードを使って認証を試みる
if (! Auth::guard('admin')->attempt($request->only('email', 'password'), $request->boolean('remember'))) {
    // 認証失敗の場合
    throw ValidationException::withMessages([
        'email' => __('auth.failed'), // resources/lang/ja/auth.php に 'failed' が定義されているはず
    ]);
}

// 3. セッションの再生成
$request->session()->regenerate();

// 4. 認証成功後のリダイレクト
return redirect()->intended(RouteServiceProvider::ADMIN_HOME);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('admin')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/admin/login');
    }
}
