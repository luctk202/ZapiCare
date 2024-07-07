<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FilterAttribute extends Model
{
    use HasFactory;

    protected $table = 'filter_attributes';

    protected $fillable = ['name' , 'filter_id'];


    public function filter()
    {
        return $this->belongsTo(Filter::class, 'filter_id');
    }
}
