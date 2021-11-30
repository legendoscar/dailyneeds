<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cust_messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sender_id');
            $table->string('msg_title');
            $table->text('msg_body');
            $table->enum('msg_type', ['order', 'billings']);
            $table->enum('msg_priority', [1,2,3,4,5])->default(3);
            $table->enum('msg_status', ['open', 'replied', 'closed'])->default('open');
            $table->dateTime('msg_status_closed');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('sender_id')->references('id')->on('users');
        });


        Schema::create('sms_otp', function (Blueprint $table) {
            $table->id();
            $table->string('otp');
            $table->string('phone');
            $table->boolean('is_used')->default(false);

            $table->timestamps();
            $table->softDeletes();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cust_messages');
        Schema::dropIfExists('sms_otp');
    }
}
