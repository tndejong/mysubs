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
        Schema::table('organisations', function (Blueprint $table) {
            $table->string('mollie_access_token')->nullable();
            $table->string('mollie_refresh_token')->nullable();
            $table->timestamp('mollie_token_expires_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('organisations', function (Blueprint $table) {
            $table->dropColumn([
                'mollie_access_token',
                'mollie_refresh_token',
                'mollie_token_expires_at',
            ]);
        });
    }
};
