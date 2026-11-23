<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $table = 'menus';

    protected $fillable = [
        'name',
        'icon',
        'route',
        'url',
        'active_pattern',
        'parent_id',
        'order',
        'status',
        'permission_key',
    ];

    public function parent()
    {
        return $this->belongsTo(Menu::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Menu::class, 'parent_id')->orderBy('order', 'asc');
    }

    public function menuRoles()
    {
        return $this->hasMany(MenuRole::class, 'menu_id');
    }

    /**
     * Check if menu or any of its children match current request pattern
     */
    public function isActive()
    {
        if ($this->active_pattern) {
            $patterns = array_map('trim', explode(',', $this->active_pattern));
            foreach ($patterns as $pattern) {
                if (request()->is($pattern)) {
                    return true;
                }
            }
        }

        if ($this->route && request()->routeIs($this->route)) {
            return true;
        }

        return false;
    }

    /**
     * Check if any child item is active (for treeview open state)
     */
    public function hasActiveChild()
    {
        foreach ($this->children as $child) {
            if ($child->isActive()) {
                return true;
            }
        }
        return false;
    }
}
