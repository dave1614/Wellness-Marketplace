<?php

namespace App\Http\Controllers;

use App\Functions\UsefulFunctions;
use App\Models\State;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TestController extends Controller
{

    public UsefulFunctions $functions;

    public function __construct()
    {
        $this->functions = new UsefulFunctions();
    }

    public function index(Request $request){
        // $cities = State::find(1)->cities()->get();

        // return $cities;

        return $this->functions->createDummyMerchantsAccounts(150);
    }


    public function vueTest(Request $request){
        return Inertia::render("Tests/Index");
    }

    public function packageJson(Request $request){
        return '{"private":true,"type":"module","scripts":{"dev":"vite","build":"vite build"},"devDependencies":{"@inertiajs/vue3":"^1.0.0","@mdi/js":"^7.4.47","@tailwindcss/forms":"^0.5.3","@tailwindcss/line-clamp":"^0.4.4","@vitejs/plugin-vue":"^5.0.0","autoprefixer":"^10.4.12","axios":"^1.6.4","chart.js":"^4.4.2","laravel-vite-plugin":"^1.0","numeral":"^2.0.6","pinia":"^2.1.7","postcss":"^8.4.31","tailwindcss":"^3.2.1","vite":"^5.0","vue":"^3.4.0"},"dependencies":{"@fortawesome/fontawesome-svg-core":"^6.5.2","@fortawesome/free-brands-svg-icons":"^6.5.2","@fortawesome/free-regular-svg-icons":"^6.5.2","@fortawesome/free-solid-svg-icons":"^6.5.2","@fortawesome/vue-fontawesome":"^3.0.8","animate.css":"^4.1.1","bootstrap":"^5.0.1","sweetalert2":"^11.12.1"}}';
    }
}
