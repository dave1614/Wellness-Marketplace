<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Business>
 */
class BusinessFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $business_name = fake()->company();
        $business_name = str_replace('-', ' ', $business_name);
        $business_user_name = str_ireplace(array('\'', '"', ',', ';', '<', '>', '.'), '', strtolower(str_replace(' ', '', $business_name)), $business_user_name);
        $business_email =  $business_user_name . '@gmail.com';
        $phone = '08' . fake()->numerify('########');

        return [
            'merchant_id' => 155,
            'name' => $business_name,
            'email' => $business_email,
            'country_id' => 151,
            'state_id' => 5,
            'city_id' => 95,
            'phone' => $phone,
        ];
    }
}
