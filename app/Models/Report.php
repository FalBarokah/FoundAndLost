<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $table = 'reports';

    protected $primaryKey = 'report_id';

    protected $fillable = [
        'code',
        'edit_code',
        'type',
        'item_name',
        'description',
        'special_features',
        'location',
        'incident_date',
        'photo_url',
        'reporter_name',
        'reporter_phone',
        'status',
        'is_published',
    ];

    protected function casts(): array
    {
        return [
            'incident_date' => 'date',
            'is_published' => 'boolean',
        ];
    }

    public function claims()
    {
        return $this->hasMany(Claim::class, 'report_id', 'report_id');
    }

    public function handover()
    {
        return $this->hasOne(Handover::class, 'report_id', 'report_id');
    }

    public function isHilang(): bool
    {
        return $this->type === 'hilang';
    }

    public function isDitemukan(): bool
    {
        return $this->type === 'ditemukan';
    }

    public function isMenungguVerifikasi(): bool
    {
        return $this->status === 'menunggu_verifikasi';
    }

    public function isSelesai(): bool
    {
        return $this->status === 'selesai';
    }

    public function getFormattedStatusAttribute(): string
    {
        return match ($this->status) {
            'proses_pencarian' => 'Proses Pencarian',
            'siap_diambil' => 'Siap Diambil',
            'menunggu_verifikasi' => 'Menunggu Verifikasi',
            'selesai' => 'Selesai',
            default => ucfirst(str_replace('_', ' ', $this->status)),
        };
    }
}
