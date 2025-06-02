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
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
            ]);
            $user->assignRole('admin');
            return FormatResponseJson::success($user, 'User admin berhasil dibuat');
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
