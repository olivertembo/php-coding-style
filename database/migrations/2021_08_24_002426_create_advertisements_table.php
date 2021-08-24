<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdvertisementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('advertisements', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string(column: 'title')->nullable();
            $table->text(column: 'description')->nullable();
            $table->string(column: 'photo_1')->nullable();
            $table->string(column: 'photo_2')->nullable();
            $table->string(column: 'photo_3')->nullable();
            $table->foreignId(column: 'user_id')->constrained(table: 'users');
            $table->uuid(column: 'uuid')->nullable();
            $table->string(column: 'visible')->default('Y');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('advertisements');
    }
}
