<?php

use App\Models\City;
use App\Models\Country;
use App\Models\State;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('businesses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('merchant_id')->nullable()->constrained(
                table: 'users',

            )->onDelete('cascade');
            $table->string('email')->unique();
            $table->foreignIdFor(Country::class)->constrained()->onDelete('cascade')->nullable();
            $table->foreignIdFor(State::class)->nullable()->constrained()->onDelete('cascade');
            $table->foreignIdFor(City::class)->nullable()->constrained()->onDelete('cascade');
            $table->string('phone_code')->nullable();
            $table->string('phone');
            $table->string('website')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('businesses');
    }
};
