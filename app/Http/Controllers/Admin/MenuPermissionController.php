<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Menu;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Helpers\FormatResponseJson;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;
class MenuPermissionController extends Controller
{
    public function index()
    {
        return view('pages.admin.menus.index');
    }
    public function fetch()
    {
        try {
            $menus = Menu::with('permissions.roles')->orderBy('id', 'DESC')->get();
            return FormatResponseJson::success($menus, 'Menus fetched successfully');
        } catch (\Throwable $th) {
            return FormatResponseJson::error($th->getMessage());
        }
    }
    public function store(Request $request)
    {
        try {
            // dd($request->all());
            DB::beginTransaction();
            $validator = Validator::make($request->all(), [
                'menu_name' => 'required|string',
                'menu_url' => 'required|string',
                'menu_icon' => 'required|string',
                'roles' => 'nullable|array',
            ], [
                'menu_name.required' => 'Nama tidak boleh kosong',
                'menu_url.required' => 'Link / Url tidak boleh kosong',
                'menu_icon.required' => 'Icon tidak boleh kosong',
            ]);
            if ($validator->fails()) {
                throw new ValidationException($validator);
            }
            if ($request->menu_url && !Permission::where('name', $request->menu_url)->exists()) {
                // dd($request->menu_url);
                Permission::create(['name' => $request->menu_url, 'guard_name' => 'web']);
            }

            $data = [
                'name' => $request->menu_name,
                'url' => $request->menu_url,
                'icon' => $request->menu_icon,
                'permission' => $request->menu_url,
                'type' => $request->menu_type ?? 'link',
                'parent_id' => $request->parent_id ?? null,
                'is_active' => $request->is_active ?? true,
            ];

            $menu = Menu::create($data);

            // Assign permission ke role
            if ($request->menu_url && $request->roles) {
                // dd($request->parent_id);
                foreach ($request->roles as $roleId) {
                    $role = Role::findById($roleId);
                    $role->givePermissionTo($request->menu_url);
                }
            }
            DB::commit();
            return FormatResponseJson::success( $menu, 'Menu created successfully');
        } catch (ValidationException $e) {
            DB::rollback();
            return FormatResponseJson::error(null, ['errors' => $e->errors()], 422);}
        catch (\Throwable $th) {
            DB::rollback();
            return FormatResponseJson::error(null, $th->getMessage(), 500);
        }
    }
    public function fetchById(Request $request)
    {
        try {
            $menu = Menu::with(['permissions.roles'])->find($request->id);
            return FormatResponseJson::success($menu, 'menu berhasil diambil');
        } catch (\Throwable $th) {
            return FormatResponseJson::error(null, $th->getMessage(), 500);
        }
    }
    public function update(Request $request)
    {
        try {
            DB::beginTransaction();

            // Validasi
            $validator = Validator::make($request->all(), [
                'menu_name' => 'required|string',
                'menu_url' => 'required|string',
                'menu_icon' => 'required|string',
                'roles' => 'nullable|array',
            ], [
                'menu_name.required' => 'Nama tidak boleh kosong',
                'menu_url.required' => 'Link / Url tidak boleh kosong',
                'menu_icon.required' => 'Icon tidak boleh kosong',
            ]);

            if ($validator->fails()) {
                throw new ValidationException($validator);
            }

            // Ambil data menu
            $menu = Menu::findOrFail($request->menu_id);

            // Cek dan buat permission jika belum ada
            $permissionName = $request->menu_url;
            $permission = Permission::firstOrCreate(
                ['name' => $permissionName, 'guard_name' => 'web']
            );

            // Update data menu
            $data = [
                'name' => $request->menu_name,
                'url' => $permissionName,
                'icon' => $request->menu_icon,
                'permission' => $permissionName,
                'type' => $request->menu_type ?? 'link',
                'parent_id' => $request->parent_id ?? null,
                'is_active' => $request->is_active ?? true,
            ];
            $menu->update($data);

            // =============================
            // Sync permission to selected roles
            // =============================

            // Ambil semua role yang saat ini memiliki permission ini
            $rolesWithPermission = Role::permission($permissionName)->get();

            // Role yang dipilih di form
            $selectedRoleIds = $request->roles ?? [];

            // Cabut permission dari role yang tidak lagi dipilih
            foreach ($rolesWithPermission as $role) {
                if (!in_array($role->id, $selectedRoleIds)) {
                    $role->revokePermissionTo($permission);
                }
            }

            // Berikan permission ke role yang dipilih
            foreach ($selectedRoleIds as $roleId) {
                $role = Role::findById($roleId);
                if (!$role->hasPermissionTo($permission, 'web')) {
                    $role->givePermissionTo($permission);
                }
            }

            DB::commit();
            return FormatResponseJson::success($menu, 'Menu updated successfully');

        } catch (ValidationException $e) {
            DB::rollBack();
            return FormatResponseJson::error(null, ['errors' => $e->errors()], 422);
        } catch (\Throwable $th) {
            DB::rollBack();
            return FormatResponseJson::error(null, $th->getMessage(), 500);
        }
    }

}
