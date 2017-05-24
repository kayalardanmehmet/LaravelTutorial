<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        if(!Schema::hasTable('posts')){
          Schema::create('posts', function (Blueprint $table) {
              $table->increments('id');
              $table->text('title');
              $table->text('content');
              $table->integer('vote')->default(0);
              $table->integer('view')->default(0);

              //Foreign key tanımlamak için önce sütunu tanımlıyoruz
              $table->integer('user_id')->unsigned();
              //Daha sonra bu sütun üzerinden foreign key tanımını gerçekleştiriyoruz
              $table->foreign('user_id')->references('id')->on('users');

              //created_at ve updated_at sütunları için
              $table->timestamps();
          });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::dropIfExists('posts');
    }
}
