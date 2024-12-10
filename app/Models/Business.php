<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Business extends Model
{
    use HasFactory;

    protected $fillable = [
        'merchant_id',
        'name',
        'email',
        'country_id',
        'state_id',
        'city_id',
        'phone',
    ];

    public function scopeFilterMerchantUserName($query, $user_name){
        $query->whereHas('merchant', function ($q) use ($user_name){
            $q->where("user_name", "like", "%$user_name%");
        });
    }

    public function scopeFilterMerchantName($query, $name){
        $query->whereHas('merchant', function ($q) use ($name) {
            $q->where('name', 'like', "%$name%");
        });
    }

    public function scopeFilterName($query, $name) {
        $query->when($name, function ($query, $name) {
            $query->where("name", "like", "%$name%");
        });
    }

    public function scopeFilterEmail($query, $email) {
        $query->when($email, function ($query, $email) {
            $query->where("email", "like", "%$email%");
        });
    }

    public function scopeFilterPhone($query, $phone) {
        $query->when($phone, function ($query, $phone) {
            $query->where("phone", "like", "%$phone%");
        });
    }

    public function scopeFilterCity($query, $city) {
        $query->whereHas('city', function ($query) use ($city) {
            $query->where('name', 'like', "%$city%");
        });
    }

    public function scopeFilterState($query, $state) {
        $query->whereHas('state', function ($query) use ($state) {
            $query->where("name", "like", "%$state%");
        });
    }

    public function scopeFilterDate ($query, $date) {
        if(!is_null($date)){
            return $query->whereDate('created_at', date('Y-m-d', strtotime($date)));
        }
    }

    public function scopeFilterBetweenDates($query, $start_date, $end_date) {
        if(!is_null($start_date) && !is_null($end_date)){
            return $query->whereBetween('created_at', [$start_date, $end_date]);
        }
    }

    public function city(): BelongsTo {
        return $this->belongsTo(City::class);
    }

    public function state(): BelongsTo {
        return $this->belongsTo(State::class);
    }

    public function merchant(): BelongsTo{
        return $this->belongsTo(User::class);
    }
}
