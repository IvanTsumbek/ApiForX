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
        Schema::table('x_tokens', function (Blueprint $table) {
            $table->string('x_user_id')->nullable()->after('user_id')->comment('ID пользователя в X');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('x_tokens', function (Blueprint $table) {
            $table->dropColumn('x_user_id');
        });
    }
};
