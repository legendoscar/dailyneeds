<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id('id');
            $table->unsignedBigInteger('prod_cat_id');
            $table->unsignedBigInteger('store_id')->nullable();
            $table->string('product_title')->unique();
            $table->string('product_sub_title')->nullable();
            $table->text('product_desc')->nullable();
            $table->enum('unit', ['plate', 'wrap', 'kg', 'liter', 'morsel'])->default('plate');
            $table->string('product_banner_img')->nullable();
            $table->string('product_images')->nullable();
            $table->string('product_code')->nullable();
            $table->float('price', 8, 2)->default('0.00');
            $table->float('old_price', 8, 2)->default('0.00')->nullable();
            $table->boolean('is_available')->nullable()->default(true);
            $table->boolean('is_new')->nullable()->default(true);
            $table->boolean('is_popular')->nullable()->default(false);
            $table->boolean('is_recommended')->nullable()->default(false);
            $table->timestamps();
            $table->softDeletes();


            $table->foreign('prod_cat_id')->references('id')->on('sub_categories');
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
        Schema::dropIfExists('products');
    }
}
