<?php

use App\Models\Member;
use App\Models\Organisation;
use App\Models\User;
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
        Schema::create('prepaid_cards', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Organisation::class);
            $table->string('card_number')->unique();
            $table->foreignIdFor(Member::class);
            $table->integer('balance')->default(12);
            $table->timestamp('activated_at')->nullable();
            $table->timestamp('expired_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prepaid_cards');
    }
};
