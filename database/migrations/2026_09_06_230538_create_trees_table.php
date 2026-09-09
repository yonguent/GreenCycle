<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('trees', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('name');
            $table->integer('level')->default(1);
            $table->integer('health')->default(100);
            $table->integer('progress')->default(0);
            $table->string('status')->default('ACTIVE');
            $table->datetime('planted_at')->useCurrent();
            $table->datetime('last_cared_at')->nullable();
            $table->datetime('next_care_at')->nullable();
            $table->datetime('last_decay_at')->nullable();
            $table->datetime('harvested_at')->nullable();
            $table->integer('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trees');
    }
};
