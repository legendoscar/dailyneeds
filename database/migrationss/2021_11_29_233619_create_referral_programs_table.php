<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReferralProgramsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('referral_programs', function (Blueprint $table) { #a single referral campaign
            $table->id();
            $table->string('name');
            $table->string('uri');
            $table->integer('lifetime_minutes')->default(7 * 24 * 60);

            $table->timestamps();
            $table->softDeletes();


        });

        Schema::create('referral_links', function (Blueprint $table) { #inks users share to get benefits for referrals
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('referral_program_id');
            $table->string('code', 36)->index();

            $table->softDeletes();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('referral_program_id')->references('id')->on('referral_programs');
            
        });

        Schema::create('referral_relationships', function (Blueprint $table) { #relationship b/w referrer & user using it.
            $table->id();
            $table->unsignedBigInteger('referral_link_id');
            $table->unsignedBigInteger('user_id');

            $table->softDeletes();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('referral_link_id')->references('id')->on('referral_links');
        });


        Schema::table('users', function (Blueprint $table) { #relationship b/w referrer & user using it.
            $table->unsignedBigInteger('referral_program_id')->after('belongs_to_store');

        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('referral_programs');
        Schema::dropIfExists('referral_links');
        Schema::dropIfExists('referral_relationships');
    }
}
