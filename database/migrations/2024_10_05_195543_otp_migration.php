<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {

        Schema::create('otps', function (Blueprint $table) {
            $table->id();
            $table->string('phone');
            $table->string('code');
            $table->enum('purpose', ['login', 'register', 'forgot-password', 'authorize'])->default('register');
            $table->dateTime('created_at')->useCurrent();
            $table->string('token')->nullable();
            $table->dateTime('verified_at')->nullable();
            $table->dateTime('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->softDeletesDatetime();
        });

        Schema::create('otp_rate_limits', function (Blueprint $table) {
            $table->id();
            $table->string('ip_address');
            $table->string('phone_number');
            $table->dateTime('expires_at');
            $table->dateTime('created_at')->useCurrent();
            $table->dateTime('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

        Schema::dropIfExists('otps');
        Schema::dropIfExists('otp_rate_limits');
    }
};
