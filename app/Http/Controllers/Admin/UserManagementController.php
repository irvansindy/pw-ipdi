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

class UserManagementController extends Controller
{
    function index() {
        $users = User::all();
        return view("pages.admin.users.index", compact("users"));
    }
}
