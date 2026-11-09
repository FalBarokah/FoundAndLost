<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Claim extends Model
{
    use HasFactory;

    protected $table = 'claims';

    protected $primaryKey = 'claim_id';

    protected $fillable = [
        'claim_code',
        'report_id',
        'claimant_name',
        'claimant_phone',
        'proof_description',
        'proof_photo_url',
        'status',
    ];

    public function report()
    {
        return $this->belongsTo(Report::class, 'report_id', 'report_id');
    }

    public function handover()
    {
        return $this->hasOne(Handover::class, 'claim_id', 'claim_id');
    }

    public function getFormattedStatusAttribute(): string
    {
        return match ($this->status) {
            'menunggu' => 'Menunggu Verifikasi',
            'ditindaklanjuti' => 'Disetujui / Ditindaklanjuti',
            'dibatalkan' => 'Dibatalkan',
            default => ucfirst($this->status),
        };
    }
}
