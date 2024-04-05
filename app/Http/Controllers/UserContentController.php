<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\FormatResponseJson;
use App\Models\Events;
use App\Models\CategoriesEvent;

class UserContentController extends Controller
{
    // public $search;

    // public function __construct($channelName, $message)
    // {
    //     $this->channelName = $channelName;
    //     $this->message = $message;
    // }
    public function index()
    {
        return view('pages.user.home');
    }
    public function fetchContent()
    {
        try {
                $event_jnns = Events::with('category')->whereHas('category', function($q) {
                    $q->where('name','like','%JNNS%');
                })
                ->orderBy('date', 'desc')
                ->limit(1)
                ->get();

            $event_seminar = Events::with('category')->whereHas('category', function($q) {
                $q->where('name','like','%Seminar%');
            })
            ->orderBy('date', 'desc')
            ->limit(1)
            ->get();

            $event_resertifikasi = Events::with('category')->whereHas('category', function($q) {
                $q->where('name','like','%Resertifikasi%');
            })
            ->orderBy('date', 'desc')
            ->limit(1)
            ->get();

            $event_wkd = Events::with('category')->whereHas('category', function($q) {
                $q->where('name','like','%WKD%');
            })
            ->orderBy('date', 'desc')
            ->limit(1)
            ->get();

            $event_kongres_wilayah = Events::with('category')->whereHas('category', function($q) {
                $q->where('name','like','%Kongres Wilayah%');
            })
            ->orderBy('date', 'desc')
            ->limit(1)
            ->get();

            $data_event = [
                'jnns'=> $event_jnns,
                'seminar'=> $event_seminar,
                'resertifikasi'=> $event_resertifikasi,
                'wkd'=> $event_wkd,
                'kongres'=> $event_kongres_wilayah
            ];
            return FormatResponseJson::success($data_event, 'Event fetched successfully');
        } catch (\Exception $e) {
            return FormatResponseJson::error(null, $e->getMessage(), 400);
        }
    }
    public function viewEvents()
    {
        return view('pages.user.event.events');
    }
    public function fetchCategoriesEvent()
    {
        try {
            $categories = CategoriesEvent::all();
            return FormatResponseJson::success($categories,'Categories event fetched successfully');
        } catch (\Exception $e) {
            return FormatResponseJson::error(null, $e->getMessage(), 400);
        }
    }
    public function fetchAllEvent(Request $request)
    {
        try {
            $search = $request->input('search');
            $filter = $request->input('filter');

            $events_query = Events::with('category');
            
            if ($search != null && $filter != null) {
                $events_query->whereHas('category', function($q) use ($filter) {
                    $q->where('name','=',$filter);
                })
                ->where(function($query) use ($search) {
                    $query->where('name','like','%'.$search.'%')
                        ->orWhere('title','like','%'.$search.'%')
                        ->orWhere('price','like','%'.$search.'%')
                        ->orWhere('description','like','%'.$search.'%')
                        ->orWhere('quota','like','%'.$search.'%');
                });
            } else {
                if ($search != null) {
                    $events_query->whereHas('category', function($q) use ($search) {
                        $q->where('name', 'like', '%'. $search .'%');
                    })
                    ->orWhere('name','like','%'.$search.'%')
                    ->orWhere('title','like','%'.$search.'%')
                    ->orWhere('price','like','%'.$search.'%')
                    ->orWhere('description','like','%'.$search.'%')
                    ->orWhere('quota','like','%'.$search.'%');
                } else if ($filter != null) {
                    $events_query->whereHas('category', function($q) use ($filter) {
                        $q->where('name','=',$filter);
                    });
                }
            }

            $events = $events_query->orderBy('date', 'desc')->limit(100)->get();

            return FormatResponseJson::success($events,'Event fetched successfully');
        } catch (\Exception $e) {
            return FormatResponseJson::error(null, $e->getMessage(), 400);
        }
    }
    public function history() {
        try {
            //code...
        } catch (\Exception $e) {
            //throw $th;
        }
    }
    public function visionAndMision()
    {
        try {
            //code...
        } catch (\Exception $e) {
            //throw $th;
        }
    }

}
