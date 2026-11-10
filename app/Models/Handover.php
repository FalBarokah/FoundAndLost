<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Handover extends Model
{
    use HasFactory;

    public const STATUS_MENUNGGU_VERIFIKASI = 'menunggu_verifikasi';

    public const STATUS_DISETUJUI = 'disetujui';

    public const STATUS_DITOLAK = 'ditolak';

    protected $table = 'handovers';

    protected $primaryKey = 'handover_id';

    protected $fillable = [
        'report_id',
        'claim_id',
        'admin_id',
        'recipient_name',
        'recipient_contact',
        'proof_photo_url',
        'notes',
        'handover_date',
        'status',
        'verified_by',
        'verified_at',
        'rejection_reason',
    ];

    protected function casts(): array
    {
        return [
            'handover_date' => 'datetime',
            'verified_at' => 'datetime',
        ];
    }

    public function report()
    {
        return $this->belongsTo(Report::class, 'report_id', 'report_id');
    }

    public function claim()
    {
        return $this->belongsTo(Claim::class, 'claim_id', 'claim_id');
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'admin_id', 'admin_id');
    }

    public function verifier()
    {
        return $this->belongsTo(Admin::class, 'verified_by', 'admin_id');
    }

    public function isMenungguVerifikasi(): bool
    {
        return $this->status === self::STATUS_MENUNGGU_VERIFIKASI;
    }

    public function isDisetujui(): bool
    {
        return $this->status === self::STATUS_DISETUJUI;
    }

    public function isDitolak(): bool
    {
        return $this->status === self::STATUS_DITOLAK;
    }
}
