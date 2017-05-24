<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    //Aksi aşağıdaki gibi belirtilmedi sürece ORM için databasede tablo adı çoğul class adı olarak kabul edilir
    //protected $table = 'my_flights';

    //Constructor olarak parametre gönderebilmek için tanımlamamız gerekiyor
    //Default olarak laravel güvenlik sisteminden dolayı buna izin vermiyor
    protected $fillable = ['email', 'name', 'surname', 'password'];

    public function posts(){

        return $this->hasMany('App\Post');

    }
}
