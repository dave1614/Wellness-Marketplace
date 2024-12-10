<?php
namespace App\Functions;

use App\Http\Controllers\Auth\RegisteredUserController;
use App\Models\City;
use App\Models\User;
use Faker\Factory as Faker;
use \Datetime;
use Illuminate\Support\Str;

class UsefulFunctions {



  public function createDummyMerchantsAccounts($num)
  {
    $faker = Faker::create();
    $arr = [];
    for ($i = 0; $i <= $num; $i++) {
      $name = $faker->name();
      $business_name = $faker->company();
      $business_name = str_replace('-', ' ', $business_name);

      // $arr[] = $name;
      // continue;

      $user_name = str_ireplace(array('\'', '"', ',', ';', '<', '>', '.'), '', strtolower(str_replace(' ', '', $name)), $user_name);
      $email =  $user_name . '@gmail.com';

      $business_user_name = str_ireplace(array('\'', '"', ',', ';', '<', '>', '.'), '', strtolower(str_replace(' ', '', $business_name)), $business_user_name);
      $business_email =  $business_user_name . '@gmail.com';

      $phone = '08' . $faker->numerify('########');

      $state = $faker->numberBetween(1, 35);
      $city = City::where('state_id', $state)->inRandomOrder()->first()->id;


      $request = new \Illuminate\Http\Request();
      $request->setMethod('POST'); //default METHOD

      $request->merge([
        'name' => $name,
        'user_name' => $user_name,
        'email' => $email,
        'phone' => $phone,
        'password' => 'Dave1614..',
        'password_confirmation' => 'Dave1614..',
        'step' => 3,
        'business_name' => $business_name,
        'business_email' => $business_email,
        'business_phone' => '08' . $faker->numerify('########'),
        'state' => $state,
        'city' => $city,
      
      ]);

     
      $registeredUserController = new RegisteredUserController();
      $registeredUserController->processMerchantRegistration($request, true);
    }

    // return $arr;
  }

  public function populateDbWithUsersAndBusinesses(){
    $this->createDummyShoppersAccounts(50);
    $this->createDummyMerchantsAccounts(50);
  }

  public function createDummyShoppersAccounts($num){
    $faker = Faker::create();
    $arr = [];
    for($i = 0; $i <= $num; $i++){
      $name = $faker->name();

      // $arr[] = $name;
      // continue;
      
      $user_name = str_ireplace(array('\'', '"', ',', ';', '<', '>', '.'), '', strtolower(str_replace(' ', '', $name)), $user_name);
      $email =  $user_name . '@gmail.com';

      $phone = '08' . $faker->numerify('########');
     
      $request = new \Illuminate\Http\Request();
      $request->setMethod('POST'); //default METHOD

      $request->merge([
        'name' => $name,
        'user_name' => $user_name,
        'email' => $email,
        'phone' => $phone,
        'password' => 'Dave1614..',
        'password_confirmation' => 'Dave1614..',
      ]);
      

      $registeredUserController = new RegisteredUserController();
      $registeredUserController->processShopperRegistration($request, true);
    }

    // return $arr;
  }

  public function getSocialMediaTime($post_date, $post_time)
  {
    $social_formated_time = "";
    if ($post_date !== "" && $post_time !== "") {
      $post_date = strtotime($post_date);
      $post_date = date("j M Y", $post_date);
      $post_time = strtotime($post_time);
      $post_time = date("H:i:s", $post_time);

      $post_date1 = $post_date;
      $post_time1 = $post_time;

      $curr_date = date("j M Y");
      $curr_time = date("h:i:sa");
      $curr_date = date("j M Y", strtotime($curr_date));
      $curr_time = date("H:i:s", strtotime($curr_time));

      $curr_date = $curr_date . " " . $curr_time;
      // echo $curr_date;
      $curr_date = new DateTime($curr_date);
      $post_date = $post_date . " " . $post_time;
      $post_date = new DateTime($post_date);

      $time_diff = $curr_date->getTimestamp() - $post_date->getTimestamp();
      // echo $time_diff;
      if ($time_diff >= 0) {
        //First Check If Time Is Greater Equal
        if ($time_diff == 0) {
          $social_formated_time = "Just Now";
        } else if ($time_diff <= 60) {
          $social_formated_time = $time_diff . " secs ago";
        } else if (($time_diff > 60) && ($time_diff < 3600)) {
          $social_formated_time = floor($time_diff / 60);
          $social_formated_time = $social_formated_time . " mins ago";
        } else if (($time_diff >= 3600) && ($time_diff < 86400)) {
          $social_formated_time = floor($time_diff / 3600);
          if ($social_formated_time == 1) {
            $social_formated_time = $social_formated_time . " hour ago";
          } else {
            $social_formated_time = $social_formated_time . " hours ago";
          }
        } else if (($time_diff >= 86400) && ($time_diff < 2628000)) {
          $social_formated_time = floor($time_diff / 86400);
          if ($social_formated_time == 1) {
            $social_formated_time = $social_formated_time . " day ago";
          } else {
            $social_formated_time = $social_formated_time . " days ago";
          }
        } else if (($time_diff >= 2628000) && (date("Y") == date("Y", strtotime($post_date1)))) {
          $social_formated_time = date("j M", strtotime($post_date1));
        } else if ((date("Y") !== date("Y", strtotime($post_date1)))) {
          $social_formated_time = date("j M Y", strtotime($post_date1));
        }
      }
    }
    return $social_formated_time;
  }

  public function checkIfUserIsMainAdmin($user_id){
    $user = User::find($user_id);
    if(!is_null($user)){

      return $user->role_id == 1 ? true : false;
    }

    return false;
  }

  public function checkIfUserIsMerchant($user_id)
  {
    $user = User::find($user_id);
    if (!is_null($user)) {

      return $user->role_id == 2 ? true : false;
    }

    return false;
  }

  public function checkIfUserIsShopper($user_id)
  {
    $user = User::find($user_id);
    if (!is_null($user)) {

      return $user->role_id == 3 ? true : false;
    }

    return false;
  }
}