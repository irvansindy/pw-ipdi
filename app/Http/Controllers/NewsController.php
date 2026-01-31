<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\News;
use App\Helpers\FormatResponseJson;

class NewsController extends Controller
{
    public function index()
    {
        // Ambil berita utama (terbaru) dengan foto pertama
        $mainNews = News::with([
                'author:id,username',
                'event.category',
                'images' => function($query) {
                    $query->orderBy('order', 'asc')->limit(1);
                }
            ])
            ->where('status', 'published')
            ->orderBy('published_at', 'desc')
            ->first();
        // Ambil berita sidebar (4 berita terbaru setelah berita utama)
        $sidebarNews = News::with([
                'author:id,username',
                'event.category',
                'images' => function($query) {
                    $query->orderBy('order', 'asc')->limit(1);
                }
            ])
            ->where('status', 'published')
            ->where('id', '!=', $mainNews?->id)
            ->orderBy('published_at', 'desc')
            ->limit(4)
            ->get();

        // Ambil berita terkini (untuk grid, 6 berita)
        $latestNews = News::with([
                'author:id,username',
                'event.category',
                'images' => function($query) {
                    $query->orderBy('order', 'asc')->limit(1);
                }
            ])
            ->where('status', 'published')
            ->orderBy('published_at', 'desc')
            ->limit(6)
            ->get();

        return view('pages.user.news.index', compact('mainNews', 'sidebarNews', 'latestNews'));
    }

    public function fetchNews(Request $request)
    {
        try {
            $news = News::with([
                    'author:id,username',
                    'event.category',
                    'images' => function($query) {
                        $query->orderBy('order', 'asc')->limit(1);
                    }
                ])
                ->where('status', 'published')
                ->orderBy('published_at', 'desc')
                ->get();

            return FormatResponseJson::success($news, 'Berita berhasil diambil');
        } catch (\Throwable $th) {
            return FormatResponseJson::error(null, 'Gagal mengambil berita: ' . $th->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $news = News::with([
                    'author:id,username',
                    'event.category',
                    'images' => function($query) {
                        $query->orderBy('order', 'asc');
                    }
                ])
                ->where('status', 'published')
                ->findOrFail($id);

            // Ambil berita terkait (3 berita terbaru, exclude berita saat ini)
            $relatedNews = News::with([
                    'author:id,username',
                    'images' => function($query) {
                        $query->orderBy('order', 'asc')->limit(1);
                    }
                ])
                ->where('status', 'published')
                ->where('id', '!=', $id)
                ->when($news->event_id, function($query) use ($news) {
                    // Prioritaskan berita dengan event yang sama
                    $query->where('event_id', $news->event_id);
                })
                ->orderBy('published_at', 'desc')
                ->limit(3)
                ->get();

            return view('pages.user.news.show', compact('news', 'relatedNews'));
        } catch (\Throwable $th) {
            return redirect()->route('news.index')->with('error', 'Berita tidak ditemukan');
        }
    }
}