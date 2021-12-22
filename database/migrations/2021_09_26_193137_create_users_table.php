<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id(); 
            $table->unsignedBigInteger('user_type')->unique();
            $table->string('role_name')->unique();

            
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->rememberToken();
            $table->softDeletes();
        });


        Schema::create('users', function (Blueprint $table) {
            $table->id(); 
            $table->unsignedBigInteger('user_role');
            $table->unsignedBigInteger('belongs_to_store')->nullable();
            $table->string('fname');
            $table->string('lname');
            $table->string('phone')->unique();
            $table->string('email')->unique();
            $table->string('profile_image')->nullable(); 
            $table->unsignedBigInteger('user_location_id')->nullable();
            $table->string('password'); 
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamp('phone_verified_at')->nullable();
            $table->enum('gender', ['male', 'female'])->nullable(); 
            $table->string('dob')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
            $table->rememberToken();
            $table->softDeletes();

            $table->foreign('user_role')->references('id')->on('roles');
            $table->foreign('belongs_to_store')->references('id')->on('stores');
            $table->foreign('user_location_id')->references('id')->on('locations');
        
        });



        Schema::create('user_address', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('address_title');
            $table->string('address_body');
            
            
            $table->string('address_state');
            $table->string('address_city');
            $table->string('address_zip_code'); 
            $table->string('address_street');
            $table->string('address_latitude');
            $table->string('address_longitude');
            $table->unsignedBigInteger('user_location_id')->nullable();

            $table->boolean('address_primary')->default(false);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('user_location_id')->references('id')->on('locations');
        });


        Schema::create('driver_details', function (Blueprint $table) { #driver details 
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('vehicle_number')->unique();
            $table->string('vehicle_model');
            $table->string('vehicle_color');
            $table->string('vehicle_image')->nullable();
            $table->integer('max_delivery_limit');

            $table->float('commission_rate')->nullable();
            
            $table->timestamps();
            $table->softDeletes();


            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('roles');
        // Schema::dropIfExists('permissions');
        Schema::dropIfExists('users');
        Schema::dropIfExists('user_address');
        Schema::dropIfExists('driver_details');
    }
}
