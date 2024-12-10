<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;
use App\Rules\CountryRule;
use Illuminate\Support\Facades\Request as Support_Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/MainLogin', [
            'canResetPassword' => Route::has('password.request'),
            'status' => session('status'),
        ]);
    }

    /**
     * Handle an incoming authentication request.
     */
    // public function store(LoginRequest $request): RedirectResponse
    // {
    public function store(Request $request)
    {
        // $request->authenticate();

        // $request->session()->regenerate();


        // $user = Auth::user();
        // return $user->role_id == 3 ? redirect()->intended(route('home_page', absolute: false)) : redirect()->intended(route('dashboard', absolute: false));

        $rules = [
            'login' => ['required'],
            'password' => ['required'],
        ];

        $messages = [
            'login.required' => 'The username or email field is required.',
            'password.required' => 'The password field is required.',
        ];

        //Validate input

        $validator = (!$request->wantsJson()) ? $request->validate($rules, $messages) : Validator::make(
            $request->all(),
            $rules,
            $messages
        );

        if ($request->wantsJson() && $validator->fails()) {
            return response()->json($validator->errors());
        }

        //Check if login is email or username
        $login_type = filter_var($request->input('login'), FILTER_VALIDATE_EMAIL)
            ? 'email'
            : 'user_name';

        // Store user input in user_data variable
        $user_data = array(
            $login_type  => $request->input('login'),
            'password' => $request->input('password')
        );

        //get the remember_me param
        $remember_me  = (!empty($request->remember_me)) ? TRUE : FALSE;


        // Check if username exists
        $user = User::where($login_type, $user_data[$login_type])->first();

        if (!$user) {
            return (!$request->wantsJson()) ? back()->with('error', 'This user does not exist') : response()->json(['error' => 'This user does not exist']);
        }

        // Check if login details are valid. If valid, redirect to admin page
        if (Auth::attempt($user_data)) {
            // $user = User::where([$login_type => $user_data[$login_type]])->first();

            $now = DB::raw('NOW()');
            $user->last_activity = $now;
            $user->save();

            $token = (!$request->wantsJson()) ? Auth::login($user, $remember_me) : $user->createToken('my-app-token')->plainTextToken;

            // return (!$request->wantsJson()) ? redirect()->intended('/dashboard')->with('success', 'Login Successful') : response()->json(['success' => true, 'user' => $user, 'token' => $token], 201);

            return $user->role_id == 3 ? redirect()->intended(route('home_page', absolute: false))->with('success', 'Login Successful') : redirect()->intended(route('admin_or_merchant.dashboard', absolute: false))->with('success', 'Login Successful');

        }

        return (!$request->wantsJson()) ? back()->with('error', 'Invalid login details') : response()->json(['error' => 'Invalid login details']);

    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
