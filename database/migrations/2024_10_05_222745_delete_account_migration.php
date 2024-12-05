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
        Schema::table('users', function (Blueprint $table) {
            $table->softDeletesDatetime();
            $table->longText('deletion_reason')->nullable();
        });

        Schema::table('otps', function (Blueprint $table) {
            $table->enum('purpose', ['login', 'register', 'forgot-password', 'delete-account', 'authorize'])->default('register')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropSoftDeletes();
            $table->dropColumn('deletion_reason');
        });

        Schema::table('otps', function (Blueprint $table) {
            $table->enum('purpose', ['login', 'register', 'forgot-password', 'authorize'])->default('register')->change();
        });
    }
};
