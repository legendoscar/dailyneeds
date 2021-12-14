<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('location_fare', function (Blueprint $table) { #fare from one point to another
            $table->id();
            $table->unsignedBigInteger('from');
            $table->unsignedBigInteger('to'); 
            $table->float('fare');
            $table->float('old_fare')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('from')->references('store_location_id')->on('stores');
            $table->foreign('to')->references('user_location_id')->on('users');
        });


        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('store_id');
            $table->unsignedBigInteger('driver_id')->nullable();

            $table->string('order_code')->unique();            
            $table->longText('kitchen_instructions')->nullable();
            
            $table->unsignedBigInteger('location');
            $table->unsignedBigInteger('destination_address');

            $table->float('tax_charge', 8, 2)->unsigned()->nullable();
            $table->float('store_charge', 8, 2)->unsigned()->nullable();
            $table->float('delivery_charge', 8, 2)->unsigned()->nullable();
            $table->float('total_amount', 8, 2)->unsigned();

            $table->enum('delivery_mode', ['pick-up', 'delivery'])->default('delivery');
            $table->enum('payment_mode', ['cash', 'transfer', 'card', 'pos'])->default('card');
            $table->enum('payment_status', ['processing', 'confirmed', 'declined', 'cancelled', 'returned'])->default('processing');
            $table->float('cash_change_amount', 8, 2)->unsigned()->nullable();

            $table->dateTime('time_order_accepted')->nullable();
            $table->dateTime('time_order_assigned')->nullable();
            $table->text('store_schedule_order_reason')->nullable(); #reasons to schedule order
            $table->dateTime('store_schedule_order_time')->nullable(); #expected delivery time
            $table->text('store_cancel_reason')->nullable(); #reason to cancel order
            $table->dateTime('store_decline_cancel_time')->nullable(); #time order was cancelled


            $table->dateTime('time_driver_accepted_delivery')->nullable();
            $table->text('user_schedule_order_reason')->nullable(); #reasons to schedule
            $table->dateTime('user_schedule_order_time')->nullable(); #for future delivery
            $table->text('user_decline_cancel_reason')->nullable(); #reason to cancel order
            $table->dateTime('user_decline_cancel_time')->nullable(); #time order was cancelled 
            
            $table->dateTime('time_order_processing')->nullable();
            $table->dateTime('time_order_in_transit')->nullable();
            $table->dateTime('time_order_delivered')->nullable();
            $table->boolean('is_complete')->nullable()->default(false);
            
            $table->boolean('is_repeat')->default(false);
            $table->integer('repeat_count')->default(0);

            $table->rememberToken(); 
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('store_id')->references('id')->on('stores');
            $table->foreign('driver_id')->references('id')->on('driver_details');
            $table->foreign('destination_address')->references('id')->on('user_address');
            $table->foreign('location')->references('id')->on('locations');
        });



        Schema::create('order_items', function(Blueprint $table){
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('product_id');
            $table->unsignedInteger('quantity')->default(1);
            $table->float('amount', 8, 2)->unsigned();

            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('product_id')->references('id')->on('products');
            $table->foreign('order_id')->references('id')->on('orders');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('location_fare');
    }
}
