<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Article;
use App\ArticleLike;
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
        $validator = Validator::make($request->all(), [
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'title' => 'required|string',
            'body' => 'required|string',
            'category' => 'required|string',
            ]);
        $image = $request->file('image');
        $extension = $image->getClientOriginalExtension();
        Storage::disk('public')->put($image->getFilename().'.'.$extension,  File::get($image));
        
        $article = new Article();
        $article->title = $request->title;
        $article->category = $request->category;
        $article->body = $request->body;
        $article->user_id = Auth::id();

        $article->filename = $image->getFilename().'.'.$extension;
        $article->mime = $image->getClientMimeType();
        $article->original_filename = $image->getClientOriginalName();
        
        $article->save();

        return response($article, 201);
    }

    public function update(Request $request, Article $article)
    {
        $article->update($request->all());

        return response($article, 200);
    }

    public function delete(Article $article)
    {   
        if(Auth::id() == $article->user_id){
            $article->delete();

            return response(['success'=>'Article deleted'], 204);
        }
        else{
            return response(['error'=>'Unauthorised'], 401);
        }
    }
    
    public function users_posts($id)
    {
        $user = User::find($id);
        $articles = $user->articles()->paginate(10);

        return response($articles, 200);
    }
    
    public function ctgry_posts($category)
    {
        $articles = Article::where('category', '=', $category)->paginate(10);

        return response($articles, 200);
    }

    public function fvrite_posts($id)
    {   
        $article_likes = ArticleLike::where('user_id', $id)->get();
        $liked_ids = [];
        foreach($article_likes as $like){
            array_push($liked_ids, $like->article_id);
        }
        $liked_articles = Article::wherein('id', $liked_ids)->get();
        return response($liked_articles ,200);
    }
}
