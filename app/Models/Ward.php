<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ward extends Model
{
    use HasFactory;

    protected $table = 'ward';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'district_id'
    ];
    public function userContacts()
    {
        return $this->hasMany(UserContact::class, 'ward_id');
    }

    public function district()
    {
        return $this->belongsTo(District::class, 'district_id');
    }
}
