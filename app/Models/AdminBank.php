<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminBank extends Model
{
    use HasFactory;

    protected $table = 'admin_bank';

    protected $fillable = ['bank_name', 'bank_username', 'bank_number', 'bank_content'];


}
