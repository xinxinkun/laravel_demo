<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('issues', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->foreignId('creator_id')->constrained('users');
            $table->boolean('is_open')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('issues');
    }
};
