<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Article;
use App\User;

class ArticleController extends Controller
{
    //
    public function index()
    {
        return Article::paginate(10);
    }

    public function show(Article $article)
    {
        return $article;
    }

    public function store(Request $request)
    {
        $article = Article::create($request->all());

        return response()->json($article, 201);
    }

    public function update(Request $request, Article $article)
    {
        $article->update($request->all());

        return response()->json($article, 200);
    }

    public function delete(Article $article)
    {
        $article->delete();

        return response()->json(null, 204);
    }
    
    public function users_posts($id)
    {
        $user = User::find($id);
        $articles = $user->articles()->paginate(10);

        return response()->json($articles, 200);
    }
    
    public function ctgry_posts($category)
    {
        $articles = Article::where('category', '=', $category)->paginate(10);

        return response()->json($articles, 200);
    }
}
