<?php

namespace App\Http\Controllers\Page;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Article;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $articles = Article::orderByDesc('id')->paginate(12);
        return view('page.article.index', compact('articles'));
    }

    public function detail($id, $slug)
    {
        $article = Article::find($id);

        if (!$article) {
            return redirect()->back()->with('error', 'Dữ liệu không tồn tại');
        }
        $articles = Article::where('id', '!=', $id)->orderByDesc('id')->limit(12)->get();

        return view('page.article.detail', compact('article', 'articles'));
    }
}
