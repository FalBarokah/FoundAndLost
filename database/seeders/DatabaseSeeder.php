<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Claim;
use App\Models\Handover;
use App\Models\Report;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Admin & Satpam
        $superAdmin = Admin::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'super_admin',
        ]);

        $satpam = Admin::create([
            'name' => 'Satpam Pos 1',
            'email' => 'satpam@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'satpam',
        ]);

        // 2. Active Reports (Hilang)
        Report::create([
            'code' => 'FL-2026-0042',
            'edit_code' => 'ED-2026-0042',
            'type' => 'hilang',
            'item_name' => 'Dompet Kulit Coklat',
            'description' => 'Dompet kulit warna coklat tua merek Levi\'s berisi KTP, Kartu Pelajar, dan sedikit uang tunai. Terakhir terlihat saat makan siang di kantin.',
            'special_features' => 'Ada stiker kecil kucing di bagian belakang kartu pelajar dan resleting bagian koin agak macet.',
            'location' => 'Kantin Utama',
            'incident_date' => Carbon::parse('2026-11-02'),
            'photo_url' => null,
            'reporter_name' => 'Rina A.',
            'reporter_phone' => '081234567890',
            'status' => 'proses_pencarian',
            'is_published' => true,
        ]);

        Report::create([
            'code' => 'FL-2026-0044',
            'edit_code' => 'ED-2026-0044',
            'type' => 'hilang',
            'item_name' => 'Botol Minum Biru',
            'description' => 'Tumbler Tupperware warna biru tosca kapasitas 750ml tertinggal di bawah meja nomor 12.',
            'special_features' => 'Ada inisial nama DS di bagian bawah botol.',
            'location' => 'Lab Komputer 2',
            'incident_date' => Carbon::parse('2026-11-01'),
            'photo_url' => null,
            'reporter_name' => 'Dewi S.',
            'reporter_phone' => '081234567891',
            'status' => 'proses_pencarian',
            'is_published' => true,
        ]);

        Report::create([
            'code' => 'FL-2026-0046',
            'edit_code' => 'ED-2026-0046',
            'type' => 'hilang',
            'item_name' => 'Tas Ransel Hitam',
            'description' => 'Tas ransel hitam merek Eiger berisi buku pelajaran dan tempat pensil.',
            'special_features' => 'Gantungan kunci karakter anime Luffy di resleting depan.',
            'location' => 'Perpustakaan',
            'incident_date' => Carbon::parse('2026-10-30'),
            'photo_url' => null,
            'reporter_name' => 'Andi P.',
            'reporter_phone' => '081234567892',
            'status' => 'siap_diambil',
            'is_published' => true,
        ]);

        Report::create([
            'code' => 'FL-2026-0050',
            'edit_code' => 'ED-2026-0050',
            'type' => 'hilang',
            'item_name' => 'Helm Putih',
            'description' => 'Helm bogo warna putih polos ditaruh di spion motor Beat hitam.',
            'special_features' => 'Kaca helm agak tergores tipis di sebelah kanan.',
            'location' => 'Parkiran Motor',
            'incident_date' => Carbon::parse('2026-11-05'),
            'photo_url' => null,
            'reporter_name' => 'Siti N.',
            'reporter_phone' => '081234567893',
            'status' => 'proses_pencarian',
            'is_published' => true,
        ]);

        // 3. Active Reports (Ditemukan)
        $repKunci = Report::create([
            'code' => 'FL-2026-0043',
            'edit_code' => 'ED-2026-0043',
            'type' => 'ditemukan',
            'item_name' => 'Kunci Motor Honda',
            'description' => 'Ditemukan di area parkir dan diamankan di Pos Satpam. Pemilik dapat mengajukan klaim dengan bukti kepemilikan.',
            'special_features' => 'Gantungan boneka beruang warna coklat dengan remote alarm kecil.',
            'location' => 'Parkiran Barat',
            'incident_date' => Carbon::parse('2026-11-03'),
            'photo_url' => null,
            'reporter_name' => 'Pak Budi (Satpam)',
            'reporter_phone' => '081298765432',
            'status' => 'siap_diambil',
            'is_published' => false, // sudah ditindaklanjuti, antrean serah terima
        ]);

        $repKartu = Report::create([
            'code' => 'FL-2026-0045',
            'edit_code' => 'ED-2026-0045',
            'type' => 'ditemukan',
            'item_name' => 'Kartu Pelajar',
            'description' => 'Kartu Pelajar SMK Negeri 1 ditemukan di pinggir lapangan basket.',
            'special_features' => 'Atas nama siswi kelas XI RPL dengan foto latar biru.',
            'location' => 'Lapangan Basket',
            'incident_date' => Carbon::parse('2026-11-04'),
            'photo_url' => null,
            'reporter_name' => 'Satpam Pos 1',
            'reporter_phone' => '081298765433',
            'status' => 'siap_diambil',
            'is_published' => false, // antrean serah terima
        ]);

        $repJam = Report::create([
            'code' => 'FL-2026-0047',
            'edit_code' => 'ED-2026-0047',
            'type' => 'ditemukan',
            'item_name' => 'Jam Tangan Hitam',
            'description' => 'Jam tangan digital sport warna hitam tali karet ditemukan di bangku koridor.',
            'special_features' => 'Merek Casio G-Shock, display waktu format 24 jam dengan baret halus di bezel.',
            'location' => 'Koridor Lantai 2',
            'incident_date' => Carbon::parse('2026-11-05'),
            'photo_url' => null,
            'reporter_name' => 'Siti N.',
            'reporter_phone' => '081234567894',
            'status' => 'siap_diambil',
            'is_published' => true,
        ]);

        Report::create([
            'code' => 'FL-2026-0048',
            'edit_code' => 'ED-2026-0048',
            'type' => 'ditemukan',
            'item_name' => 'Payung Biru',
            'description' => 'Payung lipat warna biru dongker basah tertinggal di tempat payung lobi.',
            'special_features' => 'Gagang kayu motif ukiran kecil.',
            'location' => 'Lobi Depan',
            'incident_date' => Carbon::parse('2026-11-04'),
            'photo_url' => null,
            'reporter_name' => 'Andi P.',
            'reporter_phone' => '081234567895',
            'status' => 'siap_diambil',
            'is_published' => true,
        ]);

        // 4. Claims for found items
        $claimKunci = Claim::create([
            'claim_code' => 'KLM-2026-0001',
            'report_id' => $repKunci->report_id,
            'claimant_name' => 'Rina Aulia',
            'claimant_phone' => '081234567899',
            'proof_description' => 'Kunci motor Vario 160 hitam plat B 4123 KZA, ada gantungan boneka beruang warna coklat dan ada bekas goresan dekat pangkal anak kunci.',
            'proof_photo_url' => null,
            'status' => 'ditindaklanjuti',
        ]);

        $claimKartu = Claim::create([
            'claim_code' => 'KLM-2026-0002',
            'report_id' => $repKartu->report_id,
            'claimant_name' => 'Dewi Sartika',
            'claimant_phone' => '081234567891',
            'proof_description' => 'Kartu pelajar atas nama saya sendiri Dewi Sartika, NIS 22231012, jurusan Rekayasa Perangkat Lunak.',
            'proof_photo_url' => null,
            'status' => 'ditindaklanjuti',
        ]);

        Claim::create([
            'claim_code' => 'KLM-2026-0003',
            'report_id' => $repJam->report_id,
            'claimant_name' => 'Andi Pratama',
            'claimant_phone' => '081234567892',
            'proof_description' => 'Jam tangan G-Shock DW-5600 hitam original, ada stiker kecil hologram di balik caseback.',
            'proof_photo_url' => null,
            'status' => 'menunggu',
        ]);

        // 5. History / Completed Reports (Selesai dengan Handover)
        $history1 = Report::create([
            'code' => 'FL-2026-0031',
            'edit_code' => 'ED-2026-0031',
            'type' => 'ditemukan',
            'item_name' => 'Laptop ASUS Hitam',
            'description' => 'Laptop ASUS VivoBook hitam tertinggal di ruang laboratorium.',
            'special_features' => 'Stiker logo GitHub dan Laravel di cover depan.',
            'location' => 'Lab RPL 1',
            'incident_date' => Carbon::parse('2026-10-27'),
            'photo_url' => null,
            'reporter_name' => 'Pak Budi',
            'reporter_phone' => '081298765432',
            'status' => 'selesai',
            'is_published' => false,
        ]);

        Handover::create([
            'report_id' => $history1->report_id,
            'claim_id' => null,
            'admin_id' => $satpam->admin_id,
            'recipient_name' => 'Andi P.',
            'recipient_contact' => '081234567892',
            'notes' => 'Barang diserahkan setelah pemilik berhasil membuka password laptop dan menunjukkan kartu identitas siswa.',
            'handover_date' => Carbon::parse('2026-10-28 14:30:00'),
            'status' => 'disetujui',
            'verified_by' => $superAdmin->admin_id,
            'verified_at' => Carbon::parse('2026-10-28 15:00:00'),
        ]);

        $history2 = Report::create([
            'code' => 'FL-2026-0027',
            'edit_code' => 'ED-2026-0027',
            'type' => 'hilang',
            'item_name' => 'Dompet Hitam',
            'description' => 'Dompet lipat warna hitam terjatuh di koridor.',
            'special_features' => 'Ada foto keluarga di selipan mika.',
            'location' => 'Koridor Barat',
            'incident_date' => Carbon::parse('2026-10-24'),
            'photo_url' => null,
            'reporter_name' => 'Siti N.',
            'reporter_phone' => '081234567893',
            'status' => 'selesai',
            'is_published' => false,
        ]);

        Handover::create([
            'report_id' => $history2->report_id,
            'claim_id' => null,
            'admin_id' => $satpam->admin_id,
            'recipient_name' => 'Siti N.',
            'recipient_contact' => '081234567893',
            'notes' => 'Dompet diserahkan langsung oleh penemu di pos satpam dan diterima oleh pemilik sah.',
            'handover_date' => Carbon::parse('2026-10-25 10:15:00'),
            'status' => 'disetujui',
            'verified_by' => $superAdmin->admin_id,
            'verified_at' => Carbon::parse('2026-10-25 10:30:00'),
        ]);

        $history3 = Report::create([
            'code' => 'FL-2026-0022',
            'edit_code' => 'ED-2026-0022',
            'type' => 'ditemukan',
            'item_name' => 'Kunci Loker',
            'description' => 'Anak kunci nomor 42 ditemukan di depan loker kelas X.',
            'special_features' => 'Gantungan akrilik angka 42 warna merah.',
            'location' => 'Area Loker',
            'incident_date' => Carbon::parse('2026-10-21'),
            'photo_url' => null,
            'reporter_name' => 'Satpam Pos 2',
            'reporter_phone' => '081298765434',
            'status' => 'selesai',
            'is_published' => false,
        ]);

        Handover::create([
            'report_id' => $history3->report_id,
            'claim_id' => null,
            'admin_id' => $satpam->admin_id,
            'recipient_name' => 'Budi S.',
            'recipient_contact' => '081234567897',
            'notes' => 'Kunci diverifikasi dapat membuka loker 42 milik siswa Budi S.',
            'handover_date' => Carbon::parse('2026-10-22 13:00:00'),
            'status' => 'disetujui',
            'verified_by' => $superAdmin->admin_id,
            'verified_at' => Carbon::parse('2026-10-22 13:15:00'),
        ]);

        $history4 = Report::create([
            'code' => 'FL-2026-0019',
            'edit_code' => 'ED-2026-0019',
            'type' => 'hilang',
            'item_name' => 'Tas Ransel Biru',
            'description' => 'Tas ransel warna biru muda tertinggal di musholla.',
            'special_features' => 'Ada pin badge OSIS di saku samping.',
            'location' => 'Musholla Sekolah',
            'incident_date' => Carbon::parse('2026-10-19'),
            'photo_url' => null,
            'reporter_name' => 'Maya R.',
            'reporter_phone' => '081234567898',
            'status' => 'selesai',
            'is_published' => false,
        ]);

        Handover::create([
            'report_id' => $history4->report_id,
            'claim_id' => null,
            'admin_id' => $satpam->admin_id,
            'recipient_name' => 'Maya R.',
            'recipient_contact' => '081234567898',
            'notes' => 'Tas ransel diambil oleh pemilik setelah diverifikasi isi tasnya.',
            'handover_date' => Carbon::parse('2026-10-20 16:00:00'),
            'status' => 'disetujui',
            'verified_by' => $superAdmin->admin_id,
            'verified_at' => Carbon::parse('2026-10-20 16:30:00'),
        ]);
    }
}
