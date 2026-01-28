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
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use App\Helpers\FormatResponseJson;
use App\Models\NewsImage;
class NewsController extends Controller
{
    protected $uploadPath = 'public/uploads/news';

    public function __construct()
    {
        if (!File::exists($this->uploadPath)) {
            File::makeDirectory($this->uploadPath, 0755, true);
        }
    }

    public function index()
    {
        return view('pages.admin.news.index');
    }

    private function saveNewsImage($imageFile, $newsTitle)
    {
        if (!$imageFile) {
            return null;
        }

        $filename = Str::slug($newsTitle) . '.' . $imageFile->getClientOriginalExtension();
        $imageFile->move(public_path('uploads/news'), $filename);

        return 'uploads/news/' . $filename;
    }

    private function deleteNewsImage($imagePath)
    {
        if ($imagePath && File::exists(public_path($imagePath))) {
            File::delete(public_path($imagePath));
        }
    }

    private function saveMultipleNewsImages($imageFiles, $newsId)
    {
        if (!$imageFiles) {
            return [];
        }

        $savedImages = [];
        $order = 0;

        foreach ($imageFiles as $file) {
            // Ambil info SEBELUM move file
            $originalName = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $fileSize = round($file->getSize() / 1024, 2); // KB
            $mimeType = $file->getClientMimeType();

            // Generate unique filename
            $fileName = time() . '_' . uniqid() . '.' . $extension;

            // Move file to public directory
            $file->move(public_path('uploads/news'), $fileName);

            // Save to database
            $newsImage = NewsImage::create([
                'news_id' => $newsId,
                'file_name' => $originalName,
                'file_path' => 'uploads/news/' . $fileName,
                'file_type' => 'news_image',
                'file_size' => $fileSize,
                'mime_type' => $mimeType,
                'order' => $order
            ]);

            $savedImages[] = $newsImage;
            $order++;
        }

        return $savedImages;
    }

    private function deleteMultipleNewsImages($newsId)
    {
        $images = NewsImage::where('news_id', $newsId)->get();
        foreach ($images as $image) {
            if ($image->file_path && File::exists(public_path($image->file_path))) {
                File::delete(public_path($image->file_path));
            }
            $image->delete();
        }
    }

    public function uploadNewsImages(Request $request)
    {
        try {
            DB::beginTransaction();

            // Validasi input
            $request->validate([
                'id' => 'required|exists:news,id',
                'files.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120',
            ], [
                'files.*.required' => 'File harus diupload',
                'files.*.image' => 'File harus berupa gambar',
                'files.*.mimes' => 'File harus berformat: jpeg, png, jpg, atau gif',
                'files.*.max' => 'Ukuran file maksimal 5MB',
            ]);

            $news_id = $request->id;

            // Cek apakah news ada
            $news = News::findOrFail($news_id);

            // Cek jumlah existing images
            $existingImageCount = NewsImage::where('news_id', $news_id)->count();
            $newFileCount = count($request->file('files'));
            $totalImages = $existingImageCount + $newFileCount;

            if ($totalImages > 5) {
                DB::rollBack();
                return FormatResponseJson::error(null, 'Total gambar tidak boleh lebih dari 5. Saat ini: ' . $existingImageCount . ' gambar', 400);
            }

            $uploadedFiles = [];
            $files = $request->file('files');
            $order = $existingImageCount;

            // Loop untuk upload multiple files
            foreach ($files as $file) {
                // Ambil info SEBELUM move file
                $originalName = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                $fileSize = round($file->getSize() / 1024, 2); // KB
                $mimeType = $file->getClientMimeType();

                // Generate unique filename
                $fileName = time() . '_' . uniqid() . '.' . $extension;

                // Move file to public directory
                $file->move(public_path('uploads/news'), $fileName);

                // Save to database
                $newsImage = NewsImage::create([
                    'news_id' => $news_id,
                    'file_name' => $originalName,
                    'file_path' => 'uploads/news/' . $fileName,
                    'file_type' => 'news_image',
                    'file_size' => $fileSize,
                    'mime_type' => $mimeType,
                    'order' => $order
                ]);

                $uploadedFiles[] = $newsImage;
                $order++;
            }

            DB::commit();

            return FormatResponseJson::success([
                'news' => $news,
                'uploaded_files' => $uploadedFiles
            ], 'Berhasil mengupload ' . count($uploadedFiles) . ' gambar');

        } catch (ValidationException $e) {
            DB::rollBack();
            return FormatResponseJson::error($e->errors(), 'Validasi gagal', 422);
        } catch (\Exception $e) {
            DB::rollBack();
            return FormatResponseJson::error(null, 'Gagal melakukan upload gambar: ' . $e->getMessage(), 500);
        }
    }

    public function deleteNewsImageFile(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'image_id' => 'required|exists:news_images,id',
            ]);

            if ($validator->fails()) {
                throw new ValidationException($validator);
            }

            DB::transaction(function () use ($request) {
                $image = NewsImage::findOrFail($request->image_id);

                // Delete file from storage
                if ($image->file_path && File::exists(public_path($image->file_path))) {
                    File::delete(public_path($image->file_path));
                }

                $image->delete();
            });

            return FormatResponseJson::success(true, 'Gambar berhasil dihapus');
        } catch (ValidationException $e) {
            DB::rollback();
            return FormatResponseJson::error(null, ['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            DB::rollback();
            return FormatResponseJson::error(null, $e->getMessage(), 500);
        }
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

            $news = null;
            DB::transaction(function () use ($request, &$news) {
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

                $news = News::create($newsData);
            });
            return FormatResponseJson::success($news, 'News created successfully');
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
            $news = News::with('images')->findOrFail($request->id);
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
                    'image' => $news->image,
                    'category_id' => $request->news_category == null ? null : $request->news_category,
                    'author_id' => \Auth::user()->id,
                    'source_url' => $request->news_source_url,
                    'status' => $request->news_status,
                    'published_at' => $request->news_published_at,
                ];
                if ($request->hasFile('news_image')) {
                    $this->deleteNewsImage($news->image);
                    $newsData['image'] = $this->saveNewsImage($request->file('news_image'), $request->news_title);
                }
                $news->update($newsData);
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

    public function destroy(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'news_id' => 'required|exists:news,id',
            ]);
            if ($validator->fails()) {
                throw new ValidationException($validator);
            }
            DB::transaction(function () use ($request) {
                $news = News::findOrFail($request->news_id);

                // Delete old single image if exists (backward compatibility)
                if ($news->image) {
                    $this->deleteNewsImage($news->image);
                }

                // Delete multiple images
                $this->deleteMultipleNewsImages($news->id);

                $news->delete();
            });
            return FormatResponseJson::success(true, 'News deleted successfully');
        } catch (ValidationException $e) {
            DB::rollback();
            return FormatResponseJson::error(null, ['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            DB::rollback();
            return FormatResponseJson::error(null, $e->getMessage(), 500);
        }
    }
}
