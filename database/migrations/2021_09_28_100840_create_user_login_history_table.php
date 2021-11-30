<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserLoginHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activity_type', function (Blueprint $table) {
            $table->id();
            $table->text('activity_title')->nullable();
            $table->text('activity_desc')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });


        Schema::create('user_activity_log', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('activity_type_id');
            $table->text('log_title')->nullable();
            $table->text('log_data')->nullable();            

            $table->ipAddress('ip_address')->nullable();
            $table->text('browser_info')->nullable();
            $table->text('login_location')->nullable();
            $table->dateTime('login_timestamp');
            $table->dateTime('logout_timestamp')->nullable();
            $table->string('sess_duration')->nullable();
            $table->timestamps();
            $table->softDeletes();
 
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('activity_type_id')->references('id')->on('activity_type');
        });


        
        Schema::create('store_activity_log', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('store_id');
            $table->unsignedBigInteger('activity_type_id');
            $table->text('log_title')->nullable();
            $table->text('log_data')->nullable();            

            $table->ipAddress('ip_address')->nullable();
            $table->text('browser_info')->nullable();
            $table->text('login_location')->nullable();
            $table->dateTime('login_timestamp');
            $table->dateTime('logout_timestamp')->nullable();
            $table->string('sess_duration')->nullable();
            $table->timestamps();
            $table->softDeletes();
 
            $table->foreign('store_id')->references('id')->on('stores');
            $table->foreign('activity_type_id')->references('id')->on('activity_type');
        });

        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('activity_type');
        Schema::dropIfExists('user_login_history');
        Schema::dropIfExists('store_login_history');
    }
}
