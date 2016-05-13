<?php
namespace App\Http\Controllers;

use App\News;
use Carbon\Carbon;

class NewsController extends Controller
{

    public function index()
    {
        $news = News::where('created_at', '<=', Carbon::now())->orderBy('created_at', 'desc')->paginate(config('news.news_per_page'));
        
        return view('news.index', compact('news'));
    }

    public function showPost($id)
    {
        $news = News::whereId($id)->firstOrFail();
        return view('news.post')->withPost($news);
    }
}
