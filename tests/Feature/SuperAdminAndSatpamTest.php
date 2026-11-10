<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Handover;
use App\Models\Report;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class SuperAdminAndSatpamTest extends TestCase
{
    use RefreshDatabase;

    protected Admin $superAdmin;

    protected Admin $satpam;

    protected function setUp(): void
    {
        parent::setUp();

        $this->superAdmin = Admin::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@example.com',
            'password' => Hash::make('password'),
            'role' => Admin::ROLE_SUPER_ADMIN,
        ]);

        $this->satpam = Admin::create([
            'name' => 'Satpam Pos 1',
            'email' => 'satpam@example.com',
            'password' => Hash::make('password'),
            'role' => Admin::ROLE_SATPAM,
        ]);
    }

    public function test_login_page_renders_toggle_for_admin_and_satpam(): void
    {
        $response = $this->get(route('admin.login'));

        $response->assertStatus(200);
        $response->assertSee('Admin');
        $response->assertSee('Satpam');
        $response->assertSee('login-role-input');
    }

    public function test_login_with_correct_role_toggle_succeeds(): void
    {
        // Login as Super Admin
        $responseAdmin = $this->post(route('admin.authenticate'), [
            'email' => $this->superAdmin->email,
            'password' => 'password',
            'role' => 'super_admin',
        ]);
        $responseAdmin->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($this->superAdmin, 'admin');

        $this->post(route('admin.logout'));

        // Admin with role 'admin' string in database can also login
        $plainAdmin = Admin::create([
            'name' => 'Faiz Admin',
            'email' => 'admin_plain@gmail.com',
            'password' => Hash::make('Hallo123'),
            'role' => 'admin',
        ]);
        $responsePlainAdmin = $this->post(route('admin.authenticate'), [
            'email' => 'admin_plain@gmail.com',
            'password' => 'Hallo123',
            'role' => 'super_admin',
        ]);
        $responsePlainAdmin->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($plainAdmin, 'admin');

        $this->post(route('admin.logout'));

        // Login as Satpam
        $responseSatpam = $this->post(route('admin.authenticate'), [
            'email' => $this->satpam->email,
            'password' => 'password',
            'role' => 'satpam',
        ]);
        $responseSatpam->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($this->satpam, 'admin');
    }

    public function test_login_with_mismatched_role_toggle_fails_with_helpful_message(): void
    {
        // Satpam tries logging in via Super Admin toggle
        $response = $this->post(route('admin.authenticate'), [
            'email' => $this->satpam->email,
            'password' => 'password',
            'role' => 'super_admin',
        ]);

        $response->assertSessionHasErrors('login_error');
        $this->assertGuest('admin');
    }

    public function test_satpam_is_forbidden_from_super_admin_routes(): void
    {
        // Satpam cannot access /admin/verifikasi
        $response = $this->actingAs($this->satpam, 'admin')
            ->get(route('admin.verification.index'));
        $response->assertRedirect(route('admin.dashboard'));
        $response->assertSessionHas('error');

        // Satpam cannot access /admin/satpam
        $response = $this->actingAs($this->satpam, 'admin')
            ->get(route('admin.satpam.index'));
        $response->assertRedirect(route('admin.dashboard'));

        // Satpam cannot access /admin/history (history moved to Super Admin)
        $response = $this->actingAs($this->satpam, 'admin')
            ->get(route('admin.history.index'));
        $response->assertRedirect(route('admin.dashboard'));
    }

    public function test_satpam_handover_submission_awaits_super_admin_verification(): void
    {
        $report = Report::create([
            'code' => 'FL-2026-1001',
            'edit_code' => 'ED-2026-1001',
            'type' => 'hilang',
            'item_name' => 'Payung Biru',
            'description' => 'Payung lipat tertinggal',
            'location' => 'Lobi',
            'incident_date' => Carbon::parse('2026-11-01'),
            'reporter_name' => 'Budi',
            'reporter_phone' => '081234567890',
            'status' => 'siap_diambil',
            'is_published' => true,
        ]);

        $response = $this->actingAs($this->satpam, 'admin')
            ->post(route('admin.handover.store', $report->report_id), [
                'recipient_name' => 'Budi Santoso',
                'recipient_contact' => '081234567890',
                'notes' => 'Diambil oleh pemilik langsung di pos.',
            ]);

        $response->assertRedirect(route('admin.handover.index'));
        $response->assertSessionHas('success');

        $report->refresh();
        $this->assertEquals('menunggu_verifikasi', $report->status);
        $this->assertFalse((bool) $report->is_published);

        $handover = $report->handover;
        $this->assertNotNull($handover);
        $this->assertEquals(Handover::STATUS_MENUNGGU_VERIFIKASI, $handover->status);
        $this->assertEquals($this->satpam->admin_id, $handover->admin_id);
    }

    public function test_super_admin_can_approve_handover(): void
    {
        $report = Report::create([
            'code' => 'FL-2026-1002',
            'edit_code' => 'ED-2026-1002',
            'type' => 'hilang',
            'item_name' => 'Kunci Motor',
            'description' => 'Kunci motor Honda',
            'location' => 'Parkiran',
            'incident_date' => Carbon::parse('2026-11-02'),
            'reporter_name' => 'Rian',
            'reporter_phone' => '081234567891',
            'status' => 'menunggu_verifikasi',
            'is_published' => false,
        ]);

        $handover = Handover::create([
            'report_id' => $report->report_id,
            'admin_id' => $this->satpam->admin_id,
            'recipient_name' => 'Rian Wijaya',
            'recipient_contact' => '081234567891',
            'handover_date' => Carbon::now(),
            'status' => Handover::STATUS_MENUNGGU_VERIFIKASI,
        ]);

        $response = $this->actingAs($this->superAdmin, 'admin')
            ->post(route('admin.verification.approve', $handover->handover_id));

        $response->assertRedirect(route('admin.history.index'));
        $response->assertSessionHas('success');

        $report->refresh();
        $handover->refresh();

        $this->assertEquals('selesai', $report->status);
        $this->assertEquals(Handover::STATUS_DISETUJUI, $handover->status);
        $this->assertEquals($this->superAdmin->admin_id, $handover->verified_by);
        $this->assertNotNull($handover->verified_at);
    }

    public function test_super_admin_can_reject_handover_and_it_returns_to_satpam(): void
    {
        $report = Report::create([
            'code' => 'FL-2026-1003',
            'edit_code' => 'ED-2026-1003',
            'type' => 'hilang',
            'item_name' => 'Jaket Jeans',
            'description' => 'Jaket biru',
            'location' => 'Kantin',
            'incident_date' => Carbon::parse('2026-11-03'),
            'reporter_name' => 'Sinta',
            'reporter_phone' => '081234567892',
            'status' => 'menunggu_verifikasi',
            'is_published' => false,
        ]);

        $handover = Handover::create([
            'report_id' => $report->report_id,
            'admin_id' => $this->satpam->admin_id,
            'recipient_name' => 'Sinta Rahayu',
            'recipient_contact' => '081234567892',
            'handover_date' => Carbon::now(),
            'status' => Handover::STATUS_MENUNGGU_VERIFIKASI,
        ]);

        $response = $this->actingAs($this->superAdmin, 'admin')
            ->post(route('admin.verification.reject', $handover->handover_id), [
                'rejection_reason' => 'Foto bukti tidak jelas dan nama penerima berbeda dari data pelapor.',
            ]);

        $response->assertRedirect(route('admin.verification.index'));
        $response->assertSessionHas('success');

        $report->refresh();
        $handover->refresh();

        // Status report kembali ke 'siap_diambil' agar tampil kembali di antrean serah terima satpam
        $this->assertEquals('siap_diambil', $report->status);
        $this->assertEquals(Handover::STATUS_DITOLAK, $handover->status);
        $this->assertEquals('Foto bukti tidak jelas dan nama penerima berbeda dari data pelapor.', $handover->rejection_reason);
        $this->assertEquals($this->superAdmin->admin_id, $handover->verified_by);
    }

    public function test_super_admin_can_crud_satpam_accounts(): void
    {
        // 1. List Satpam
        $indexResponse = $this->actingAs($this->superAdmin, 'admin')
            ->get(route('admin.satpam.index'));
        $indexResponse->assertStatus(200);
        $indexResponse->assertSee($this->satpam->name);

        // 2. Create Satpam
        $storeResponse = $this->actingAs($this->superAdmin, 'admin')
            ->post(route('admin.satpam.store'), [
                'name' => 'Satpam Baru',
                'email' => 'satpam.baru@example.com',
                'password' => 'secret123',
            ]);
        $storeResponse->assertRedirect(route('admin.satpam.index'));

        $newSatpam = Admin::where('email', 'satpam.baru@example.com')->first();
        $this->assertNotNull($newSatpam);
        $this->assertEquals(Admin::ROLE_SATPAM, $newSatpam->role);

        // 3. Update Satpam
        $updateResponse = $this->actingAs($this->superAdmin, 'admin')
            ->put(route('admin.satpam.update', $newSatpam->admin_id), [
                'name' => 'Satpam Baru Updated',
                'email' => 'satpam.baru@example.com',
                'password' => '',
            ]);
        $updateResponse->assertRedirect(route('admin.satpam.index'));
        $newSatpam->refresh();
        $this->assertEquals('Satpam Baru Updated', $newSatpam->name);

        // 4. Delete Satpam
        $deleteResponse = $this->actingAs($this->superAdmin, 'admin')
            ->delete(route('admin.satpam.destroy', $newSatpam->admin_id));
        $deleteResponse->assertRedirect(route('admin.satpam.index'));
        $this->assertNull(Admin::find($newSatpam->admin_id));
    }
}
