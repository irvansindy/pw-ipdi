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
            $user = User::with('roles')->where('id', $request->id)->first();
            if (!$user) {
                return FormatResponseJson::error(null, 'User tidak ditemukan', 404);
            }

            $userData = $user->toArray();
            $userData['role_ids'] = $user->roles->pluck('id')->toArray();
            $userData['role_names'] = $user->roles->pluck('name')->toArray();

            return FormatResponseJson::success($userData, 'Data user berhasil diambil');
        } catch (\Throwable $th) {
            return FormatResponseJson::error(null, $th->getMessage(), 500);
        }
    }

    public function destroy(Request $request)
    {
        try {
            $request->validate([
                'user_id' => 'required|exists:users,id',
            ]);

            $user = User::findOrFail($request->user_id);
            $user->delete();

            return FormatResponseJson::success(null, 'User berhasil dihapus');
        } catch (\Illuminate\Validation\ValidationException $th) {
            return FormatResponseJson::error(null, $th->errors(), 422);
        } catch (\Throwable $th) {
            return FormatResponseJson::error(null, $th->getMessage(), 500);
        }
    }

    public function updateRole(Request $request)
    {
        try {
            $request->validate([
                'user_id' => 'required|exists:users,id',
                'roles' => 'nullable|array',
                'roles.*' => 'exists:roles,id',
            ]);

            $user = User::findOrFail($request->user_id);
            $roleNames = Role::whereIn('id', $request->roles ?? [])->pluck('name')->toArray();
            $user->syncRoles($roleNames);

            return FormatResponseJson::success($user, 'Role user berhasil diupdate');
        } catch (\Illuminate\Validation\ValidationException $th) {
            return FormatResponseJson::error(null, $th->errors(), 422);
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
            $request->validate([
                'user_id' => 'required|exists:users,id',
                'username' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'nik' => 'nullable|string|max:20',
                'nira_ppni' => 'nullable|string|max:20',
                'nira_ipdi' => 'nullable|string|max:20',
                'pw_ipdi_provinsi' => 'nullable|string',
                'nama_perusahaan' => 'nullable|string|max:255',
                'center_dialisis' => 'nullable|string',
                'number_phone' => 'nullable|string|max:20',
                'alamat_center_dialisis_perusahaan' => 'nullable|string',
                'last_edication' => 'nullable|string|max:100',
            ]);

            $user = User::findOrFail($request->user_id);

            $user->update([
                'username' => $request->username,
                'email' => $request->email,
                'nik' => $request->nik,
                'nira_ppni' => $request->nira_ppni,
                'nira_ipdi' => $request->nira_ipdi,
                'pw_ipdi_provinsi' => $request->pw_ipdi_provinsi,
                'nama_perusahaan' => $request->nama_perusahaan,
                'center_dialisis' => $request->center_dialisis,
                'number_phone' => $request->number_phone,
                'alamat_center_dialisis_perusahaan' => $request->alamat_center_dialisis_perusahaan,
                'last_edication' => $request->last_edication,
            ]);

            return FormatResponseJson::success($user, 'User berhasil diupdate');
        } catch (\Illuminate\Validation\ValidationException $th) {
            return FormatResponseJson::error(null, $th->errors(), 422);
        } catch (\Throwable $th) {
            return FormatResponseJson::error(null, $th->getMessage(), 500);
        }
    }
    public function exportExcel()
    {
        return Excel::download(new ExportUser(), 'list_user.xlsx');
    }
}
