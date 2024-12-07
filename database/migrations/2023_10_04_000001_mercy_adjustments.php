<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        // Should only hit mercy upgraded servers
        if (!Schema::hasColumn('products', 'is_custom')) {
            Schema::table('products', function (Blueprint $table) {

                $table->boolean('is_custom')
                      ->default(true)
                      ->comment('Marked as individual item (in jumble sales or smth)')
                      ->after('is_public');
                $table->boolean('force_public')
                      ->default(false)
                      ->comment('In private stores this can be linked public for everyone')
                      ->after('is_custom');
                $table->timestamp('started_at')->nullable()->comment('null = always available')->after('web_uri');
                $table->timestamp('expired_at')->nullable()->comment('null = open end')->after('started_at');

            });
        }

        if (!Schema::hasColumn('offers', 'expired_at')) {
            Schema::table('offers', function (Blueprint $table) {

                $table->timestamp('expired_at')->nullable()->comment('null = open end')->after('description');

            });
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
    }

};
