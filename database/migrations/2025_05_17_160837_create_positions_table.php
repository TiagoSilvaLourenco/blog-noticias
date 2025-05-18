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
        Schema::create('positions', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique()->comment('Ex: home_top, home_footer, post_middle');
            $table->string('label')->comment('Ex: Top (Home), Footer (Post)');
            $table->unsignedInteger('width')->nullable()->comment('Largura recomendada, em pixels');
            $table->unsignedInteger('height')->nullable()->comment('Altura recomendada, em pixels');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('positions');
    }
};
