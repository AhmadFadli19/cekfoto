<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('catin')->after('name'); // catin, bidan, admin_faskes, admin_sistem, relawan
            $table->string('phone')->nullable()->after('email');
            $table->string('province')->nullable()->after('phone');
            $table->string('city')->nullable()->after('province');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'phone', 'province', 'city']);
        });
    }
};
