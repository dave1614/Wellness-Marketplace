<?php

namespace App\Http\Controllers;

use App\Functions\UsefulFunctions;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public UsefulFunctions $functions;

    public function __construct()
    {
        $this->functions = new UsefulFunctions();
    }

    public function index(Request $request){
        $user = Auth::user();
        $user = User::find($user->id);

        $props['user'] = $user;

        

        if($this->functions->checkIfUserIsMainAdmin($user->id)){
            $props['merchants_num'] = User::where('role_id', 2)->count();
            $props['merchants_num_today'] = User::where('role_id', 2)->whereDate('created_at', '>=', Carbon::today())->count();

            $props['shoppers_num'] = User::where('role_id', 3)->count();
            $props['shoppers_num_today'] = User::where('role_id', 3)->whereDate('created_at', '>=', Carbon::today())->count();

            return Inertia::render('Admin/Dashboard', $props);
        }else{

            $props['businesses'] = $user->businesses()->get();
            return Inertia::render('Merchant/Dashboard', $props);
        }
    }
}
