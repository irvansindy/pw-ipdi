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
        DB::beginTransaction();

        try {
            $validator = Validator::make($request->all(), [
                "create_event_name" => "required|string|max:255",
                "create_event_title" => "required|string|max:255",
                "create_event_category" => "required|integer|exists:categories_events,id",
                "create_event_price" => "nullable|numeric|min:0",
                "create_event_description" => "required|string",
                "create_event_quota" => "required|integer|min:1",
                "create_event_date" => "required|date",
                "create_event_start_date" => "required|date",
                "create_event_end_date" => "required|date|after_or_equal:start_date",
                // "create_event_date_last_register" => "required|date|before_or_equal:start_date",
                "create_event_date_last_register" => "required|date",
                "create_event_max_attendance" => "nullable|integer|min:1",
                "create_event_image" => "nullable|image|mimes:png,jpg,jpeg|max:2048",
                "create_event_detail_event_name" => "nullable|array",
                "create_event_detail_event_price" => "nullable|array",
                "create_event_hotel" => "nullable|string|max:255",
                "create_event_harga_hotel" => "nullable|min:0",
            ]);

            if ($validator->fails()) {
                throw new ValidationException($validator);
            }

            // Handle image upload
            $filename = null;
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $filename = time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('img/logos/IPDI/event/'), $filename);
            }

            $eventData = [
                "name" => $request->create_event_name,
                "title" => $request->create_event_title,
                "category_event_id" => $request->create_event_category,
                "price" => $request->create_event_price ?? 0,
                "last_register" => $request->create_event_date_last_register,
                "description" => $request->create_event_description,
                "quota" => $request->create_event_quota,
                "date" => $request->create_event_date,
                "start_date" => $request->create_event_start_date,
                "end_date" => $request->create_event_end_date,
                "max_attendance" => $request->create_event_max_attendance,
                "image" => $filename,
                "is_active" => 1,
            ];

            $event = Events::create($eventData);

            // Create event details if provided
            if ($request->has('detail_event_name') && is_array($request->detail_event_name)) {
                $eventDetails = [];
                foreach ($request->detail_event_name as $index => $name) {
                    if (!empty($name) && isset($request->detail_event_price[$index])) {
                        $eventDetails[] = [
                            'event_id' => $event->id,
                            'name' => $name,
                            'price' => $request->detail_event_price[$index],
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }
                }
                if (!empty($eventDetails)) {
                    EventDetail::insert($eventDetails);
                }
            }

            // Create event hotel if provided
            if ($request->filled('hotel') && $request->filled('harga_hotel')) {
                EventHotel::create([
                    'event_id' => $event->id,
                    'name' => $request->hotel,
                    'price' => $request->harga_hotel,
                ]);
            }

            DB::commit();
            return FormatResponseJson::success($event, 'Event created successfully');
        } catch (ValidationException $e) {
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
            $validator = Validator::make($request->all(), [
                'id' => 'required|integer|exists:events,id'
            ]);

            if ($validator->fails()) {
                throw new ValidationException($validator);
            }

            $event = Events::with(['eventDetail', 'category', 'hotel'])
                ->findOrFail($request->id);
            $categories = CategoriesEvent::all();

            return FormatResponseJson::success([
                'event' => $event,
                'categories' => $categories,
            ], 'Event fetched successfully');
        } catch (ValidationException $e) {
            return FormatResponseJson::error(null, $e->errors(), 422);
        } catch (\Throwable $th) {
            return FormatResponseJson::error(null, $th->getMessage(), 404);
        }
    }
    public function updateOld(Request $request)
    {
        // DEPRECATED: Use update() method instead
        return FormatResponseJson::error(null, 'This endpoint is deprecated', 410);
    }

    public function update(Request $request)
    {
        DB::beginTransaction();

        try {
            $validator = Validator::make($request->all(), [
                "id" => "required|integer|exists:events,id",
                "name" => "required|string|max:255",
                "title" => "required|string|max:255",
                "category" => "required|integer|exists:categories_events,id",
                "price" => "nullable|min:0",
                "description" => "required|string",
                "quota" => "required|integer|min:1",
                "date" => "required|date",
                "start_date" => "required|date",
                "end_date" => "required|date|after_or_equal:start_date",
                "last_register" => "required|date|before_or_equal:start_date",
                "max_attendance" => "nullable|integer|min:1",
                "image" => "nullable|image|mimes:png,jpg,jpeg|max:2048",
                "is_active" => "nullable|boolean",
                "detail_name" => "nullable|array",
                "detail_price" => "nullable|array",
                "update_event_hotel" => "nullable|string|max:255",
                "update_event_harga_hotel" => "nullable|numeric|min:0",
            ]);

            if ($validator->fails()) {
                throw new ValidationException($validator);
            }

            $event = Events::findOrFail($request->id);

            // Handle image upload
            if ($request->hasFile('image')) {
                // Delete old image
                if ($event->image && file_exists(public_path('img/logos/IPDI/event/' . $event->image))) {
                    unlink(public_path('img/logos/IPDI/event/' . $event->image));
                }

                $file = $request->file('image');
                $filename = time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('img/logos/IPDI/event/'), $filename);
                $event->image = $filename;
            }

            // Update event data
            $event->update([
                "name" => $request->name,
                "title" => $request->title,
                "category_event_id" => $request->category,
                "price" => $request->price ?? 0,
                "description" => $request->description,
                "quota" => $request->quota,
                "date" => $request->date,
                "start_date" => $request->start_date,
                "end_date" => $request->end_date,
                "max_attendance" => $request->max_attendance,
                "last_register" => $request->last_register,
                "is_active" => $request->is_active ? 1 : 0,
                "image" => $event->image,
            ]);

            // Update event details
$detailNames  = $request->input('update_event_detail_name', []);
$detailPrices = $request->input('update_event_detail_price', []);

if (is_array($detailNames) && !empty($detailNames)) {

    // Get existing details keyed by name
    $existingDetails = EventDetail::where('event_id', $event->id)
        ->get()
        ->keyBy('name')
        ->toArray();

    $usedNames = [];

    foreach ($detailNames as $index => $name) {
        if (empty($name)) continue;

        // =========================
        // NORMALISASI PRICE
        // =========================
        $rawPrice = $detailPrices[$index] ?? 0;

        // Hapus koma, spasi, simbol lain
        $price = (int) preg_replace('/[^0-9]/', '', $rawPrice);

        $usedNames[] = $name;

        if (isset($existingDetails[$name])) {
            // Update existing detail
            EventDetail::where('event_id', $event->id)
                ->where('name', $name)
                ->update([
                    'price' => $price
                ]);
        } else {
            // Create new detail
            EventDetail::create([
                'event_id' => $event->id,
                'name'     => $name,
                'price'    => $price,
            ]);
        }
    }

    // Delete details that are not in the request
    EventDetail::where('event_id', $event->id)
        ->whereNotIn('name', $usedNames)
        ->delete();

} else {
    // If no details provided, delete all
    EventDetail::where('event_id', $event->id)->delete();
}


            // Update or delete hotel
            if ($request->filled('hotel') && $request->filled('harga_hotel')) {
                $hotel = EventHotel::where('event_id', $event->id)->first();
                if ($hotel) {
                    $hotel->update([
                        'name' => $request->hotel,
                        'price' => $request->harga_hotel,
                    ]);
                } else {
                    EventHotel::create([
                        'event_id' => $event->id,
                        'name' => $request->hotel,
                        'price' => $request->harga_hotel,
                    ]);
                }
            } else {
                // Delete hotel if no data provided
                EventHotel::where('event_id', $event->id)->delete();
            }

            DB::commit();
            return FormatResponseJson::success($event, 'Event updated successfully');
        } catch (ValidationException $e) {
            DB::rollback();
            return FormatResponseJson::error(null, $e->errors(), 422);
        } catch (\Exception $e) {
            DB::rollback();
            return FormatResponseJson::error(null, $e->getMessage(), 500);
        }
    }
    public function indexPeserta()
    {
        return view('');
    }
}
