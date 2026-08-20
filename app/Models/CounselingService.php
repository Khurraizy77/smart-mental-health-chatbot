<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CounselingService extends Model
{
    protected $primaryKey = 'service_id';

    protected $fillable = [
        'service_name',
        'contact_info',
        'counsellor_email',
        'description',
    ];

    public function referrals(): HasMany
    {
        return $this->hasMany(Referral::class, 'service_id', 'service_id');
    }
}
