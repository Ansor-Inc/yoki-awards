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
        Schema::create('groups', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->foreignId('group_category_id')->index()->nullable()->constrained()->nullOnDelete()->restrictOnUpdate();
            $table->foreignId('owner_id')->nullable()->constrained('users')->nullOnDelete()->restrictOnUpdate();
            $table->integer('member_limit')->nullable();
            $table->string('degree')->nullable();
            $table->boolean('is_private')->default(false);
            $table->string('invite_link')->nullable();
            $table->string('status')->index()->default('PENDING_APPROVAL');
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
        Schema::dropIfExists('groups');
    }
};
