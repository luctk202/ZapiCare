<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PartnerSetting extends Model
{
    use HasFactory;

    protected $table = 'partner_settings';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'partner_id',
        'commission',
    ];

    public function partner(){
        return $this->belongsTo(Partner::class, 'partner_id');
    }
}
