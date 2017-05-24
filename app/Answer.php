<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    //Aksi aşağıdaki gibi belirtilmedi sürece ORM için databasede tablo adı çoğul class adı olarak kabul edilir
    //Bu class için 'answers'
    //protected $table = 'my_flights';

    //Constructor olarak parametre gönderebilmek için tanımlamamız gerekiyor
    //Default olarak laravel güvenlik sisteminden dolayı buna izin vermiyor
    protected $fillable = ['content', 'user_id', 'post_id'];

    public function user(){
        return $this->belongsTo('App\User');
    }

    public function post(){
        return $this->belongsTo('App\Post');
    }
}
