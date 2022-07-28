<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('author');
            $table->string('title');
            $table->integer('oldprice');
            $table->integer('newprice');
            $table->string('description');
            $table->string('language');
            $table->integer('level')->nullable();
            $table->integer('enrolled_students')->nullable();
            $table->integer('pages')->nullable();
            $table->string('genre')->nullable();
            $table->date('published_date')->nullable();
            $table->integer('pages')->nullable();
            $table->string('sound_director')->nullable();
            $table->string('tags')->nullable();
            $table->string('fragment_url')->nullable();

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
        Schema::dropIfExists('books');
    }
};
