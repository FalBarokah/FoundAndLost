<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Handover extends Model
{
    use HasFactory;

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
    ];

    protected function casts(): array
    {
        return [
            'handover_date' => 'datetime',
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
}
