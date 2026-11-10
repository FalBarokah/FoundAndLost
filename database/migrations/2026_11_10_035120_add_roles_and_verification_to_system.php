<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('admins', function (Blueprint $table) {
            $table->string('role', 20)->default('satpam')->after('name');
        });

        Schema::table('handovers', function (Blueprint $table) {
            $table->string('status', 30)->default('menunggu_verifikasi')->after('notes');
            $table->foreignId('verified_by')->nullable()->after('status')->constrained('admins', 'admin_id')->nullOnDelete();
            $table->dateTime('verified_at')->nullable()->after('verified_by');
            $table->text('rejection_reason')->nullable()->after('verified_at');
        });

        // Update reports status column definition or alter to include menunggu_verifikasi in MySQL
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE reports MODIFY COLUMN status ENUM('proses_pencarian', 'siap_diambil', 'menunggu_verifikasi', 'selesai') DEFAULT 'proses_pencarian'");
        }

        // Seed / set roles on existing admin records
        DB::table('admins')->where('email', 'admin@gmail.com')->update([
            'role' => 'super_admin',
            'name' => 'Super Admin',
        ]);

        DB::table('admins')->where('email', 'satpam@gmail.com')->update([
            'role' => 'satpam',
            'name' => 'Satpam Pos 1',
        ]);

        // Existing handovers before this feature were already considered completed
        DB::table('handovers')->update([
            'status' => 'disetujui',
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('handovers', function (Blueprint $table) {
            $table->dropForeign(['verified_by']);
            $table->dropColumn(['status', 'verified_by', 'verified_at', 'rejection_reason']);
        });

        Schema::table('admins', function (Blueprint $table) {
            $table->dropColumn('role');
        });

        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE reports MODIFY COLUMN status ENUM('proses_pencarian', 'siap_diambil', 'selesai') DEFAULT 'proses_pencarian'");
        }
    }
};
