<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\MenuRole;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

class MenuController extends Controller
{
    public function index()
    {
        if (session('role') != 1) {
            return redirect()->route('dashboard')->with('error', 'Only Super Admin can access Menu Management.');
        }

        // Fetch parent menus with their children and role relationships
        $menus = Menu::whereNull('parent_id')
            ->with(['children.menuRoles.role', 'menuRoles.role'])
            ->orderBy('order', 'asc')
            ->get();

        $allRoles = Role::where('status', 1)->get();

        return view('admin.menu.index', compact('menus', 'allRoles'));
    }

    public function create()
    {
        if (session('role') != 1) {
            return redirect()->route('dashboard')->with('error', 'Only Super Admin can create menus.');
        }

        // Only menus that have NO route and NO url can be parent menus
        $parentMenus = Menu::whereNull('parent_id')
            ->where(function ($q) {
                $q->whereNull('route')->orWhere('route', '');
            })
            ->where(function ($q) {
                $q->whereNull('url')->orWhere('url', '');
            })
            ->orderBy('order', 'asc')
            ->get();

        $roles = Role::where('status', 1)->orderBy('id', 'asc')->get();

        return view('admin.menu.create', compact('parentMenus', 'roles'));
    }

    public function store(Request $request)
    {
        if (session('role') != 1) {
            return redirect()->route('dashboard')->with('error', 'Only Super Admin can create menus.');
        }

        $isParent = ($request->menu_type === 'parent');

        $rules = [
            'name' => 'required|string|max:100',
            'icon' => 'nullable|string|max:100',
            'status' => 'required|in:0,1',
            'roles' => 'required|array|min:1',
            'roles.*' => 'exists:roles,id',
        ];

        if (!$isParent) {
            $rules['parent_id'] = 'nullable|exists:menus,id';
            $rules['route'] = 'nullable|string|max:150';
            $rules['url'] = 'nullable|string|max:200';
            $rules['active_pattern'] = 'nullable|string|max:255';
        }

        $this->validate($request, $rules);

        // Auto-increment order
        if ($isParent) {
            $maxOrder = Menu::whereNull('parent_id')->max('order');
            $order = ($maxOrder !== null) ? $maxOrder + 1 : 1;
            $parentId = null;
            $route = null;
            $url = null;
            $activePattern = null;
        } else {
            $parentId = $request->parent_id ?: null;
            if ($parentId) {
                $maxOrder = Menu::where('parent_id', $parentId)->max('order');
            } else {
                $maxOrder = Menu::whereNull('parent_id')->max('order');
            }
            $order = ($maxOrder !== null) ? $maxOrder + 1 : 1;
            $route = $request->route;
            $url = $request->url;
            $activePattern = $request->active_pattern;
        }

        DB::beginTransaction();
        try {
            $menu = Menu::create([
                'name' => $request->name,
                'icon' => $request->icon,
                'route' => $route,
                'url' => $url,
                'active_pattern' => $activePattern,
                'parent_id' => $parentId,
                'order' => $order,
                'status' => $request->status,
                'permission_key' => $request->permission_key ?? null,
            ]);

            // Attach roles
            foreach ($request->roles as $roleId) {
                MenuRole::create([
                    'menu_id' => $menu->id,
                    'role_id' => $roleId,
                ]);
            }

            DB::commit();
            return redirect()->route('admin.menu.index')->with('success', 'Menu created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to create menu: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Reorder menus via drag and drop AJAX
     */
    public function reorder(Request $request)
    {
        if (session('role') != 1) {
            return response()->json(['success' => false, 'message' => 'Unauthorized.'], 403);
        }

        $items = $request->input('items', []);
        if (empty($items)) {
            return response()->json(['success' => false, 'message' => 'No items provided.'], 400);
        }

        DB::beginTransaction();
        try {
            foreach ($items as $item) {
                if (isset($item['id'])) {
                    $updateData = ['order' => (int) $item['order']];
                    if (array_key_exists('parent_id', $item)) {
                        $updateData['parent_id'] = !empty($item['parent_id']) ? (int) $item['parent_id'] : null;
                    }
                    Menu::where('id', $item['id'])->update($updateData);
                }
            }
            DB::commit();
            return response()->json(['success' => true, 'message' => 'Menu order updated successfully.']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Failed to update order: ' . $e->getMessage()], 500);
        }
    }

    public function edit($id)
    {
        if (session('role') != 1) {
            return redirect()->route('dashboard')->with('error', 'Only Super Admin can edit menus.');
        }

        $menu = Menu::with(['menuRoles', 'children'])->findOrFail($id);

        // Check if menu is a parent menu (no parent, no route, no url)
        $isParent = is_null($menu->parent_id) && empty($menu->route) && empty($menu->url);

        // Only menus that have NO route and NO url can be parent menus (excluding current)
        $parentMenus = Menu::whereNull('parent_id')
            ->where(function ($q) {
                $q->whereNull('route')->orWhere('route', '');
            })
            ->where(function ($q) {
                $q->whereNull('url')->orWhere('url', '');
            })
            ->where('id', '!=', $id)
            ->orderBy('order', 'asc')
            ->get();

        $roles = Role::where('status', 1)->orderBy('id', 'asc')->get();
        $assignedRoleIds = $menu->menuRoles->pluck('role_id')->toArray();

        return view('admin.menu.edit', compact('menu', 'isParent', 'parentMenus', 'roles', 'assignedRoleIds'));
    }

    public function update(Request $request, $id)
    {
        if (session('role') != 1) {
            return redirect()->route('dashboard')->with('error', 'Only Super Admin can update menus.');
        }

        $menu = Menu::findOrFail($id);
        $isParent = ($request->menu_type === 'parent');

        $rules = [
            'name' => 'required|string|max:100',
            'icon' => 'nullable|string|max:100',
            'status' => 'required|in:0,1',
            'roles' => 'required|array|min:1',
            'roles.*' => 'exists:roles,id',
        ];

        if (!$isParent) {
            $rules['parent_id'] = 'nullable|exists:menus,id|not_in:' . $id;
            $rules['route'] = 'nullable|string|max:150';
            $rules['url'] = 'nullable|string|max:200';
            $rules['active_pattern'] = 'nullable|string|max:255';
        }

        $this->validate($request, $rules);

        if ($isParent) {
            $parentId = null;
            $route = null;
            $url = null;
            $activePattern = null;
        } else {
            $parentId = $request->parent_id ?: null;
            $route = $request->route;
            $url = $request->url;
            $activePattern = $request->active_pattern;
        }

        DB::beginTransaction();
        try {
            $menu->update([
                'name' => $request->name,
                'icon' => $request->icon,
                'route' => $route,
                'url' => $url,
                'active_pattern' => $activePattern,
                'parent_id' => $parentId,
                'status' => $request->status,
                'permission_key' => $request->permission_key ?? null,
            ]);

            // Sync roles
            MenuRole::where('menu_id', $menu->id)->delete();
            foreach ($request->roles as $roleId) {
                MenuRole::create([
                    'menu_id' => $menu->id,
                    'role_id' => $roleId,
                ]);
            }

            DB::commit();
            return redirect()->route('admin.menu.index')->with('success', 'Menu updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to update menu: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        if (session('role') != 1) {
            return redirect()->route('dashboard')->with('error', 'Only Super Admin can delete menus.');
        }

        $menu = Menu::findOrFail($id);

        // Delete menu and its role associations
        DB::beginTransaction();
        try {
            MenuRole::where('menu_id', $id)->delete();
            // If it's a parent, also delete children
            foreach ($menu->children as $child) {
                MenuRole::where('menu_id', $child->id)->delete();
                $child->delete();
            }
            $menu->delete();

            DB::commit();
            return redirect()->route('admin.menu.index')->with('success', 'Menu and its submenus deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.menu.index')->with('error', 'Failed to delete menu: ' . $e->getMessage());
        }
    }
}
