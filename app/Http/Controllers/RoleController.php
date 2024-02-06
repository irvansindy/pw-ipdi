<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use DB;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Helpers\FormatResponseJson;

class RoleController extends Controller
{
    public function index(Request $request): View
    {
        $roles = Role::orderBy('id','DESC')->paginate(5);
        return view('pages.roles.index',compact('roles'))
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // print_r('abc');
        try {
            // dd($request->all());
            $this->validate($request, [
                'name' => 'required|unique:roles,name',
                // 'permission' => 'required',
            ]);
            $role = Role::create(['name' => $request->input('name')]);
            $role->syncPermissions($request->input('permission'));
            // return redirect()->route('roles.index')
            //     ->with('success','Role created successfully');
            return FormatResponseJson::success($role, 'Role created successfully');
        } catch (\Throwable $th) {
            return FormatResponseJson::error($th->getMessage());
        }
    }
}
