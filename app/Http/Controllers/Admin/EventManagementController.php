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
        return view("pages.admin.events.index");
    }

    public function fetch()
    {
        try {
            $events = Events::orderBy("created_at","desc")->limit(100)->get();
            return FormatResponseJson::success($events, 'Event fetched successfully');
        } catch (\Throwable $th) {
            return FormatResponseJson::error(null, $th->getMessage(), 400);
        }

    }
    public function fetchCategory()
    {
        try {
            $categories = CategoriesEvent::all();
            return FormatResponseJson::success($categories, 'Event fetched successfully');
        } catch (\Throwable $th) {
            return FormatResponseJson::error(null, $th->getMessage(), 400);
        }

    }
    

    public function store(Request $request)
    {
        try {
            // dd($request);
            $this->validate($request, [
                "name"=> "required|string",
                "title"=> "required|string",
                "category"=> "required|string",
                "price"=> "required|string",
                "description"=> "required|string",
                "quota"=> "required|integer",
                "date"=> "required|string",
            ]);

            $data = [
                "name"=> $request->name,
                "title"=> $request->title,
                "category_event_id"=> $request->category,
                "price"=> $request->price,
                "description"=> $request->description,
                "quota"=> $request->quota,
                "date"=> $request->date,
            ];

            $event = Events::create($data);
            return FormatResponseJson::success($event, 'Event created successfully');
        } catch (\Throwable $th) {
            return FormatResponseJson::error(null, $th->getMessage(), 400);
        }
    }

    public function detail(Request $request)
    {
        try {
            $event_id = $request->id;
            $event = Events::with('category')->findOrFail($event_id);
            $categories = CategoriesEvent::all();
            $data = [
                $event,
                $categories,
            ];
            return FormatResponseJson::success($data,'Event fetched successfully');
        } catch (\Throwable $th) {
            return FormatResponseJson::error(null, $th->getMessage(), 404);
        }
    }

    public function update(Request $request)
    {
        try {
            $current_event = Events::find($request->id);

            $this->validate($request, [
                "name"=> "required|string",
                "title"=> "required|string",
                "category"=> "required|string",
                "price"=> "required|string",
                "description"=> "required|string",
                "quota"=> "required|integer",
                "date"=> "required|string"
            ]);

            $current_event->update([
                "name"=> $request->name,
                "title"=> $request->title,
                "category_event_id"=> $request->category,
                "price"=> $request->price,
                "description"=> $request->description,
                "quota"=> $request->quota,
                "date"=> $request->date
            ]);
            return FormatResponseJson::success($current_event,'Event updated successfully');
        } catch (\Throwable $th) {
            return FormatResponseJson::error(null, $th->getMessage(), 404);
        }
    }

}
