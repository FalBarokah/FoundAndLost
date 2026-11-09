<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Claim;
use App\Models\Report;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LostAndFoundWorkflowTest extends TestCase
{
    use RefreshDatabase;

    protected Admin $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = Admin::create([
            'name' => 'Admin Satpam',
            'email' => 'admin@email.com',
            'password' => Hash::make('password'),
        ]);
    }

    public function test_home_page_is_accessible(): void
    {
        $response = $this->get(route('home'));
        $response->assertStatus(200);
        $response->assertSee('Found & Lost');
        $response->assertSee('Prosedur');
    }

    public function test_user_can_submit_lost_item_report(): void
    {
        $response = $this->post(route('report.store'), [
            'type' => 'hilang',
            'item_name' => 'Dompet Kulit Coklat',
            'description' => 'Dompet kulit berisi KTP dan ATM.',
            'special_features' => 'Ada gantungan kunci kucing',
            'location' => 'Kantin Utama',
            'incident_date' => '2026-11-03',
            'reporter_name' => 'Rina Aulia',
            'reporter_phone' => '081234567890',
        ]);

        $report = Report::where('item_name', 'Dompet Kulit Coklat')->first();
        $this->assertNotNull($report);
        $this->assertStringStartsWith('FL-', $report->code);
        $this->assertStringStartsWith('ED-', $report->edit_code);

        $response->assertRedirect(route('report.success', ['code' => $report->code]));
    }

    public function test_user_can_submit_claim_for_found_item(): void
    {
        $report = Report::create([
            'code' => 'FL-2026-0043',
            'edit_code' => 'ED-2026-0043',
            'type' => 'ditemukan',
            'item_name' => 'Kunci Motor Honda',
            'description' => 'Kunci motor tergeletak di bangku.',
            'special_features' => 'Gantungan karet hitam bertuliskan One Heart',
            'location' => 'Parkiran Barat',
            'incident_date' => '2026-11-03',
            'reporter_name' => 'Ahmad Satpam',
            'reporter_phone' => '081298765432',
            'status' => 'siap_diambil',
            'is_published' => true,
        ]);

        $response = $this->post(route('claim.store', ['code' => $report->code]), [
            'claimant_name' => 'Budi Santoso',
            'claimant_phone' => '081345678901',
            'proof_description' => 'Gantungan karet hitam One Heart, ada remote alarm kecil.',
        ]);

        $claim = Claim::where('report_id', $report->report_id)->first();
        $this->assertNotNull($claim);
        $this->assertStringStartsWith('KLM-', $claim->claim_code);
        $this->assertEquals('menunggu', $claim->status);

        $response->assertRedirect(route('claim.success', ['claim_code' => $claim->claim_code]));
    }

    public function test_admin_can_approve_claim_and_complete_handover(): void
    {
        $report = Report::create([
            'code' => 'FL-2026-0044',
            'edit_code' => 'ED-2026-0044',
            'type' => 'ditemukan',
            'item_name' => 'Helm KYT Putih',
            'description' => 'Helm ditinggal di spion motor.',
            'special_features' => 'Stiker bendera di kaca helm',
            'location' => 'Parkiran Timur',
            'incident_date' => '2026-11-04',
            'reporter_name' => 'Bambang',
            'reporter_phone' => '081299998888',
            'status' => 'siap_diambil',
            'is_published' => true,
        ]);

        $claim = Claim::create([
            'claim_code' => 'KLM-2026-0088',
            'report_id' => $report->report_id,
            'claimant_name' => 'Citra Dewi',
            'claimant_phone' => '081377776666',
            'proof_description' => 'Stiker bendera di visor bagian kiri.',
            'status' => 'menunggu',
        ]);

        // Admin approves claim
        $approveResponse = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.claim.approve', ['id' => $claim->claim_id]));

        $approveResponse->assertSessionHas('success');
        $claim->refresh();
        $report->refresh();
        $this->assertEquals('ditindaklanjuti', $claim->status);
        $this->assertFalse((bool) $report->is_published);

        // Admin performs handover
        $handoverResponse = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.handover.store', ['id' => $report->report_id]), [
                'recipient_name' => 'Citra Dewi',
                'recipient_contact' => '081377776666',
                'notes' => 'Barang telah diterima langsung oleh pemilik asli.',
            ]);

        $handoverResponse->assertRedirect(route('admin.history.index'));
        $report->refresh();
        $this->assertEquals('selesai', $report->status);
        $this->assertNotNull($report->handover);
        $this->assertEquals('Citra Dewi', $report->handover->recipient_name);
    }

    public function test_user_can_track_report(): void
    {
        $report = Report::create([
            'code' => 'FL-2026-9999',
            'edit_code' => 'ED-2026-9999',
            'type' => 'hilang',
            'item_name' => 'Kacamata Hitam',
            'description' => 'Tertinggal di perpus',
            'location' => 'Perpustakaan',
            'incident_date' => '2026-11-05',
            'reporter_name' => 'Hadi',
            'reporter_phone' => '081234567890',
            'status' => 'proses_pencarian',
            'is_published' => true,
        ]);

        $response = $this->post(route('report.track'), [
            'code' => 'FL-2026-9999',
        ]);

        $response->assertRedirect(route('report.show', ['code' => 'FL-2026-9999']));
    }

    public function test_phone_number_must_be_numeric_between_10_and_13_digits(): void
    {
        $responseInvalidLetters = $this->post(route('report.store'), [
            'type' => 'hilang',
            'item_name' => 'Laptop Asus',
            'description' => 'Tertinggal',
            'location' => 'Lab 1',
            'incident_date' => '2026-11-05',
            'reporter_name' => 'Dodi',
            'reporter_phone' => 'kepo',
        ]);
        $responseInvalidLetters->assertSessionHasErrors('reporter_phone');

        $responseInvalidLength = $this->post(route('report.store'), [
            'type' => 'hilang',
            'item_name' => 'Laptop Asus',
            'description' => 'Tertinggal',
            'location' => 'Lab 1',
            'incident_date' => '2026-11-05',
            'reporter_name' => 'Dodi',
            'reporter_phone' => '12345',
        ]);
        $responseInvalidLength->assertSessionHasErrors('reporter_phone');
    }

    public function test_unauthenticated_user_is_redirected_to_admin_login(): void
    {
        $response = $this->get(route('admin.dashboard'));
        $response->assertRedirect(route('admin.login'));

        $postResponse = $this->post(route('admin.claim.cancel', ['id' => 1]));
        $postResponse->assertRedirect(route('admin.login'));
    }

    public function test_admin_can_cancel_claim(): void
    {
        $report = Report::create([
            'code' => 'FL-2026-7777',
            'edit_code' => 'ED-2026-7777',
            'type' => 'ditemukan',
            'item_name' => 'Kunci Motor',
            'description' => 'Ditemukan',
            'location' => 'Parkiran',
            'incident_date' => '2026-11-05',
            'reporter_name' => 'Satpam',
            'reporter_phone' => '081234567890',
            'status' => 'siap_diambil',
            'is_published' => true,
        ]);

        $claim = Claim::create([
            'claim_code' => 'KLM-2026-7777',
            'report_id' => $report->report_id,
            'claimant_name' => 'Penipu',
            'claimant_phone' => '081234567891',
            'proof_description' => 'Salah ciri',
            'status' => 'menunggu',
        ]);

        $response = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.claim.cancel', ['id' => $claim->claim_id]));

        $response->assertSessionHas('success');
        $claim->refresh();
        $this->assertEquals('dibatalkan', $claim->status);
    }

    public function test_admin_can_login_even_if_password_was_edited_in_plaintext_in_database(): void
    {
        // Simulate user directly editing password in phpMyAdmin to plaintext 'Hallo123'
        DB::table('admins')
            ->where('admin_id', $this->admin->admin_id)
            ->update(['password' => 'Hallo123']);

        $response = $this->post(route('admin.authenticate'), [
            'email' => $this->admin->email,
            'password' => 'Hallo123',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($this->admin, 'admin');

        // Verify it was upgraded to a valid Bcrypt hash in DB
        $updatedAdmin = Admin::find($this->admin->admin_id);
        $this->assertTrue(str_starts_with($updatedAdmin->password, '$2y$'));
    }

    public function test_login_with_wrong_credentials_shows_error(): void
    {
        $response = $this->post(route('admin.authenticate'), [
            'email' => $this->admin->email,
            'password' => 'wrong-password',
        ]);

        $response->assertSessionHasErrors('login_error');
    }
}
