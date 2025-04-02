<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\News;
use App\Models\User;
use App\Models\Events;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;
use App\Helpers\FormatResponseJson;
class NewsController extends Controller
{
    public function index()
    {
        return view('pages.admin.news.index');
    }
    public function fetch()
    {
        try {
            $news = News::orderBy('created_at','desc')->get();
            return FormatResponseJson::success( $news, 'news fetch successfully');
        } catch (\Throwable $th) {
            return FormatResponseJson::error(null, $th->getMessage(), 404);
        }
    }
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'news_title'=> 'required|string',
                'news_content'=> 'required|string',
                'news_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
                'news_category'=> 'required',
                'news_status' => 'required',
                'news_published_at' => 'required|date',
            ]);
            if ($request->filled('news_source_url')) {
                $validator->sometimes('news_source_url', 'url:http,https', function ($input) {
                    return !empty($input->news_source_url);
                });
            }
            if ($validator->fails()) {
                throw new ValidationException($validator);
            }
            // $newsData = [];
            DB::transaction(function () use ($request) {
                $newsData = [
                    'event_id' => null,
                    'title' => $request->news_title,
                    'content' => $request->news_content,
                    'image' => null,
                    'category_id' => $request->news_category == null ? null : $request->news_category,
                    'author_id' => \Auth::user()->id,
                    'source_url' => $request->news_source_url,
                    'status' => $request->news_status,
                    'published_at' => $request->news_published_at,
                ];
                if ($request->hasFile('news_image')) {
                    $filename = Str::slug($request->news_title) . '.' . $request->file('news_image')->getClientOriginalExtension();
                    // dd($filename);
                    $newsData['image'] = $request->file('news_image')->storeAs('news_images', $filename, 'public');
                }
                News::create($newsData);
                // return FormatResponseJson::success($newsData, 'News created successfully');
            });
            return FormatResponseJson::success(true, 'News created successfully');
        } catch (ValidationException $e) {
            DB::rollback();
            return FormatResponseJson::error(null, ['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            DB::rollback();
            return FormatResponseJson::error(null, $e->getMessage(), 500);
        }
    }
    public function show(Request $request)
    {
        try {
            $news = News::findOrFail($request->id);
            return FormatResponseJson::success($news,'news fetched successfully');
        } catch (\Throwable $th) {
            return FormatResponseJson::error(null, $th->getMessage(),500);
        }
    }
    public function update(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'news_title'=> 'required|string',
                'news_content'=> 'required|string',
                'news_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
                'news_category'=> 'required',
                'news_status' => 'required',
                'news_published_at' => 'required|date',
            ]);
            if ($request->filled('news_source_url')) {
                $validator->sometimes('news_source_url', 'url:http,https', function ($input) {
                    return !empty($input->news_source_url);
                });
            }
            if ($validator->fails()) {
                throw new ValidationException($validator);
            }
            DB::transaction(function () use ($request) {
                $news = News::findOrFail($request->news_id);
                $newsData = [
                    'event_id' => null,
                    'title' => $request->news_title,
                    'content' => $request->news_content,
                    'image' => null,
                    'category_id' => $request->news_category == null ? null : $request->news_category,
                    'author_id' => \Auth::user()->id,
                    'source_url' => $request->news_source_url,
                    'status' => $request->news_status,
                    'published_at' => $request->news_published_at,
                ];
                if ($request->hasFile('news_image')) {
                    if ($news->news_image) {
                        Storage::disk('public')->delete($news->news_image);
                    }
                    $filename = Str::slug($request->news_title) . '.' . $request->file('news_image')->getClientOriginalExtension();
                    $newsData['image'] = $request->file('news_image')->storeAs('news_images', $filename, 'public');
                }
                $news->update($newsData);
                // return FormatResponseJson::success($newsData, 'News updated successfully');
            });
            return FormatResponseJson::success(true, 'News updated successfully');
        } catch (ValidationException $e) {
            DB::rollback();
            return FormatResponseJson::error(null, ['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            DB::rollback();
            return FormatResponseJson::error(null, $e->getMessage(), 500);
        }
    }
}
