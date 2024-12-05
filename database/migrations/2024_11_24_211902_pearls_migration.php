<?php

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
        Schema::create('shops', function (Blueprint $table) {
            $table->id();
            $table->string('username')->unique();
            $table->string('link')->nullable();
            $table->string('payment_instructions')->nullable();
            $table->string('name');
            $table->unsignedBigInteger('user_id')->index();
            $table->foreign('user_id')->references('id')->on('users');
            $table->string('description')->nullable();
            $table->string('social_media_link')->nullable();
            $table->string('status');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('shop_id')->index();
            $table->foreign('shop_id')->references('id')->on('shops');
            $table->double('price');
            $table->bigInteger('no_of_stocks')->nullable();
            $table->string('description')->nullable();
            $table->boolean('is_featured')->nullable()->default(false);
            $table->string('status')->default('active');
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create('item_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->string('description')->nullable();
            $table->string('status');
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create('item_has_categories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('shop_id')->index();
            $table->foreign('shop_id')->references('id')->on('shops');
            $table->unsignedBigInteger('item_id')->index();
            $table->foreign('item_id')->references('id')->on('items');
            $table->unsignedBigInteger('item_category_id')->index();
            $table->foreign('item_category_id')->references('id')->on('item_categories');
            $table->string('description')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('cart_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->index();
            $table->foreign('user_id')->references('id')->on('users');
            $table->unsignedBigInteger('item_id')->index();
            $table->foreign('item_id')->references('id')->on('items');
            $table->enum('cart_type', ['wishlist', 'cart']);
            $table->integer('quantity')->default(1);
            $table->timestamps();
        });
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_code')->unique();
            $table->unsignedBigInteger('user_id')->index();
            $table->foreign('user_id')->references('id')->on('users');
            $table->unsignedBigInteger('shop_id')->index();
            $table->foreign('shop_id')->references('id')->on('shops');

            $table->string('shipping_method');
            $table->string('shipped_by')->nullable();
            $table->string('shipping_no')->nullable();
            $table->double('shipping_fee');
            $table->string('shipping_status')->nullable();
            $table->string('shipping_address');

            $table->string('billing_customer_name');
            $table->string('billing_customer_email')->nullable();
            $table->string('billing_customer_phone')->nullable();

            $table->string('payment_method');
            $table->string('payment_ref')->nullable();
            $table->string('payment_status')->default('pending');

            $table->double('sub_total');
            $table->double('total');
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id')->index();
            $table->foreign('order_id')->references('id')->on('orders');
            $table->unsignedBigInteger('item_id')->index();
            $table->foreign('item_id')->references('id')->on('items');
            $table->integer('quantity');
            $table->double('price');
            $table->double('amount');
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create('order_shipping_statuses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id')->index();
            $table->foreign('order_id')->references('id')->on('orders');
            $table->string('name');
            $table->string('description')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create('order_payment_statuses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id')->index();
            $table->foreign('order_id')->references('id')->on('orders');
            $table->string('name');
            $table->string('description')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};