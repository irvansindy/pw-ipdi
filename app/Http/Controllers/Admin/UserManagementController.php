<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use DB;
use Illuminate\View\View;
use App\Helpers\FormatResponseJson;
use App\Models\User;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ExportUser;
class UserManagementController extends Controller
{
    function index() {
        return view("pages.admin.users.index");
    }
    public function fetch()
    {
        try {
            $users = User::whereHas('roles', function ($query) {
                $query->where('name', 'user');
            })->get();
            return FormatResponseJson::success($users, 'Data user berhasil diambil');
        } catch (\Throwable $th) {
            return FormatResponseJson::error(null, $th->getMessage(), 500);
        }
    }
    public function fetchAdmin()
    {
        try {
            $admin = User::whereHas('roles', function ($query) {
                $query->where('name', '!=', 'user');
            })->get();
            
            return FormatResponseJson::success($admin, 'Data admin berhasil diambil');
        } catch (\Throwable $th) {
            return FormatResponseJson::error(null, $th->getMessage(), 500);
        }
    }
    public function fetchById(Request $request)
    {
        try {
            $user = User::where('id', $request->id)->first();
            return FormatResponseJson::success($user,'Data user berhasil diambil');
        } catch (\Throwable $th) {
            return FormatResponseJson::error(null, $th->getMessage(), 500);
        }
    }
    public function storeAdmin(Request $request)
    {
        try {
            $request->validate([
                'user_id' => 'required|exists:users,id',
                'roles'   => 'required|array',
                'roles.*' => 'exists:roles,id',
            ]);
            $user = User::findOrFail($request->user_id);
            // dd($request->all());
            // Ambil nama role berdasarkan ID
            $roleNames = Role::whereIn('id', $request->roles)->pluck('name')->toArray();

            // Assign multiple roles
            $user->syncRoles($roleNames);
            return FormatResponseJson::success($user, 'User admin berhasil dibuat');
        } catch (\Throwable $th) {
            return FormatResponseJson::error(null, $th->getMessage(), 500);
        }
    }
    public function fetchAdminById(Request $request)
    {
        try {
            $user = User::with(['roles'])->where('id', $request->id)->first();
            if (!$user) {
                return FormatResponseJson::error(null, 'User tidak ditemukan', 404);
            }
            $roles = $user->getRoleNames();
            return FormatResponseJson::success($user, 'Data user berhasil diambil');
        } catch (\Throwable $th) {
            return FormatResponseJson::error(null, $th->getMessage(), 500);
        }
    }
    public function update(Request $request)
    {
        try {
            //code...
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
    public function exportExcel()
    {
        return Excel::download(new ExportUser(), 'list_user.xlsx');
    }
}
