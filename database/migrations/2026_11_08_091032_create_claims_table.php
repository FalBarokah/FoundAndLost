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
        Schema::create('claims', function (Blueprint $table) {
            $table->id('claim_id');
            $table->string('claim_code', 30)->unique();
            $table->foreignId('report_id')->constrained('reports', 'report_id')->cascadeOnDelete();
            $table->string('claimant_name', 100);
            $table->string('claimant_phone', 20);
            $table->text('proof_description');
            $table->string('proof_photo_url', 255)->nullable();
            $table->enum('status', ['menunggu', 'ditindaklanjuti', 'dibatalkan'])->default('menunggu');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('claims');
    }
};
