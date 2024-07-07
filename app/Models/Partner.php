<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Partner extends Model
{
    use HasFactory;

    protected $table = 'partners';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'province_id',
        'province_name',
        'district_id',
        'district_name',
        'description',
        'ward_id',
        'ward_name',
        'code',
        'name',
        'phone',
        'address',
        'status',
        'logo'
    ];

    /*public function province(){
        return $this->belongsTo(Province::class, 'province_id');
    }*/

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }
    public function partner_setting()
    {
        return $this->hasMany(PartnerSetting::class,'partner_id');
    }
}
