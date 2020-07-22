<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    //
    protected $fillable = ['title', 'body', 'category'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
