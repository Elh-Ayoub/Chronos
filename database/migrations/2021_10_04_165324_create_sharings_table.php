<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSharingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sharings', function (Blueprint $table) {
            $table->id();
            $table->integer('shared_by');
            $table->enum('target', ['event', 'calendar']);
            $table->enum('accepted', ['yes', 'no']);
            $table->integer('target_id');
            $table->string('shared_to_email');
            $table->enum('shared_to_role', ['guest', 'admin'])->default('guest');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sharings');
    }
}
