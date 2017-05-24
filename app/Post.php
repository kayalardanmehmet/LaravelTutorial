<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
  //Aksi aşağıdaki gibi belirtilmedi sürece ORM için databasede tablo adı çoğul class adı olarak kabul edilir
  //protected $table = 'my_flights';

  //Constructor olarak parametre gönderebilmek için tanımlamamız gerekiyor
  //Default olarak laravel güvenlik sisteminden dolayı buna izin vermiyor
  protected $fillable = ['title', 'content', 'user_id'];

  public function user(){
      return $this->belongsTo('\App\User');
  }

  //bir posta ait birden çok cevap olabilir. OneToMany ilişkileri bu şekilde tanımlıyoruz
  public function answers(){
      return $this->hasMany('\App\Answer')->orderBy('vote', 'desc');
  }
}
