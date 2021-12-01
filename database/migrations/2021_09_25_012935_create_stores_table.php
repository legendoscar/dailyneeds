<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoresTable extends Migration
{
    /**
     * Run the migrations.
     * 
     * @return void
     */
    public function up()
    {
        Schema::create('locations', function (Blueprint $table) { #getting literal locations like, Works, Amakohia
            $table->id();
            // $table->unsignedBigInteger('user_id');
            $table->string('name');
            $table->string('desc');
            $table->string('location_country_name');
            $table->string('location_country_code');

            $table->boolean('is_popular')->default(false);
            $table->boolean('is_recommended')->default(false);
            $table->boolean('is_active')->default(true);

            $table->timestamps();
            $table->rememberToken();
            $table->softDeletes();

        });


        Schema::create('stores', function (Blueprint $table) { #stores include pharmacy & losgistics
            $table->id();
            $table->string('store_name')->unique();
            $table->unsignedBigInteger('store_cat_id');
            $table->unsignedBigInteger('store_location_id')->nullable();
            $table->string('store_address')->nullable();
            $table->string('store_phone')->unique();
            $table->string('store_email')->unique();
            $table->string('store_image')->nullable();
            $table->string('store_about')->nullable(); 
            $table->string('password');
            $table->string('CAC_document')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamp('phone_verified_at')->nullable();
            $table->enum('verification_status', [0,1])->default(0); # 0=>unverified   1=>verified
            $table->boolean('status')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('store_cat_id')->references('id')->on('categories');
            $table->foreign('store_location_id')->references('id')->on('locations');
        });


        Schema::create('store_payment_accounts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('store_id');
            $table->string('account_name');
            $table->integer('account_number');
            $table->string('account_bank');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('store_id')->references('id')->on('stores');
        });


        Schema::create('store_wallet', function (Blueprint $table) { #total their earning at every point in time
            $table->id();
            $table->unsignedBigInteger('store_id');
            $table->float('amount');
            $table->boolean('is_requested')->default(false);
            $table->boolean('is_processed')->default(false);

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('store_id')->references('id')->on('stores');

        });
       
       
        Schema::create('store_payouts', function (Blueprint $table) { #keeps track of their earnings
            $table->id();
            $table->unsignedBigInteger('store_id');
            $table->float('amount');
            $table->boolean('is_requested')->default(false);
            $table->boolean('is_processed')->default(false);

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('store_id')->references('id')->on('stores');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('locations');
        Schema::dropIfExists('stores');
        Schema::dropIfExists('store_payment_accounts');
    }
}
