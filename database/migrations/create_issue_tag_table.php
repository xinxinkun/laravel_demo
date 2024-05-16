<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class  extends Migration
{
    public function up()
    {
        Schema::create('issue_tags', function (Blueprint $table) {
            $table->id();
            $table->foreignId('issue_id')->constrained('issues');
            $table->foreignId('tag_id')->constrained('tags');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('issue_tag');
    }
};
