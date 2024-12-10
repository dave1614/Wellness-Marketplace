<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\City;
use App\Models\State;
use App\Models\User;
use App\Notifications\MerchantRegistered;
use App\Rules\CityValidationRule;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RegisteredUserController extends Controller
{

    public function processMerchantRegistration(Request $request, $seeder = false): RedirectResponse
    {
        $response_arr = ['success' => false];

        if($request->has('step')){

            $date = date("j M Y");
            $time = date("h:i:sa");

            $rules = [];

            
            $rules['user_name'] = $request->step == 1 || $request->step == 2 || $request->step == 3 ? ['required', 'alpha_dash', 'max:60', 'unique:' . User::class] : [];
            $rules['email'] = $request->step == 1 || $request->step == 2 || $request->step == 3 ? 'required|string|email|max:255|unique:users,email' : '';
            $rules['name'] = $request->step == 1 || $request->step == 2 || $request->step == 3 ? 'required|string|max:255' : '';
            $rules['phone'] = $request->step == 1 || $request->step == 2 || $request->step == 3 ? 'required|numeric|min_digits:7|max_digits:15|unique:users,phone' : '';

            $rules['business_name'] = $request->step == 2 || $request->step == 3 ? 'required|string|max:255' : '';
            $rules['business_email'] = $request->step == 2 || $request->step == 3 ? 'required|string|email|max:255|unique:businesses,email' : '';
            $rules['business_phone'] = $request->step == 2 || $request->step == 3 ? 'required|numeric|min_digits:7|max_digits:15|unique:businesses,phone' : '';
            $rules['state'] = $request->step == 2 || $request->step == 3 ? 'required|numeric|exists:states,id' : '';
            $rules['city'] = $request->step == 2 || $request->step == 3 ? ['required', 'numeric', new CityValidationRule($request->state)] : [];


            $rules['password'] = $request->step == 3 ? ['required', 'confirmed', Rules\Password::defaults()] : [];

            // return $rules;
            
           
            $request->validate($rules);


            if($request->step == 3){
                $user = User::create([
                    'name' => $request->name,
                    'user_name' => $request->user_name,
                    'email' => $request->email,
                    'role_id' => 2,
                    'created_date' => $date,
                    'created_time' => $time,
                    'country_id' => 151,
                    'phone' => $request->phone,
                    'password' => Hash::make($request->password),
                    'remember_token' => Str::random(10),
                ]);

                $business = Business::create([
                    'merchant_id' => $user->id,
                    'name' => $request->business_name,                    
                    'email' => $request->business_email,
                    'country_id' => 151,
                    'state_id' => $request->state,
                    'city_id' => $request->city,
                    'phone' => $request->business_phone,
                ]);

                $now = DB::raw('NOW()');
                $user->last_activity = $now;
                $user->save();


                if(!$seeder){
                    event(new Registered($user));
                }
                
                // $user->notify(new MerchantRegistered($user));

                Auth::login($user);

                return redirect(route('admin_or_merchant.dashboard'));
            }
            $response_arr['success'] = true;
            
        }


        return back()->with('data', $response_arr);
    }

    public function merchantRegisterPage(): Response
    {
        $props['year'] = date('Y');
        $props['props_states'] = State::where('id', '!=', '0')->orderBy("name", "ASC")->get();
        $props['state'] = 1;
        $props['props_cities'] = City::where('state_id', $props['state'])->orderBy("name", "ASC")->get();
        $props['city'] = 1;

        
        return Inertia::render('Auth/MerchantRegister', $props);
    }


    public function processShopperRegistration(Request $request, $seeder = false): RedirectResponse
    {
        $response_arr = ['success' => false, 'terms_unaccepted' => true];

        $date = date("j M Y");
        $time = date("h:i:sa");


        // if(isset($request->terms) && $request->terms){
        $response_arr['terms_unaccepted'] = false;
        $rules = [

            'user_name' => ['required', 'alpha_dash', 'max:60', 'unique:' . User::class],
            'email' => 'required|string|email|max:255|unique:users,email',
            // 'country' => ['required', new CountryRule],
            // 'region' => ['required', new RegionRule($request->country)],
            'name' => 'required|string|max:255',
            'phone' => 'required|numeric|min_digits:7|max_digits:15|unique:users,phone',
            // 'phone' => ['required', 'numeric', 'min_digits:7', 'max_digits:15', new PhoneRegistrRule($request->country)],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ];

        $messages = [
            // 'sponsor_user_name.exists' => 'This user does not exist.',
        ];

        $request->validate($rules, $messages);

        // $sponsor = User::where('country_id', $request->sponsor_country)->where('phone', $request->sponsor_phone_number)->first();

        $user = User::create([
            'name' => $request->name,
            'user_name' => $request->user_name,
            'email' => $request->email,
            'role_id' => 3,
            'created_date' => $date,
            'created_time' => $time,
            'country_id' => 151,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'remember_token' => Str::random(10),
        ]);


        $user_id = $user->id;

        $now = DB::raw('NOW()');
        $user->last_activity = $now;
        $user->save();


        if(!$seeder){
            event(new Registered($user));
        }

        Auth::login($user);

        return redirect(route('home_page'));


        // }

        return back()->with('data', $response_arr);
    }

    /**
     * Display the registration view.
     */

    public function shopperRegisterPage(): Response
    {
        return Inertia::render('Auth/ShopperRegister');
    }

    public function create(): Response
    {
        return Inertia::render('Auth/Register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:'.User::class,
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('admin_or_merchant.dashboard', absolute: false));
    }
}
