<?php

namespace App\Http\Controllers;

use App\ArticleLike;
use App\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;


class ArticleLikeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {   
        $validator = Validator::make($request->all(), [
            'likedArticleID' => 'required|unique:articles',
        ]);

        if(Auth::check()){
        $articlelike = new ArticleLike();
        $articlelike->article_id = $request->likedArticleID;
        $articlelike->user_id = Auth::id();

        $articlelike->save();

        return response($articlelike, 201);
        }
        else{
            response(['error'=>'Unauthorised'], 401);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ArticleLike  $articleLike
     * @return \Illuminate\Http\Response
     */
    public function show(ArticleLike $articleLike)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ArticleLike  $articleLike
     * @return \Illuminate\Http\Response
     */
    public function edit(ArticleLike $articleLike)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ArticleLike  $articleLike
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ArticleLike $articleLike)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ArticleLike  $articleLike
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $article_like = ArticleLike::find($id);
        $article_authorid = $article_like->user_id;
        if(Auth::id() == $article_authorid){
            $article_like->delete();

            return response(['success'=>'ArticleLike deleted'], 204);
        }
        else{
            return response(['error'=>'Unauthorized'], 401);
        }
    }
}
