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
    public function up()
    {
        if (!Schema::hasTable('user_parent_reputations')) {
            Schema::create('user_parent_reputations', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id')->unsigned();
                $table->unsignedBigInteger('parent_id')->unsigned();

                $table->foreign('user_id')
                    ->references('id')
                    ->on('users')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');
                $table->foreign('parent_id')
                    ->references('id')
                    ->on('users')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');

                $table->timestamps();
                $table->comment('Works like a log, so entries can doubled');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_parent_reputations');
    }

};
