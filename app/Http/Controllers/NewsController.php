<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\News;
use App\Helpers\FormatResponseJson;

class NewsController extends Controller
{
    public function index()
    {
        // Ambil berita utama (terbaru)
        $mainNews = News::with(['author:id,username', 'event.category'])
            ->where('status', 'published')
            ->orderBy('published_at', 'desc')
            ->first();

        // Ambil berita sidebar (4 berita terbaru setelah berita utama)
        $sidebarNews = News::with(['author:id,username', 'event.category'])
            ->where('status', 'published')
            ->where('id', '!=', $mainNews?->id)
            ->orderBy('published_at', 'desc')
            ->limit(4)
            ->get();

        // Ambil berita terkini (untuk carousel, 6 berita)
        $latestNews = News::with(['author:id,username', 'event.category'])
            ->where('status', 'published')
            ->orderBy('published_at', 'desc')
            ->limit(6)
            ->get();

        return view('pages.user.news.index', compact('mainNews', 'sidebarNews', 'latestNews'));
    }

    public function fetchNews(Request $request)
    {
        try {
            $news = News::with(['author:id,username', 'event.category'])
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
            $news = News::with(['author:id,username', 'event.category'])
                ->where('status', 'published')
                ->findOrFail($id);

            return view('pages.user.news.show', compact('news'));
        } catch (\Throwable $th) {
            return redirect()->route('news.index')->with('error', 'Berita tidak ditemukan');
        }
    }
}
