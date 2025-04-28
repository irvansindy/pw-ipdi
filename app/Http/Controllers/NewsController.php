<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\News;
use App\Helpers\FormatResponseJson;
class NewsController extends Controller
{
    public function index()
    {
        return view('pages.user.news.index');
    }
    public function fetchNews(Request $request)
    {
        try {
            $news = News::with(['author:id,username', 'event.category']) // optimasi: ambil field yang dibutuhkan
                ->orderBy('created_at', 'desc')
                ->get();
    
            return FormatResponseJson::success($news, 'Berita berhasil diambil');
        } catch (\Throwable $th) {
            return FormatResponseJson::error(null, 'Gagal mengambil berita');
        }
    }
}
