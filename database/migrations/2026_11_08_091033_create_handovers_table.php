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
        Schema::create('handovers', function (Blueprint $table) {
            $table->id('handover_id');
            $table->foreignId('report_id')->constrained('reports', 'report_id')->cascadeOnDelete();
            $table->foreignId('claim_id')->nullable()->constrained('claims', 'claim_id')->nullOnDelete();
            $table->foreignId('admin_id')->nullable()->constrained('admins', 'admin_id')->nullOnDelete();
            $table->string('recipient_name', 100);
            $table->string('recipient_contact', 100);
            $table->string('proof_photo_url', 255)->nullable();
            $table->text('notes')->nullable();
            $table->dateTime('handover_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('handovers');
    }
};
