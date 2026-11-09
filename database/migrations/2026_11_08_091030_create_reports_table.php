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
        Schema::create('reports', function (Blueprint $table) {
            $table->id('report_id');
            $table->string('code', 20)->unique();
            $table->string('edit_code', 20)->unique();
            $table->enum('type', ['hilang', 'ditemukan']);
            $table->string('item_name', 150);
            $table->text('description');
            $table->text('special_features')->nullable();
            $table->string('location', 255);
            $table->date('incident_date');
            $table->string('photo_url', 255)->nullable();
            $table->string('reporter_name', 100);
            $table->string('reporter_phone', 20);
            $table->enum('status', ['proses_pencarian', 'siap_diambil', 'selesai'])->default('proses_pencarian');
            $table->boolean('is_published')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
