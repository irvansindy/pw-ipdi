<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Helpers\FormatResponseJson;
use Illuminate\Support\Facades\Auth;
use App\Models\Events;
use App\Models\CategoriesEvent;
use App\Models\EventDetail;
use App\Models\EventHotel;
use App\Models\Hotel;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;
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
    public function fetchHotel()
    {
        try {
            $hotel = Hotel::all();
            return FormatResponseJson::success($hotel, 'Event fetched successfully');
        } catch (\Throwable $th) {
            return FormatResponseJson::error(null, $th->getMessage(), 400);
        }
    }
    public function store(Request $request)
    {
        try {
            // dd($request->price);
            DB::beginTransaction();
            $validator = Validator::make($request->all(), [
                "name"=> "required|string",
                "title"=> "required|string",
                "category"=> "required|string",
                "price"=> "required|string",
                "description"=> "required|string",
                "quota"=> "required|integer",
                "date"=> "required|string",
                "image"=> "mimes:png,jpg",
            ]);
            if ($validator->fails()) {
                throw new ValidationException($validator);
            }
            $file = $request->file('image');
            $file_name = time().'.'.$file->getClientOriginalExtension();
            $file->move(public_path('img/logos/IPDI/event/'), $file_name);
            $date  = date("Y-m-d ", strtotime($request->last_register));
            $data = [
                "name"=> $request->name,
                "title"=> $request->title,
                "category_event_id"=> $request->category,
                "price"=> $request->price == 'undefined' ? 0 : $request->price,
                "last_register"=> $date,
                "description"=> $request->description,
                "quota"=> $request->quota,
                "date"=> $request->date,
                "date_end"=> $request->date,
                "image"=> $file_name,
            ];
            $event = Events::create($data);
            
            if (count($request->detail_event_name) > 0) {
                $data_event_detail = [];
                $list_event_detail = [];
                for ($i=0; $i < count($request->detail_event_name); $i++) { 
                    $list_event_detail = [
                        'event_id' => $event->id,
                        'name' => $request->detail_event_name[$i],
                        'price' => $request->detail_event_price[$i],
                        // 'location' => 'Jakarta'
                    ];

                    array_push($data_event_detail, $list_event_detail);
                }
                $create_event_detail = EventDetail::insert($data_event_detail);
            }

            if($request->hotel != null && $request->harga_hotel != null) {
                $event_hotel = EventHotel::create([
                    'event_id' => $event->id,
                    'name' => $request->hotel,
                    'price' => $request->harga_hotel,
                ]);
            }

            DB::commit();
            return FormatResponseJson::success($event, 'Event created successfully');
        }  catch (ValidationException $e) {
            // Return validation errors as JSON response
            DB::rollback();
            return FormatResponseJson::error(null, $e->errors(), 422);
        } catch (\Exception $e) {
            DB::rollback();
            return FormatResponseJson::error(null, $e->getMessage(), 500);
        }
    }
    public function detail(Request $request)
    {
        try {
            $event_id = $request->id;
            $event = Events::with(['category', 'hotel'])->findOrFail($event_id);
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
            // dd($request->all());
            $this->validate($request, [
                "name"=> "required|string",
                "title"=> "required|string",
                "category"=> "required|string",
                "price"=> "required|string",
                "description"=> "required|string",
                "quota"=> "required|integer",
                "date"=> "required|string",
                "date_end"=> "required|string",
            ]);

            $current_event->update([
                "name"=> $request->name,
                "title"=> $request->title,
                "category_event_id"=> $request->category,
                "price"=> $request->price,
                "description"=> $request->description,
                "quota"=> $request->quota,
                "date"=> $request->date,
                "date_end"=> $request->date_end,
                "is_active" => $request->is_active,
            ]);
            return FormatResponseJson::success($current_event,'Event updated successfully');
        } catch (\Throwable $th) {
            return FormatResponseJson::error(null, $th->getMessage(), 404);
        }
    }
    public function indexPeserta()
    {
        return view('');
    }
}
