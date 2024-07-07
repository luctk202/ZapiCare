<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    protected $table = 'province';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'order'
    ];
    public function userContacts()
    {
        return $this->hasMany(UserContact::class, 'province_id');
    }
    public function districts()
    {
        return $this->hasMany(District::class);
    }
}
