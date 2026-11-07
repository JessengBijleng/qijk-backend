<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kitchen_analyses', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('photo_disk');
            $table->string('photo_path');
            $table->string('photo_mime_type');
            $table->unsignedSmallInteger('cabinet_count');
            $table->unsignedSmallInteger('drawer_count');
            $table->decimal('estimated_wrappable_surface_m2', 6, 2);
            $table->text('description');
            $table->string('provider')->nullable();
            $table->string('model')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kitchen_analyses');
    }
};
