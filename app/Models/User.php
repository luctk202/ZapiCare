<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
//        'code',
        'phone',
        'address',
        'avatar',
        'province_id',
        'province_name',
        'district_id',
        'district_name',
        'ward_id',
        'ward_name',
        'email',
        'password',
        'status',
        'verified',
        'verified_time',
        'verified_id',
        'balance',
        'total_order',
        'total_revenue',
        'bank_name',
        'bank_username',
        'bank_number',
        'device_token',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        //'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        //'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        //'profile_photo_url',
    ];


    /*public function references()
    {
        return $this->belongsTo(User::class, 'references_id');
    }*/

    public function verified_by()
    {
        return $this->belongsTo(Admin::class, 'verified_id');
    }

    public function shop()
    {
        return $this->hasMany(Shop::class, 'user_id');
    }

    public function partner()
    {
        return $this->hasMany(Partner::class, 'user_id');
    }

    public function fileUpload()
    {
        return $this->hasMany(FileUpload::class,'user_id');
    }
    public function fileExtract()
    {
        return $this->hasMany(FileExtract::class,'user_id');
    }
}
