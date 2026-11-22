<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $table = 'roles';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'status',
    ];

    public function members()
    {
        return $this->hasMany(Member::class, 'role_id');
    }

    public function menuRoles()
    {
        return $this->hasMany(MenuRole::class, 'role_id');
    }

    public function menus()
    {
        return $this->belongsToMany(Menu::class, 'menu_roles', 'role_id', 'menu_id');
    }
}
