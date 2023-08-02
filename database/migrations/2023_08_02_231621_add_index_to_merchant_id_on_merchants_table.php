<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {

        Schema::table('merchants', function (Blueprint $table) {
            $table->index('id'); // Index for Merchant ID
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

        Schema::table('merchants', function (Blueprint $table) {
            $table->dropIndex(['id']); // Drop index for Merchant ID
        });

    }
};
