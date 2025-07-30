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
        Schema::table('tribunaux', function (Blueprint $table) {
            $table->string('centres_de_conservation')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tribunaux', function (Blueprint $table) {
            $table->dropColumn('centres_de_conservation');
        });
    }
};
