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
        Schema::create('settlements', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('api_id')->unsigned()->nullable(false);
            $table->bigInteger('district_id')->unsigned()->nullable(false);
            $table->foreign('district_id')->references('id')->on('districts');
            $table->string('name')->nullable(false);
            $table->unsignedSmallInteger('type')->nullable(false);
            $table->decimal('x',15,12);
            $table->decimal('y',15,12);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settlements');
    }
};
