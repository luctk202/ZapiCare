<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use \App\Models\Province;
use \App\Models\District;
use \App\Models\Ward;
class UserContact extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'date_of_birth',
        'gender',
        'province_id',
        'district_id',
        'ward_id',
        'address',
        'age',
        'body_shape',
        'file_upload_id',
        'user_id'
    ];

    public function province()
    {
        return $this->belongsTo(Province::class, 'province_id');
    }

    public function district()
    {
        return $this->belongsTo(District::class, 'district_id');
    }

    public function ward()
    {
        return $this->belongsTo(Ward::class, 'ward_id');
    }

}
