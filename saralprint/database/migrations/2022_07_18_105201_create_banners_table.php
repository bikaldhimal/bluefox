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
        // $current = Carbon::now();
        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            $table->string('title')->unique();
            $table->string('cover')->unique();
            $table->enum('status', ['active', 'inactive'])->default('inactive');
            $table->timestamps();
            // Carbon::now()->format('Y-m-d')
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('banners');
    }
};
