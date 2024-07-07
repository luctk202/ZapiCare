<?php

namespace App\Models;

use App\Support\Authorization\AuthorizationRoleTrait;
use Illuminate\Database\Eloquent\Model;

class AdminRole extends Model
{
    use AuthorizationRoleTrait;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'admin_roles';

    protected $fillable = ['name', 'description'];

    public function admins()
    {
        return $this->hasMany(Admin::class, 'role_id');
    }

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    /*protected static function newFactory()
    {
        return new RoleFactory;
    }*/
}
