<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Helpers\FormatResponseJson;
use Illuminate\Support\Facades\Auth;
use App\Models\Events;
use App\Models\CategoriesEvent;

class EventManagementController extends Controller
{
    public function index()
    {
        $events = Events::orderBy("created_at","desc")->get();
        $categories = CategoriesEvent::all();
        return view("pages.admin.events.index", compact("events", "categories"));
    }
}
