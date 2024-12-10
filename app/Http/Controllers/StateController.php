<?php

namespace App\Http\Controllers;

use App\Models\State;
use Illuminate\Http\Request;

class StateController extends Controller
{

    public function loadCities(Request $request, State $state)
    {
        $return_arr = ['city' => 0, 'cities' => []];

        $cities = $state->cities()->orderBy('name', 'ASC')->get();
        $return_arr['cities'] = $cities;
        $return_arr['city'] = $cities[0]->id;

        return $return_arr;
    }

    public function loadCitiesInApp(Request $request, State $state)
    {
        $return_arr = ['cities' => []];

        $ret_cities = [];
        $cities = $state->cities()->orderBy('name', 'ASC')->get();
        

        if($cities->count() > 0){
            for ($i = 0; $i < count($cities); $i++) {
                $ret_cities[] = [
                    'id' => $cities[$i]->id,
                    'label' => $cities[$i]->name,
                ];
            }
        }

        $return_arr['cities'] = $ret_cities;
        

        return $return_arr;
    }


    
    
}
