<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('title')->index();
            $table->text('description')->nullable();
            $table->string('language')->nullable();
            $table->integer('page_count')->nullable();
            $table->date('publication_date')->nullable();

            $table->float('price')->nullable();
            $table->float('compare_price')->nullable();
            $table->boolean('is_free')->default(false);
            $table->string('status')->index()->default('PENDING_APPROVAL');
            $table->string('book_type')->index();

            $table->dateTime('rejected_at')->nullable();
            $table->text('reject_reason')->nullable();

            $table->unsignedBigInteger('publisher_id')->nullable()->index();
            $table->unsignedBigInteger('genre_id')->nullable()->index();
            $table->unsignedBigInteger('author_id')->nullable()->index();
            $table->timestamps();

            $table->foreign('publisher_id')
                ->references('id')
                ->on('publishers')
                ->nullOnDelete();

            $table->foreign('genre_id')
                ->references('id')
                ->on('genres')
                ->nullOnDelete();

            $table->foreign('author_id')
                ->references('id')
                ->on('authors')
                ->nullOnDelete();
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
