<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('cat_title')->unique();
            $table->string('cat_desc')->nullable();
            $table->enum('cat_type', [1, 2, 3]);   #1=>store  # 2=>product 
            $table->string('cat_image')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });


        
        Schema::create('sub_categories', function (Blueprint $table) {
            $table->id('id');
            $table->unsignedBigInteger('cat_id');
            $table->string('sub_cat_title')->unique();
            $table->string('sub_cat_desc')->nullable();
            $table->string('sub_cat_image')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps(); 
            $table->softDeletes();


            $table->foreign('cat_id')->references('id')->on('categories');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('categories');
        Schema::dropIfExists('sub_categories');
    }
}
