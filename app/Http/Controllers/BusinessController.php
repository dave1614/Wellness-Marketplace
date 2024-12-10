<?php

namespace App\Http\Controllers;

use App\Functions\UsefulFunctions;
use App\Models\Business;
use App\Http\Requests\StoreBusinessRequest;
use App\Http\Requests\UpdateBusinessRequest;
use App\Models\City;
use App\Models\State;
use App\Models\User;
use App\Rules\BusinessEmailEditRule;
use App\Rules\BusinessPhoneEditRule;
use App\Rules\CityValidationInAppRule;
use App\Rules\StateValidationInAppRule;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class BusinessController extends Controller
{

    public UsefulFunctions $functions;

    public function __construct()
    {
        $this->functions = new UsefulFunctions();
    }

    public function loadAllBusinessesForAdmin(Request $request)
    {

        $user = Auth::user();

        $page = $request->query('page', 1);
        $length = $request->query("length", 10);

        $businesses = Business::with('merchant', 'state', 'city')
            ->addSelect('businesses.*')
            ->where('id', '!=', 0)
            ->filterMerchantUserName($request->query('merchant_username'))
            ->filterMerchantName($request->query('merchant_fullname'))
            ->filterName($request->query('name'))
            ->filterEmail($request->query('email'))
            ->filterPhone($request->query('phone'))
            ->filterState($request->query('state'))
            ->filterCity($request->query('city'))
            ->filterDate($request->query('date'))
            ->filterBetweenDates($request->query('start_date'), $request->query('end_date'))
            ->orderBy("id", "DESC")
            ->paginate($length)
            ->withQueryString();

        return $businesses;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $user = User::find($user->id);

        $props['user'] = $user;

        $business = $this->functions->checkIfUserIsMainAdmin($user->id) ? [] : $user->businesses()->orderBy('id', 'DESC');

        // return $props['businesses'];

        if ($this->functions->checkIfUserIsMainAdmin($user->id)) {

            $props['merchant_username'] = $request->merchant_username;
            return Inertia::render('Admin/ManageBusinesses', $props);
        } else {

            $props['search_value'] = $request->has('value') ? $request->value : '';
            $business = $request->has('value') ? $business->where('name', 'like', '%' . $request->value . '%') : $business;
            $business = $business->paginate(10);
            $props['businesses'] = $business;
            return Inertia::render('Merchant/ManageBusinesses', $props);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $user = Auth::user();
        $user = User::find($user->id);

        $props['user'] = $user;
        $props['props_states'] = [];
        $states = State::where('id', '!=', '0')->orderBy("name", "ASC")->get();

        for($i = 0; $i < count($states); $i++){
            $props['props_states'][] = [
                'id' => $states[$i]->id,
                'label' => $states[$i]->name,
            ];
        }

        $props['props_cities'] = [];
        $cities = City::where('state_id', 1)->orderBy("name", "ASC")->get();
        for ($i = 0; $i < count($cities); $i++) {
            $props['props_cities'][] = [
                'id' => $cities[$i]->id,
                'label' => $cities[$i]->name,
            ];
        }


        return Inertia::render('Merchant/BusinessCreate', $props);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $response = ['success' => false];
        $user = Auth::user();
        // return $request->state;

        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:businesses,email',
            'phone' => 'required|numeric|min_digits:7|max_digits:15|unique:businesses,phone',
            'state' => ['required', new StateValidationInAppRule],
            'city' => ['required', new CityValidationInAppRule($request->state)]
        ];

        $request->validate($rules);

        Business::create([
            'merchant_id' => $user->id,
            'name' => $request->name,
            'email' => $request->email,
            'country_id' => 151,
            'state_id' => $request->state['id'],
            'city_id' => $request->city['id'],
            'phone' => $request->phone,
        ]);

        $response['success'] = true;

        return back()->with('data', $response);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Business $business)
    {
        $user = Auth::user();
        $user = User::find($user->id);

        $props['user'] = $user;
        $props['business'] = $business;

        if ($this->functions->checkIfUserIsMainAdmin($user->id)) {
            $props['merchant_details'] = User::find($business->merchant_id);
            return Inertia::render('Admin/BusinessEdit', $props);
        } else {
            return Inertia::render('Merchant/BusinessShow', $props);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, Business $business)
    {
        $user = Auth::user();
        $user = User::find($user->id);

        $props['user'] = $user;
        $props['business'] = $business;
        $props['props_states'] = [];
        $states = State::where('id', '!=', '0')->orderBy("name", "ASC")->get();

        for ($i = 0; $i < count($states); $i++) {

            if($states[$i]->id == $business->state_id){
                $props['state_index'] = $i;
            }

            $props['props_states'][] = [
                'id' => $states[$i]->id,
                'label' => $states[$i]->name,
            ];
        }

        $props['props_cities'] = [];
        $cities = City::where('state_id', $business->state_id)->orderBy("name", "ASC")->get();
        for ($i = 0; $i < count($cities); $i++) {

            if ($cities[$i]->id == $business->city_id) {
                $props['city_index'] = $i;
            }

            $props['props_cities'][] = [
                'id' => $cities[$i]->id,
                'label' => $cities[$i]->name,
            ];
        }

        if($this->functions->checkIfUserIsMainAdmin($user->id)){
            $props['merchant_details'] = User::find($business->merchant_id);
            return Inertia::render('Admin/BusinessEdit', $props);
        }else{
            return Inertia::render('Merchant/BusinessEdit', $props);
        }


    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Business $business)
    {
        $response = ['success' => false];
        $user = Auth::user();
        // return $request->state;

        $rules = [
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:25', new BusinessEmailEditRule($business)],
            'phone' => ['required', 'numeric', 'min_digits:7', 'max_digits:15', new BusinessPhoneEditRule($business)],
            'state' => ['required', new StateValidationInAppRule],
            'city' => ['required', new CityValidationInAppRule($request->state)]
        ];

        $request->validate($rules);

        $business->merchant_id = $user->id;
        $business->name = $request->name;
        $business->email = $request->email;
        $business->state_id = $request->state['id'];
        $business->city_id = $request->city['id'];
        $business->phone = $request->phone;

        $business->save();


        $response['success'] = true;

        return back()->with('data', $response);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Business $business)
    {
        //
    }
}
