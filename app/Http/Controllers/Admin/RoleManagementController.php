<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use DB;
use Illuminate\View\View;
use App\Helpers\FormatResponseJson;
class RoleManagementController extends Controller
{
    public function index(Request $request): View
    {
        $roles = Role::orderBy('id','DESC')->paginate(5);
        return view('pages.admin.roles.index',compact('roles'))
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }
    public function fetch()
    {
        try {
            $roles = Role::orderBy('id', 'DESC')->get();
            return FormatResponseJson::success($roles, 'Roles fetched successfully');
        } catch (\Throwable $th) {
            return FormatResponseJson::error($th->getMessage());
        }
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $this->validate($request, [
                'name' => 'required|unique:roles,name',
                // 'permission' => 'required',
            ]);
            $role = Role::create(['name' => $request->input('name')]);
            $role->syncPermissions($request->input('permission'));
            return FormatResponseJson::success($role, 'Role created successfully');
        } catch (\Throwable $th) {
            return FormatResponseJson::error($th->getMessage());
        }
    }
}
